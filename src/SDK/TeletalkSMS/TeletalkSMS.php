<?php

namespace Khbd\LaravelSmsBD\SDK\TeletalkSMS;

use Ixudra\Curl\Facades\Curl;

class TeletalkSMS
{
    private $apiUrl;
    private $userName;
    private $password;
    private $acode;
    private $masking;

    public function __construct($apiUrl, $userName, $password, $acode, $masking)
    {
        $this->apiUrl = $apiUrl;
        $this->userName = $userName;
        $this->password = $password;
        $this->acode = $acode;
        $this->masking = $masking;
    }

    public function getSMSEndpoint(){
        return $this->apiUrl . '/api/sendSMS';
    }
    public function getBalanceEndpoint(){
        return false;
    }


    public function send($to, $message = null, $is_unicode = false){
        if(is_string($to)){
            $to = [
                $to
            ];
        }
        if(!is_array($to)){
            throw new \Exception('Teletalk SMS Faild. Error - Invalid phone number type. Number should string or array.');
        }
        $data = [];
        $data['auth'] = [
            'username' => $this->userName,
            'password' => $this->password,
            'acode' => $this->acode
        ];
        $data['smsInfo'] = [
                'message' => $message,
                'is_unicode' =>$is_unicode ?? false,
                'masking' => $this->masking,
                'msisdn' => $to
            ];
          try {
              $response = Curl::to($this->getSMSEndpoint())
                  ->asJsonRequest()
                  ->returnResponseArray()
                  ->withData($data)
                  ->post();

              return $response;
          } catch (\Exception $exception){
                throw new \Exception('Teletalk SMS Faild. Error - ' . $exception->getMessage(), $exception->getCode());
          }

    }

    public function balance()
    {
        $response = Curl::to($this->getBalanceEndpoint())
            ->get();

        return $response;
    }
}
