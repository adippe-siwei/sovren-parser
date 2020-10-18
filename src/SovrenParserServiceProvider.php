<?php

namespace Siwei\SovrenParser;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class SovrenParserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sovren.php', 'sovren');

        $this->app->singleton('sovren-parser', static function () {
            $client = new Client([
                'base_uri' => config('sovren.sovren-endpoint'),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Sovren-AccountId' => config('sovren.sovren-accountid'),
                    'Sovren-ServiceKey' => config('sovren.sovren-servicekey'),
                    'User-Agent' => 'Laravel'
                ]
            ]);
            return new Sovren($client);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/sovren.php' => config_path('sovren-parse.php'),
            ], 'config');
        }

        $this->publishes([
            __DIR__ . '/../config/sovren.php' => config_path('sovren.php'),
        ], 'config');
    }
}
