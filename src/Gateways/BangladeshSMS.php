<?php

namespace Khbd\LaravelSmsBD\Gateways;

use Khbd\LaravelSmsBD\Interfaces\SMSInterface;
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
        $AT = new SMSGateway($this->settings->username, $this->settings->api_key);
        $sms = $AT->sms();

        $result = $sms->send([
            'to'      => $recipient,
            'message' => $message,
            'from'    => $this->settings->from,
        ]);

        // message sending successful
        if ($result['status'] == 'success') {
            $data = $result['data']->SMSMessageData->Recipients[0];

            $this->is_success = $data->status == 'Success'; // define what determines success from the response
            $this->message_id = $data->messageId; // reference the message id here. auto generate if not available
            $arr = [
                'is_success' => $data->status == 'Success',
                'message_id' => $data->messageId,
                'number'     => $data->number,
                'cost'       => $data->cost,
                'status'     => $data->status,
                'statusCode' => $data->statusCode,
            ];

            $this->data = (object) $arr;

            return $this;
        } else {
            // sms sending failed  // problem with gateway
            $arr = $result;
            $this->data = (object) $arr;

            return $this;
        }
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
    public function getBalance(): float
    {
        $AT = new SMSGateway($this->settings->username, $this->settings->api_key);
        $application = $AT->application();
        $balance = $application->fetchApplicationData()['data']->UserData->balance;
        $replacements = ['/\bKES\b/', '/\bUGX\b/', '/\TSH\b/'];

        return (float) str_replace(' ', '', preg_replace($replacements, '', $balance));
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
