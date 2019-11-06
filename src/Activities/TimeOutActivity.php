<?php

namespace Gashey\LaravelMobiverseUssd\Activities;

use Gashey\LaravelMobiverseUssd\Lib\UssdActivity;
use Gashey\LaravelMobiverseUssd\Lib\UssdResponse;

class TimeOutActivity extends UssdActivity
{
    public function run()
    {
        $this->response->UssdServiceOp = UssdResponse::RELEASE;
        $this->response->Message = "Oops! Seems your session has timed out!";
        return $this;
    }
}
