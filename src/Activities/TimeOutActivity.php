<?php

namespace Gashey\MobiverseUssd\Activities;

use Gashey\MobiverseUssd\Lib\UssdActivity;
use Gashey\MobiverseUssd\Lib\UssdResponse;

class TimeOutActivity extends UssdActivity
{
    public function run()
    {
        $this->response->UssdServiceOp = UssdResponse::RELEASE;
        $this->response->Message = "Oops! Seems your session has timed out!";
        return $this;
    }
}
