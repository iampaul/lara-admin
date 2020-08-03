<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Config;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
                
                $config = array(
                    'driver'     => 'smtp',
                    'host'       => getGeneralSettingValue('SMTP_HOST'),
                    'port'       => getGeneralSettingValue('SMTP_PORT'),
                    'from'       => array('address' => getGeneralSettingValue('ADMIN_EMAIL'), 'name' => getGeneralSettingValue('SITE_TITLE')),
                    'encryption' => 'ssl' ,
                    'username'   => getGeneralSettingValue('SMTP_EMAIL'),
                    'password'   => getGeneralSettingValue('SMTP_PASS'),                   
                );
                Config::set('mail', $config);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
