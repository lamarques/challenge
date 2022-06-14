<?php

namespace App\Application\Account;

use App\Application\Account\Dto\AccountBalanceResponseDto;
use App\Application\Account\Dto\AccountErrorResponse;
use App\Application\Account\Dto\AccountResponseDto;
use App\Application\Account\Shared\AccountApplicationInterface;
use App\Domain\Account\AccountDomain;
use App\Domain\Account\Shared\AccountDomainInterface;
use App\Domain\Account\Validators\AccountRequest;
use App\Infra\Account\AccountRepository;
use App\Infra\Account\Entity\Transfer;
use App\Infra\Account\Shared\AccountRepositoryInterface;
use App\Infra\Account\Shared\TransferRepositoryInterface;
use App\Infra\Account\TransferRepository;
use App\Jobs\NotifyJob;
use App\Models\User;
use App\Services\ExternaAuthorizerService;
use Illuminate\Support\Facades\DB;

class AccountApplication implements AccountApplicationInterface
{

    protected AccountDomain $domain;
    protected AccountRepository $accountRepository;
    protected TransferRepository $TransferRepository;
    protected ExternaAuthorizerService $externalAuthorizeService;

    private $transfer;

    public function __construct(AccountDomainInterface $domain, AccountRepositoryInterface $accountRepository, TransferRepositoryInterface $TransferRepository, ExternaAuthorizerService $externalAuthorizeService)
    {
        $this->domain = $domain;
        $this->accountRepository = $accountRepository;
        $this->TransferRepository = $TransferRepository;
        $this->externalAuthorizeService = $externalAuthorizeService;
    }

    public function moneyTransfer(AccountRequest $request, User $user): AccountResponseDto|AccountErrorResponse
    {
        $error =  new AccountErrorResponse();
        $validate = $this->domain->moneyTransfer($request, $user);
        $payee = User::find($validate->payee);
        if(!$payee) {
            $error->error = 'This payee user is not valid';
            $error->code = 400;
            return $error;
        }

        $account = $this->accountRepository->getAccount($user);
        if (!$this->accountRepository->haveBalance($account, $validate->value)) {
            $error->error = 'Insufficient funds';
            $error->code = 400;
            return $error;
        }

        if (!$this->externalAuthorizeService->execute()) {
            $error->error = 'Unauthorized External';
            $error->code = 401;
            return $error;
        }

        DB::beginTransaction();
        try {
            $this->transfer = $this->TransferRepository->createTransfer($user, $payee, $validate->value);
            $this->transfer = $this->TransferRepository->updateTransfer($this->transfer, 'under_analysis');

            $account_payee = $this->accountRepository->getAccount($payee);
            $account = $this->accountRepository->updateBalance($account, $validate->value);
            $account_payee = $this->accountRepository->updateBalance($account_payee, $validate->value, false);

            $this->transfer = $this->TransferRepository->updateTransfer($this->transfer, 'approved');

            dispatch(new NotifyJob($payee));

            DB::commit();

            $response = new AccountResponseDto();
            $response->message = "Transfer was a success";
            $response->payload = $request;
            $response->transfer = $this->transfer;

            return $response;

        } catch (\Exception $e) {
            DB::rollBack();
            $error->error = $e->getMessage();
            $error->code = 400;
            return $error;
        }

    }

    public function balance(User $user): AccountBalanceResponseDto
    {
        $response = new AccountBalanceResponseDto();
        $response->balance = $this->accountRepository->getBalance($user);
        return $response;
    }
}
