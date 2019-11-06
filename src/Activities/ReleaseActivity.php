<?php

namespace Gashey\LaravelMobiverseUssd\Activities;

use Gashey\LaravelMobiverseUssd\Lib\UssdActivity;
use Gashey\LaravelMobiverseUssd\Lib\UssdResponse;

class ReleaseActivity extends UssdActivity
{
    public function run()
    {
        $this->response->UssdServiceOp = UssdResponse::RELEASE;
        $this->response->Message = "Good bye!";
        return $this;
    }
}
