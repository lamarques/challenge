<?php

namespace App\Domain\Account;

use App\Domain\Account\Dto\AccountDto;
use App\Domain\Account\Shared\AccountDomainInterface;
use App\Domain\Account\Shared\AccountRequestInterface;
use App\Models\User;

class AccountDomain implements AccountDomainInterface
{

    public function moneyTransfer(AccountRequestInterface $request, User $user): AccountDto
    {
        $account = new AccountDto();
        $account->payer = $user->id;
        $account->payee = $request-> payee;
        $account->value = floatval($request->value);

        return $account;
    }
}
