<?php

namespace Gashey\MobiverseUssd\Lib;

class UssdRequest
{

    /**
     * REQUEST TYPES:
     */

    /**
     * indicates the first message in a USSD Session
     */
    const INITIATION = '1';

    /**
     * indicates a follow up in an already existing USSD session.
     */
    const RESPONSE = '18';

    /**
     * indicates that the subscriber is ending the USSD session.
     */
    const RELEASE = '30';

    /**
     * indicates that the USSD session has timed out.
     */
    const TIMEOUT = 'Timeout';

    /**
     * indicates that the user data should not be passed onto Hubtel (Safaricom Only).
     */
    const HIJACKSESSION = 'HijackSession';

    /**
     * Represents the phone number of the mobile subscriber.
     * Required: Yes
     *
     * @var string
     */
    public $Msisdn;

    /**
     * UUID string representing a unique identifier for the current USSD Session.
     * Required: Yes
     *
     * @var string
     */
    public $SessionId;

    /**
     * Represents the USSD Service code assigned by the network.
     * Required: Yes
     *
     * @var string
     */
    public $UssdString;

    /**
     * Indicates the type of USSD Request.
     * Required: Yes
     *
     * @var string
     */
    public $UssdServiceOp;

    /**
     * Represents the actual text entered by the mobile subscriber. For initiation, this will represent the USSD string entered by the subscriber. For Response, this will be the message sent.
     * Required: Yes
     *
     * @var string
     */
    public $Message;

    /**
     * Indicates the network operator the subscriber belongs to.
     * Required: Yes
     * @var string
     */
    public $Network;

    /**
     * Indicates the position of the current message in the USSD session.
     * Required: Yes
     *
     * @var int
     */
    public $Sequence;

    /**
     * Represents data that API client asked API service to send from the previous USSD request. This data is only sent in the current request and is then discarded.
     * Maximum of 100 characters.
     * Required: No
     *
     * @var string
     */
    public $ClientState;

    /**
     * Any network specific data will be sent through this parameter. This allows Hubtel to route data that is only available on a particular network to be routed to your application.
     *
     * Required: No
     * @var stdClass
     */
    public $MetaData;

    static public function ToUssdRequest(array $params): UssdRequest
    {
        $request = new UssdRequest();
        $request->UssdServiceOp = $params['ussdServiceOp'];
        $request->Msisdn = $params['msisdn'];
        $request->SessionId = $params['sessionID'];
        $request->UssdString = $request->UssdServiceOp == UssdRequest::INITIATION ? $params['ussdString'] : "";
        $request->Message = $request->UssdServiceOp == UssdRequest::RESPONSE ? $params['ussdString'] : "";
        $network_mapping = config('ussd.network_mapping');
        $request->Network = isset($network_mapping[$params['network']]) ? $network_mapping[$params['network']] : "";
        return $request;
    }
}
