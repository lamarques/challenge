<?php

namespace App\Application\Account\Dto;

use App\Domain\Account\Validators\AccountRequest;
use App\Models\Transfer;

class AccountResponseDto
{
    public string $message;
    public AccountRequest $payload;
    public Transfer $transfer;
}
