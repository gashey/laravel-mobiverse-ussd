<?php

namespace Gashey\MobiverseUssd\Activities;

use Gashey\MobiverseUssd\Lib\UssdActivity;
use Gashey\MobiverseUssd\Lib\UssdResponse;

class HijackSessionActivity extends UssdActivity
{
    public function run()
    {
        $this->response->UssdServiceOp == UssdResponse::RELEASE;
        $this->response->Message = "This is bad! Your session has been hijacked!";
        return $this;
    }
}
