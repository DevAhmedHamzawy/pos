<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\InstallmentNotificationService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

        $service = app(InstallmentNotificationService::class);

        $view->with([
            'lateInstallments' => $service->lateInstallments(),
            'lateInstallmentsCount' => $service->count(),
            'lateInstallmentsTotal' => $service->totalRemaining(),
        ]);

    });
    }
}
