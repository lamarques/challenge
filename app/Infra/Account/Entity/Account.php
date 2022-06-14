<?php

namespace App\Infra\Account\Entity;


class Account extends \App\Models\Account
{
    public int $id;
    public int $user_id;
    public float $balance;
    public float $blocked_balance;
    public float $credit_balance;
}
