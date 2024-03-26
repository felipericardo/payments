<?php

namespace App\Repositories;

use App\Entities\Transaction;
use App\Models\Transaction as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends Repository
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function hydrateEntity(string $entityName, $data)
    {
        /** @var Transaction $transaction */
        $transaction = parent::hydrateEntity($entityName, $data);

        foreach ($data as $fieldName => $datum) {
            switch ($fieldName) {
                case 'payer_id':
                    $transaction->setPayer($this->userRepository->getUserById($datum));
                    break;
                case 'payee_id':
                    $transaction->setPayee($this->userRepository->getUserById($datum));
                    break;
            }
        }

        return $transaction;
    }

    public function save(Transaction $transaction)
    {
        if (empty($transaction->getCreatedAt())) {
            $transaction->setCreatedAt(Carbon::now());
        }
        $transaction->setUpdatedAt(Carbon::now());

        $model = empty($transaction->getId())
            ? new Model()
            : Model::find($transaction->getId());

        $model->payer_id   = $transaction->getPayer()->getId();
        $model->payee_id   = $transaction->getPayee()->getId();
        $model->value      = $transaction->getValue();
        $model->status     = $transaction->getStatus();
        $model->message    = $transaction->getMessage();
        $model->created_at = $transaction->getCreatedAt();
        $model->updated_at = $transaction->getUpdatedAt();

        $model->save();

        if (empty($transaction->getId())) {
            $transaction->setId($model->id);
        }
    }

    public function getTransactionById(int $transactionId)
    {
        $transaction = DB::selectOne("SELECT * FROM transactions WHERE id = :id", ['id' => $transactionId]);
        if (!empty($transaction)) {
            $transaction = $this->hydrateEntity('Transaction', $transaction);
        }

        return $transaction;
    }
}
