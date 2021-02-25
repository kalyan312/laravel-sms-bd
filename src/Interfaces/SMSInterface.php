<?php

namespace Khbd\LaravelSmsBD\Interfaces;

use Illuminate\Http\Request;

interface SMSInterface
{
    /**
     * Construct the class with the relevant settings.
     *
     * @param $settings object
     */
    public function __construct($settings);

    /**
     * @param $recipient
     * @param $message
     * @param null $params
     *
     * @return mixed
     */
    public function send(string $recipient, string $message, $params = null);

    /**
     * @return mixed
     */
    public function getBalance();

    /**
     * define when the a message is successfully sent.
     *
     * @return bool
     */
    public function is_successful();

    /**
     * the message ID as received on the response.
     *
     * @return mixed
     */
    public function getMessageID();

    /**
     *  the message API response
     *
     * @return object
     */
    public function getResponseBody();

    /**
     * @param Request $request
     *
     * @return object
     */
    public function getDeliveryReports(Request $request);
}
