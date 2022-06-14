<?php

namespace App\Domain\Account\Shared;

use App\Domain\Account\Dto\AccountDto;
use App\Models\User;

Interface AccountDomainInterface
{
    public function moneyTransfer(AccountRequestInterface $request, User $user): AccountDto;
}
