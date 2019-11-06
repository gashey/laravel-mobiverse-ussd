<?php

return [
    /**
     * The ussd code for your application
     */
    "code" => env('APP_USSD_CODE', '*1234#'),

    /**
     * This is the entry point of your ussd application.
     */
    "home" => Gashey\LaravelMobiverseUssd\Activities\HomeActivity::class,

    /**
     * Called on a release request
     */
    "release" => Gashey\LaravelMobiverseUssd\Activities\ReleaseActivity::class,

    /**
     * Called when session times out
     */
    "timeout" => Gashey\LaravelMobiverseUssd\Activities\TimeOutActivity::class,

    /**
     * Called when a hijack session event occurs
     */
    "hijack_session" => Gashey\LaravelMobiverseUssd\Activities\HijackSessionActivity::class,

    "go_back_key" => "#",

    "session_lifetime_minutes" => "1",

    "cache_driver" => env('CACHE_DRIVER', 'file'),

    "network_mapping" => array('01' => 'MTN', '02' => 'VODAFONE', '03' => 'AIRTEL-TIGO', '04' => 'AIRTEL-TIGO', '05' => 'GLO'),
];
