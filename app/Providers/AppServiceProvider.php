<?php

namespace App\Providers;

use App\Models\AppSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view): void {
            $rawWhatsapp = AppSetting::getValue('store_whatsapp', '081111111111');
            $digits = preg_replace('/\D+/', '', $rawWhatsapp) ?? '';

            if (str_starts_with($digits, '0')) {
                $waInternational = '62'.substr($digits, 1);
            } else {
                $waInternational = $digits;
            }

            $view->with('storeWhatsappDisplay', $rawWhatsapp);
            $view->with('storeWhatsappLink', 'https://wa.me/'.$waInternational);
        });
    }
}
