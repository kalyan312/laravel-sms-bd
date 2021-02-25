<?php

namespace Khbd\LaravelSmsBD\SDK\BangladeshSMS;

use Ixudra\Curl\Facades\Curl;

class BangladeshSMS
{
    private $apiUrl;
    private $userName;
    private $apiKey;
    private $from;

    public function __construct($apiUrl, $userName, $apiKey, $from)
    {
        $this->apiUrl = $apiUrl;
        $this->userName = $userName;
        $this->apiKey = $apiKey;
        $this->from = $from;
    }

    public function getSMSEndpoint(){
        return $this->apiUrl . '/smsapi';
    }
    public function getBalanceEndpoint(){
        return $this->apiUrl . '/miscapi/'.$this->apiKey.'/getBalance';
    }
    public function getDeliveryReportsEndpoint($all = true){
        if($all){
            return $this->apiUrl . '/miscapi/'.$this->apiKey.'/getDLR/getAll';
        }else{
            return $this->apiUrl . '/miscapi/'.$this->apiKey.'/getDLR/';
        }
    }


    public function send($to, $message = null){
        $data = [
            "api_key" => $this->apiKey,
            "type" => "text",
            "contacts" => $to,
            "senderid" => $this->from,
            "msg" => $message,
          ];
          $response = Curl::to($this->getSMSEndpoint())
                ->withData($data)
                ->post();

          return $response;
    }

    public function balance()
    {
        $response = Curl::to($this->getBalanceEndpoint())
            ->get();

        return $response;
    }
}
