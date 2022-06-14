<?php

namespace App\Infra\Account;

use App\Infra\Account\Entity\Account;
use App\Infra\Account\Shared\AccountRepositoryInterface;
use App\Models\User;

class AccountRepository extends Account implements AccountRepositoryInterface
{

    public function getAccount(User $user): Account
    {
        $account = $this->where(['user_id' => $user->id])->first();
        if (!$account) {
            $account = $this->makeAccount($user->id);
        }
        return $account;
    }

    public function haveBalance(Account $account, float $value = 0): bool
    {
        $account =(object) $account->getAttributes();
        return (($account->balance - $account->blocked_balance + $account->credit_balance) > $value);
    }

    public function makeAccount(User $user): Account
    {
        $account = new Account();
        $account->user_id = $user->id;
        $account->balance = 0.00;
        $account->blocked_balance = 0.00;
        $account->credit_balance = 0.00;
        $account->save();
        return $account;
    }

    public function getBalance(User $user): float
    {
        $account =(object) $this->getAccount($user)->getAttributes();
        return ($account->balance - $account->blocked_balance + $account->credit_balance);
    }

    public function updateBalance(Account $account, $value, $debit = true) {
        if ($debit) {
            $account->setAttribute('balance', $account->getAttribute('balance') - $value);
        } else {
            $account->setAttribute('balance', $account->getAttribute('balance') + $value);
        }
        $account->save();
        return $account;
    }
}
