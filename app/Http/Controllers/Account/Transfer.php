<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Transfer extends Controller
{

    public function moneyTransfer(Request $request) {
        dd($request->all());
    }

}
