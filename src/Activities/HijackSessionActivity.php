<?php

namespace Gashey\LaravelMobiverseUssd\Activities;

use Gashey\LaravelMobiverseUssd\Lib\UssdActivity;
use Gashey\LaravelMobiverseUssd\Lib\UssdResponse;

class HijackSessionActivity extends UssdActivity
{
    public function run()
    {
        $this->response->UssdServiceOp == UssdResponse::RELEASE;
        $this->response->Message = "This is bad! Your session has been hijacked!";
        return $this;
    }
}
