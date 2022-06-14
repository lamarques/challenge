<?php

namespace App\Http\Controllers\Account;

use App\Application\Account\Shared\AccountApplicationInterface;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function __construct(private AccountApplicationInterface $accountApplication)
    {
    }

    public function balance() {
        return response()->json(
            $this->accountApplication->balance(auth()->user())
        );
    }

}
