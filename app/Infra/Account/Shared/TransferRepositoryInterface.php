<?php

namespace App\Infra\Account\Shared;

use App\Models\User;

interface TransferRepositoryInterface
{
    public function createTransfer(User $payer, User $payee, float $ammount);
    public function updateTransfer($transfer, string $status);
}
