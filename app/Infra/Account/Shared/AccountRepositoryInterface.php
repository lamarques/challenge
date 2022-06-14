<?php

namespace App\Infra\Account\Shared;

use App\Infra\Account\Entity\Account;
use App\Models\User;

interface AccountRepositoryInterface
{
    public function getAccount(User $user): Account;
    public function haveBalance(Account $account, float $value): bool;
    public function makeAccount(User $user): Account;
    public function getBalance(User $user): float;
}
