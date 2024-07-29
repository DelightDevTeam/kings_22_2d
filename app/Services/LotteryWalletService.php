<?php

namespace App\Services;

use App\Enums\TransactionName;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LotteryWalletService
{
    /**
     * Transfer lottery balance from one user to another.
     *
     * @param  User  $fromUser  The user from whom the balance is transferred.
     * @param  User  $toUser  The user to whom the balance is transferred.
     * @param  float  $amount  The amount to transfer.
     * @param  TransactionName  $transactionName  The type of transaction.
     * @param  array  $meta  Additional metadata for the transfer.
     *
     * @throws ValidationException If the `fromUser` doesn't have enough balance.
     */
    public function transfer(User $fromUser, User $toUser, float $amount, TransactionName $transactionName, array $meta = [])
    {
        // Ensure the user has sufficient balance
        if ($fromUser->lottery_balance < $amount) {
            throw ValidationException::withMessages([
                'lottery_balance' => 'Insufficient lottery balance for transfer.',
            ]);
        }

        DB::transaction(function () use ($fromUser, $toUser, $amount, $transactionName, $meta) {
            // Re-fetch users to ensure accurate data
            $fromUser = User::findOrFail($fromUser->id);
            $toUser = User::findOrFail($toUser->id);

            // Deduct from the sender's balance
            $fromUser->lottery_balance -= $amount;
            $fromUser->save();

            // Add to the receiver's balance
            $toUser->lottery_balance += $amount;
            $toUser->save();

            // Record the transaction with metadata
            $this->recordTransaction($fromUser, $toUser, $transactionName, $meta);
        });
    }

    /**
     * Record a transaction in the system.
     *
     * @param  User  $fromUser  The sender of the transfer.
     * @param  User  $toUser  The receiver of the transfer.
     * @param  TransactionName  $transactionName  The type of transaction.
     * @param  array  $meta  Additional metadata for the transaction.
     */
    protected function recordTransaction(User $fromUser, User $toUser, TransactionName $transactionName, array $meta = [])
    {
        // Custom logic to record the transaction
        $metaData = array_merge([
            'name' => $transactionName->value,
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'opening_balance' => $fromUser->lottery_balance,
        ], $meta);

        // Insert into a transaction table or use a wallet library method to record the transaction
        // Example: use WalletService's forceTransferFloat or similar to create the record.
    }
}
