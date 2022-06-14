<?php

namespace App\Infra\Account;

use App\Infra\Account\Entity\Transfer;
use App\Infra\Account\Shared\TransferRepositoryInterface;
use App\Models\User;

class TransferRepository extends Transfer implements TransferRepositoryInterface
{
    const STATUS_NEW = 'new';

    public function createTransfer(User $payer, User $payee, float $ammount)
    {
        $transfer = new \App\Models\Transfer();
        $transfer->payer = $payer->id;
        $transfer->payee = $payee->id;
        $transfer->appointment_date = date('Y-m-d');
        $transfer->amount = $ammount;
        $transfer->status = self::STATUS_NEW;
        $transfer->save();
        return $transfer;
    }

    public function updateTransfer($transfer, string $status)
    {
        $transfer->status = $status;
        $transfer->save();
        return $transfer;
    }

}
