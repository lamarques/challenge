<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyJob;
use App\Models\Account;
use App\Models\User;
use App\Models\Transfer;
use App\Services\ExternaAuthorizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{

    public function __construct(private ExternaAuthorizerService $externalAuthorizeService)
    {
    }

    public function moneyTransfer(Request $request) {
        $user = auth()->user();
        if ($user->type === "logist") {
            return $this->returnResponseError('This user is not allowed to transfer values', 400);
        }
        $validator = Validator::make($request->all(), [
            'payee' => 'required',
            'value' => 'required|numeric|min:0|not_in:0',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $payer_id = $user->id;
        $payee_id = $request->payee;
        $value = $request->value;

        $payee = User::find($payee_id);
        if(!$payee) {
            return $this->returnResponseError('This payee user is not valid', 400);
        }

        $account = Account::where(['user_id' => $payer_id])->first();
        if (!$account) {
            $account = $this->createZeroAccount($payer_id);
        }
        $funds = $account->balance - $account->blocked_balance + $account->credit_balance;

        if ($value > $funds) {
            return $this->returnResponseError('Insufficient funds', 400);
        }
        if (!$this->externalAuthorizeService->execute()) {
            return $this->returnResponseError('Unauthorized External', 401);
        }

        DB::beginTransaction();
        try {
            $transfer = $this->createTransfer($payer_id, $payee_id, $value);
            $transfer = $this->updateTransfer($transfer, 'under_analysis');

            $account_peyee = Account::find($payee_id);
            if (!$account_peyee) {
                $account_peyee = $this->createZeroAccount($payee_id);
            }
            $account->balance = $account->balance - $value;
            $account_peyee->balance = $account_peyee->balance + $value;

            $account->save();
            $account_peyee->save();

            $transfer = $this->updateTransfer($transfer, "approved");

            dispatch(new NotifyJob($payee));

            DB::commit();
            return response()->json([
                "message" => "Transfer was a success",
                "payload" => $request->all(),
                "transfer" => $transfer,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnResponseError('Transfer failure: ' . $e->getMessage(), 400);
        }


    }

    private function createZeroAccount($user_id) {
        $account = new Account();
        $account->user_id = $user_id;
        $account->balance = 0.00;
        $account->blocked_balance = 0.00;
        $account->credit_balance = 0.00;
        $account->save();
        return $account;
    }

    public function returnResponseError(string $message, int $code) {
        return response(['error' => $message, $code]);
    }

    private function createTransfer($payer, $payee, $ammount) {
        $transfer = new Transfer();
        $transfer->payer = $payer;
        $transfer->payee = $payee;
        $transfer->appointment_date = date('Y-m-d');
        $transfer->amount = $ammount;
        $transfer->status = 'new';
        $transfer->save();
        return $transfer;
    }

    private function updateTransfer($transfer, $status) {
        $transfer->status = $status;
        $transfer->save();
        return $transfer;
    }
}
