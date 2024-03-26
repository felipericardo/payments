<?php

namespace App\Jobs;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidPayerException;
use App\Exceptions\TransactionNotFoundException;
use App\Exceptions\UnauthorizedTransactionException;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;

class ProcessTransaction extends Job
{
    private int $transactionId;

    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    public function handle(
        TransactionRepository $transactionRepository,
        TransactionService $transactionService
    ) {
        $transaction = $transactionRepository->getTransactionById($this->transactionId);
        if (empty($transaction)) {
            throw new TransactionNotFoundException();
        }

        $transactionService->process($transaction);
    }
}
