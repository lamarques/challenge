<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Domain\Account\Shared\AccountDomainInterface::class, \App\Domain\Account\AccountDomain::class);
        $this->app->bind(\App\Domain\Account\Shared\AccountRequestInterface::class, \App\Domain\Account\Validators\AccountRequest::class);
        $this->app->bind(\App\Application\Account\Shared\AccountApplicationInterface::class, \App\Application\Account\AccountApplication::class);
        $this->app->bind(\App\Infra\Account\Shared\AccountRepositoryInterface::class, \App\Infra\Account\AccountRepository::class);
        $this->app->bind(\App\Infra\Account\Shared\TransferRepositoryInterface::class, \App\Infra\Account\TransferRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
