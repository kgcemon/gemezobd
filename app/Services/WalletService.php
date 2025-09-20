<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    /**
     * Add balance to user's wallet
     */
    public function addBalance(User $user, float $amount): bool
    {
        DB::beginTransaction();
        try {
            $user->wallet += $amount;
            $user->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Deduct balance from user's wallet
     */
    public function deductBalance(User $user, float $amount): bool
    {
        if ($user->wallet < $amount) {
            return false;
        }

        DB::beginTransaction();
        try {
            $user->wallet -= $amount;
            $user->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get wallet balance
     */
    public function getBalance(User $user): float
    {
        return $user->wallet;
    }

    /**
     * Transfer balance between two users
     */
    public function transfer(User $fromUser, User $toUser, float $amount): bool
    {
        if ($fromUser->wallet < $amount) {
            return false; // Insufficient funds
        }

        DB::beginTransaction();
        try {
            $fromUser->wallet -= $amount;
            $fromUser->save();

            $toUser->wallet += $amount;
            $toUser->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
