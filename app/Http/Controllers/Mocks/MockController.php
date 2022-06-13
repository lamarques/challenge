<?php

namespace App\Http\Controllers\Mocks;

use App\Http\Controllers\Controller;

class MockController extends Controller
{

    public function moneytransfer() {
        return [
            'success' => true,
        ];
    }

}
