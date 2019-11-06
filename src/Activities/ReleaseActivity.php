<?php

namespace Gashey\MobiverseUssd\Activities;

use Gashey\MobiverseUssd\Lib\UssdActivity;
use Gashey\MobiverseUssd\Lib\UssdResponse;

class ReleaseActivity extends UssdActivity
{
    public function run()
    {
        $this->response->UssdServiceOp = UssdResponse::RELEASE;
        $this->response->Message = "Good bye!";
        return $this;
    }
}
