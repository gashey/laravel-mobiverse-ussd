<?php

namespace Gashey\MobiverseUssd\Lib;

class UssdResponse
{

    /**
     * RESPONSE TYPES:
     */
    /**
     * indicates that the application is ending the USSD session.
     */
    const RELEASE = '17';

    /**
     * indicates a response in an already existing USSD session.
     */
    const RESPONSE = '2';

    /**
     * Indicates the type of USSD Request.
     * @required
     * @var string
     */
    public $UssdServiceOp;

    /**
     * Represents the actual message on the mobile subscriber’s phone
     *
     * @required
     * @var string
     */
    public $Message;

    /**
     * Represents an error message to be prefixed to the message on the mobile subscriber’s phone
     *
     * @required
     * @var string
     */
    public $ErrorMessage;

    /**
     * UUID string representing a unique identifier for the current USSD Session.
     * Required: Yes
     *
     * @var string
     */
    public $SessionId;

    /**
     * Represents data that API client wants API service to send in the next USSD request. This data is sent in the next USSD request only and is subsequently discarded. (Max of 100 characters)
     * @var string
     */
    public $ClientState;

    /**
     * It is used to indicate whether the current response in a USSD session from a mobile subscriber should be masked by Mobiverse. This is a useful security feature for masking sensitive information such as financial transactions.
     * @var bool
     */
    public $Mask;

    /**
     * It indicates whether the next incoming request should be masked by Mobiverse. It is also useful for masking sensitve information such as user PIN’s.
     * @var bool
     */
    public $MaskNextRoute;

    public function __construct()
    {
        $this->Type = self::RESPONSE;
    }

    public function asMobiverseResponse(): array
    {
        return array(
            'message' => $this->ErrorMessage . $this->Message,
            'ussdServiceOp' => $this->UssdServiceOp,
            'sessionID' => $this->SessionId
        );
    }
}
