<?php

namespace Gashey\MobiverseUssd\Activities;

use Gashey\MobiverseUssd\Lib\UssdActivity;
use Gashey\MobiverseUssd\Lib\UssdResponse;

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
