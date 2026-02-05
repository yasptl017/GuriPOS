<?php

namespace App\Providers;

use App\Models\CookieConsent;
use App\Models\CustomPage;
use App\Models\FacebookPixel;
use App\Models\Footer;
use App\Models\FooterLink;
use App\Models\FooterSocialLink;
use App\Models\GoogleAnalytic;
use App\Models\Setting;
use App\Models\TawkChat;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $setting = Setting::first();
            $social_links = FooterSocialLink::all();
            $footer = Footer::first();
            $googleAnalytic = GoogleAnalytic::first();
            $facebookPixel = FacebookPixel::first();

            $custom_pages = CustomPage::where('status', 1)->get();
            $cookieConsent = CookieConsent::first();
            $tawk_setting = TawkChat::first();

            $view->with('currency_icon', $setting->currency_icon);
            $view->with('setting', $setting);
            $view->with('social_links', $social_links);
            $view->with('footer', $footer);
            $view->with('googleAnalytic', $googleAnalytic);
            $view->with('facebookPixel', $facebookPixel);

            $view->with('breadcrumb', $setting->breadcrumb_image);
            $view->with('default_user_avatar', $setting->default_avatar);
            $view->with('custom_pages', $custom_pages);
            $view->with('cookie_consent', $cookieConsent);
            $view->with('tawk_setting', $tawk_setting);
        });


    }
}
