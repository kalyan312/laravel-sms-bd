<?php

namespace Khbd\LaravelSmsBD\Gateways;

use Khbd\LaravelSmsBD\Interfaces\SMSInterface;
use Khbd\LaravelSmsBD\SDK\BangladeshSMS\BangladeshSMS as SMSGateway;
use Illuminate\Http\Request;

class BangladeshSMS implements SMSInterface
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
     * @param $recipient
     * @param $message
     * @param null $params
     *
     * @return object
     */
    public function send(string $recipient, string $message, $params = null)
    {

        $AT = new SMSGateway($this->settings->base_url, $this->settings->username, $this->settings->api_key, $this->settings->from);
        $this->smsResponse = $AT->send($recipient, $message);
        $msg = strtolower($this->smsResponse);
        $status = false;
        $messageID = null;
        if(strpos($msg, 'sms submitted:') !== false){
            $status = true;
            $id = explode('-', $msg);
            if(isset($id[1])){
                $messageID = trim($id[1]);
            }
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
        $AT = new SMSGateway($this->settings->base_url, $this->settings->username, $this->settings->api_key, $this->settings->from);
        return $AT->balance();
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

    public function fixNumber($number){
       $validCheckPattern = "/^(?:\+88|01)?(?:\d{11}|\d{13})$/";
       if(preg_match($validCheckPattern, $number)){
           if(preg_match('/^(?:01)\d+$/', $number)){
               $number = '+88' . $number;
           }

           return $number;
       }

       return false;
    }
}
