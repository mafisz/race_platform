<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        \Carbon\Carbon::setLocale('pl');
        $translator = \Carbon\Carbon::getTranslator();
        $resources = $translator->getCatalogue('pl')->all('messages');
        $resources['after'] = ':time temu';
        $resources['before'] = 'za :time';
        $translator->addResource('array', $resources, 'pl');

        if (class_exists('\App\SiteInfo')) {
            $banner = \App\SiteInfo::where('name', 'banner')->first();
            \View::share('banner', $banner);
        }


        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
