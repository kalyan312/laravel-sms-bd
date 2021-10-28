<?php

namespace Khbd\LaravelSmsBD\Gateways;

use Illuminate\Support\Facades\Log;
use Khbd\LaravelSmsBD\Interfaces\SMSInterface;
use Khbd\LaravelSmsBD\SDK\TeletalkSMS\TeletalkSMS as SMSGateway;
use Illuminate\Http\Request;

class TeletalkSMS implements SMSInterface
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var bool
     */
    protected $is_success;

    /**
     * @var mixed
     */
    protected $message_id;

    /**
     * @var object
     */
    public $data;

    /**
     * @var object | array
     */
    public $smsResponse;

    /**
     * @param $settings
     *
     * @throws \Exception
     */
    public function __construct($settings)
    {
        // initiate settings (username, api_key, etc)

        $this->settings = (object) $settings;
    }

    /**
     * @param string | array $recipient
     * @param $message
     * @param null $params
     *
     * @return object
     */
    public function send($recipient, string $message, $is_unicode = false)
    {
        $AT = new SMSGateway($this->settings->base_url, $this->settings->username, $this->settings->password, $this->settings->acode, $this->settings->masking);
        $this->smsResponse = $AT->send($recipient, $message, $is_unicode);
        $content  = json_decode($this->smsResponse['content'], true);
        $status = false;
        $messageID = null;
        Log::debug("message", $this->smsResponse);
        Log::info("message", $this->smsResponse);

        if(isset($content) && empty($content['error_code'])){
            // success
            $status = true;
            $smsRawId = $content['smsInfo'];
            $messageID = '';
            if(is_array($smsRawId) && count($smsRawId) == 1){
                $messageID = $smsRawId[0]['smsID'];
            }else{
                $messageID = json_encode($smsRawId);
            }
        }else if (isset($content['error_code']) && $content['error_code'] < 0){
            $messageID =  "Error code - " . $content['error_code'] . " and Message - " . $content['description'];
        } else {
            $messageID = json_encode($content);
        }

        $this->is_success = $status;      // define what determines success from the response
        $this->message_id = $messageID;   // reference the message id here. auto generate if not available

        return $this;
    }

    /**
     * initialize the is_success parameter.
     *
     * @return bool
     */
    public function is_successful(): bool
    {
        return $this->is_success;
    }

    /**
     * initialize the getResponseBody parameter.
     *
     * @return bool
     */
    public function getResponseBody()
    {
        return $this->smsResponse;
    }

    /**
     * assign the message ID as received on the response,auto generate if not available.
     *
     * @return mixed
     */
    public function getMessageID()
    {
        return $this->message_id;
    }

    /**
     * auto generate if not available.
     */
    public function getBalance()
    {
       return 'API provider dont provide balance status.';
    }


    /**
     * @param Request $request
     *
     * @return object
     */
    public function getDeliveryReports(Request $request)
    {
        $status = $request->status;

        if ($status == 'Failed' || $status == 'Rejected') {
            $fs = $request->failureReason;
        } else {
            $fs = $status;
        }

        $data = [
            'status'       => $fs,
            'message_id'   => $request->id,
            'phone_number' => '',
        ];

        return (object) $data;
    }
}
