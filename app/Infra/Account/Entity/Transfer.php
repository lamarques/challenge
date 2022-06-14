<?php

namespace App\Infra\Account\Entity;

class Transfer extends \App\Models\Transfer
{
    public int $id;
    public int $payer;
    public int $payee;
    public string $appointment_date;
    public float $amount;
    public bool $status;
}
