<?php

namespace App\Services;

use App\Entities\Transaction;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidPayerException;
use App\Exceptions\OperationNotAllowedException;
use App\Exceptions\PayeeNotFoundException;
use App\Exceptions\PayerNotFoundException;
use App\Exceptions\UnauthorizedTransactionException;
use App\Jobs\ProcessTransaction;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;

class TransactionService
{
    private UserRepository $userRepository;
    private TransactionRepository $transactionRepository;
    private AuthorizerService $authorizerService;
    private NotificationService $notificationService;

    public function __construct(
        UserRepository $userRepository,
        TransactionRepository $transactionRepository,
        AuthorizerService $authorizerService,
        NotificationService $notificationService
    ) {
        $this->userRepository        = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->authorizerService     = $authorizerService;
        $this->notificationService   = $notificationService;
    }

    public function create(int $payerId, int $payeeId, float $value): Transaction
    {
        $payer = $this->userRepository->getUserById($payerId);
        if (empty($payer)) {
            throw new PayerNotFoundException();
        }

        $payee = $this->userRepository->getUserById($payeeId);
        if (empty($payee)) {
            throw new PayeeNotFoundException();
        }

        $transaction = new Transaction();
        $transaction->setPayer($payer);
        $transaction->setPayee($payee);
        $transaction->setValue($value);
        $transaction->setStatus('PENDING');

        $this->transactionRepository->save($transaction);

        $this->sendToQueue($transaction->getId());

        return $transaction;
    }

    public function sendToQueue(int $transactionId): void
    {
        dispatch(new ProcessTransaction($transactionId));
    }

    public function process(Transaction $transaction): void
    {
        try {
            $transaction->setStatus('PROCESSING');
            $this->transactionRepository->save($transaction);

            $this->validate($transaction);

            $this->userRepository->decreaseBalance($transaction->getPayer()->getId(), $transaction->getValue());
            $this->userRepository->increaseBalance($transaction->getPayee()->getId(), $transaction->getValue());

            $transaction->setStatus('COMPLETED');
            $this->transactionRepository->save($transaction);

            $this->notificationService->sendToQueue(
                $transaction->getPayee(),
                sprintf('Transfer received R$ %s', number_format($transaction->getValue(), 2, ',', '.'))
            );
        } catch (InvalidPayerException|InsufficientBalanceException|UnauthorizedTransactionException $e) {
            $transaction->setStatus('FAIL');
            $transaction->setMessage($e->getMessage());

            $this->transactionRepository->save($transaction);
        }
    }

    private function validate(Transaction $transaction): void
    {
        if ($transaction->getPayer()->getType() === 'STORE') {
            throw new InvalidPayerException('Stores cannot send money!');
        } elseif ($transaction->getPayer()->getBalance() < $transaction->getValue()) {
            throw new InsufficientBalanceException();
        } elseif (!$this->authorizerService->isAuthorized()) {
            throw new UnauthorizedTransactionException();
        }
    }

    public function cancel(Transaction $transaction): void
    {
        if ($transaction->getStatus() !== 'COMPLETED') {
            throw new OperationNotAllowedException();
        }

        $this->userRepository->decreaseBalance($transaction->getPayee()->getId(), $transaction->getValue());
        $this->userRepository->increaseBalance($transaction->getPayer()->getId(), $transaction->getValue());

        $transaction->setStatus('CANCELLED');
        $this->transactionRepository->save($transaction);
    }
}
