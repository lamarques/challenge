<?php

namespace App\Http\Controllers\Account;

use App\Application\Account\AccountApplication;
use App\Application\Account\Shared\AccountApplicationInterface;
use App\Domain\Account\Validators\AccountRequest;
use App\Http\Controllers\Controller;

class TransferController extends Controller
{
    protected AccountApplication $accountApplication;

    public function __construct(AccountApplicationInterface $accountApplication)
    {
        $this->accountApplication = $accountApplication;
    }

    public function moneyTransfer(AccountRequest $request) {
        $application = $this->accountApplication->moneyTransfer($request, auth()->user());

        if (isset($application->error)) {
            return response(['error' => $application->error, $application->code]);
        }
        return response()->json($application);
    }
}
