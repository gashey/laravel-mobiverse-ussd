<?php

namespace Gashey\MobiverseUssd;

use Illuminate\Support\Facades\Cache;
use Gashey\MobiverseUssd\Activities\HijackSessionActivity;
use Gashey\MobiverseUssd\Activities\HomeActivity;
use Gashey\MobiverseUssd\Activities\ReleaseActivity;
use Gashey\MobiverseUssd\Activities\TimeOutActivity;
use Gashey\MobiverseUssd\Lib\UssdActivity;
use Gashey\MobiverseUssd\Lib\UssdRequest;
use Gashey\MobiverseUssd\Lib\UssdResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Exception;

class MainController extends Controller
{

    /**
     * Next ussd response to be sent
     * @var UssdResponse
     */
    protected $response;

    /**
     * Current ussd request
     * @var UssdRequest
     */
    protected $request;

    /**
     * This is the key by which we save a user's session
     *
     * @var string
     */
    protected $sessionId;

    /**
     * Loaded cache driver
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * User session state
     * @var mixed|null
     */
    protected $session = [];

    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->cache = Cache::store(config('ussd.session_cache_driver', 'file'));

        // Let's instantiate our next response
        $this->response = new UssdResponse();

        // Set request
        if (count($request->all()) > 0) {
            $this->request = UssdRequest::ToUssdRequest($request->all());
            $this->sessionId = $this->request->SessionId;
        }

        // Check if cache is set
        $this->session = $this->retrieveSession();
    }

    /**
     * Main application entry point.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {

            switch ($this->request->UssdServiceOp) {

                case UssdRequest::INITIATION:
                    $activity = $this->processInitiationRequest();
                    break;

                case UssdRequest::RELEASE:
                    $activity = $this->processReleaseRequest();
                    break;

                case UssdRequest::TIMEOUT:
                    $activity = $this->processTimeoutRequest();
                    break;

                case UssdRequest::RESPONSE:
                    $activity = $this->processResponseRequest();
                    break;

                case UssdRequest::HIJACKSESSION:
                    $activity = $this->processHijackSessionRequest();
                    break;

                default:
                    throw new Exception("Unknown request");
                    break;
            }

            // Session might have changed during activity:
            $this->updateSession(array_merge($activity->getSession(), ['activity' => get_class($activity)]));

            // Get updated response from activity
            $this->response = $activity->getResponse();

            return $this->sendResponse();
        } catch (Exception $e) {

            // Let's log the error first
            \Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());

            // ... then we inform the user
            $this->response->Type = UssdResponse::RELEASE;
            $this->response->Message = env('APP_DEBUG', false) ? $e->getMessage() : "Oops! Something isn't right. Please try again later.";
            return $this->sendResponse();
        }
    }

    /**
     * Retrieve active user session from cache.
     *
     * @return mixed|null
     */
    protected function retrieveSession()
    {
        if ($this->cache->has($this->sessionId)) {
            return $this->cache->get($this->sessionId);
        }

        return [];
    }

    /**
     * Update current user session with $data
     *
     * @param array $data
     * @return void
     */
    protected function updateSession($data = [])
    {

        $oldSessionData = $this->retrieveSession();

        $updatedData = !empty($oldSessionData) ? array_merge($oldSessionData, $data) : $data;

        $expiresAt = Carbon::now()->addMinutes(config('ussd.session_lifetime_minutes', 5));

        $this->cache->put($this->sessionId, $updatedData, $expiresAt);

        $this->session = $updatedData;

        if (env('APP_DEBUG', false)) {
            logger("---------- USSD session -------");
            logger($this->session);
        }
    }

    /**
     * Send final response to user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendResponse()
    {
        $this->response->SessionId = $this->sessionId;
        return response()->json($this->response->asMobiverseResponse());
    }

    /**
     * Initiation request
     * @return UssdActivity|void
     */
    private function processInitiationRequest()
    {
        $className = config('ussd.home', HomeActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

    /**
     * Response request
     * @return type
     */
    private function processResponseRequest()
    {
        $className = $this->session['activity'];
        $activity = new $className($this->request, $this->response, $this->session);

        // Handle back action
        if (trim($this->request->Message) != config('ussd.go_back_key', '#')) {
            $className = $activity->next();
            $activity = new $className($this->request, $this->response, $activity->getSession());
        }

        return $activity->run();
    }

    private function processReleaseRequest()
    {
        $className = config('ussd.release', ReleaseActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

    private function processTimeoutRequest()
    {
        $className = config('ussd.timeout', TimeOutActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

    private function processHijackSessionRequest()
    {
        $className = config('ussd.hijack_session', HijackSessionActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }
}
