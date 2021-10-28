<?php

namespace Khbd\LaravelSmsBD;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Khbd\LaravelSmsBD\Models\SmsHistory;



class SMS
{
    /**
     * SMS Configuration.
     *
     * @var null|object
     */
    protected $config = null;

    /**
     * Sms Gateway Settings.
     *
     * @var null|object
     */
    protected $settings = null;

    /**
     * Sms Gateway Name.
     *
     * @var null|string
     */
    protected $gateway = null;

    /**
     * @var object
     */
    protected $object = null;

    /**
     * @var object
     */
    protected $smsRecord;


    /**
     * SMS constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->config = config('sms');
        $this->gateway = $this->config['default'];
        $this->mapGateway();
    }

    /**
     * Change the gateway on the fly.
     *
     * @param $gateway
     *
     * @return $this
     */
    public function gateway($gateway)
    {
        $this->gateway = $gateway;
        $this->mapGateway();

        return $this;
    }

    /**
     *map the gateway that will be used to send.
     */
    private function mapGateway()
    {
        $this->settings = $this->config['gateways'][$this->gateway];
        $class = $this->config['map'][$this->gateway];
        $this->object = new $class($this->settings);
    }

    /**
     * @param $recipient
     * @param $message
     * @param null $params
     *
     * @return mixed
     */
    public function send($recipient, $message, $params = null)
    {
        if($this->config['sms_activate'] == false) {
            return false;
        }
        if($this->config['sms_log']) {
            $this->beforeSend($recipient, $message, $params = null);
        }
        if(method_exists($this->object, 'fixNumber') && !$recipient = $this->object->fixNumber($recipient)){
            return false;
        }
        $object = $this->object->send($recipient, $message, $params);
        if($this->config['sms_log']) {
            $this->afterSend();
        }

        return $object;
    }

    /**
     * define when the a message is successfully sent.
     *
     * @return bool
     */
    public function is_successful()
    {
        return $this->object->is_successful();
    }

    /**
     * return api response getResponseBody
     *
     * @return object | array
     */
    public function getResponseBody()
    {
        return $this->object->getResponseBody();
    }

    /**
     * the message ID as received on the response.
     *
     * @return mixed
     */
    public function getMessageID()
    {
        return $this->object->getMessageID();
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->object->getBalance();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getDeliveryReports(Request $request)
    {
        return $this->object->getDeliveryReports($request);
    }

    private function beforeSend($recipient, $message, $params = null){
        try{
            $history = new SmsHistory();
            $history->mobile_number = is_array($recipient) ? json_encode($recipient) : $recipient;
            $history->message = is_array($message) ? json_encode($message) : $message;
            $history->gateway = $this->gateway;
            $history->created_at = now();
            $history->save();
            $this->smsRecord = $history;
        } catch (\Exception $exception){
            Log::debug("Faild to save sms message. " . $exception->getMessage());
        }
    }
    private function afterSend(){
        try{
            $status = 2;
            if($this->is_successful()){
                $status = 1;
            }

            if(is_object($this->smsRecord)){
                $this->smsRecord->status = $status;
                $this->smsRecord->sms_submitted_id = is_array($this->getMessageID()) ? json_encode($this->getMessageID()) : $this->getMessageID();
                $this->smsRecord->api_response = json_encode($this->getResponseBody());
                $this->smsRecord->save();
            }

        }catch (\Exception $exception){
            $exception->getMessage();
        }
    }
}
