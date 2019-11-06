<?php

namespace Gashey\LaravelMobiverseUssd\Activities;

use Gashey\LaravelMobiverseUssd\Lib\UssdActivity;
use Gashey\LaravelMobiverseUssd\Lib\UssdResponse;

class HomeActivity extends UssdActivity
{
    /**
     * @return string
     */
    public function run()
    {
        $this->response->UssdServiceOp = UssdResponse::RELEASE;
        $this->response->Message = 'Ussd is working!';
        return $this;
    }
}
