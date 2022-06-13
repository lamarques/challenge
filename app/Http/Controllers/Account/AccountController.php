<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function balance() {
        $user = Auth::user();
        $account = Account::where('user_id', $user->id)->first();
        $balance = $account->balance - $account->blocked_balance + $account->credit_balance;
        return response()->json(['balance' => $balance]);
    }

}
