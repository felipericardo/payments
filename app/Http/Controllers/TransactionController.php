<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionNotFoundException;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller
{
    public function create(
        Request $request,
        TransactionService $transactionService
    ) {
        $this->validate($request, [
            'payer' => ['required', 'int'],
            'payee' => ['required', 'int'],
            'value' => ['required', 'numeric', 'min:0.01'],
        ]);

        try {
            $transaction = $transactionService->create(
                $request->input('payer'),
                $request->input('payee'),
                $request->input('value'),
            );
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($transaction->toArray());
    }

    public function get(
        int $transactionId,
        TransactionRepository $transactionRepository
    ) {
        try {
            $transaction = $transactionRepository->getTransactionById($transactionId);
            if (empty($transaction)) {
                throw new TransactionNotFoundException();
            }
        } catch (TransactionNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json($transaction->toArray());
    }

    public function cancel(
        int $transactionId,
        TransactionRepository $transactionRepository,
        TransactionService $transactionService
    ) {
        try {
            $transaction = $transactionRepository->getTransactionById($transactionId);
            if (empty($transaction)) {
                throw new TransactionNotFoundException();
            }

            $transactionService->cancel($transaction);

            $transaction = $transactionRepository->getTransactionById($transactionId);
        } catch (TransactionNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json($transaction->toArray());
    }
}
