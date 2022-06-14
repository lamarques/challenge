<?php

namespace App\Application\Account\Shared;

use App\Application\Account\Dto\AccountBalanceResponseDto;
use App\Application\Account\Dto\AccountErrorResponse;
use App\Application\Account\Dto\AccountResponseDto;
use App\Domain\Account\Validators\AccountRequest;
use App\Models\User;

interface AccountApplicationInterface
{
    public function moneyTransfer(AccountRequest $request, User $user): AccountResponseDto|AccountErrorResponse;
    public function balance(User $user): AccountBalanceResponseDto;
}
