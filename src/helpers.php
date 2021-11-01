<?php

if (!function_exists('sms')) {

    /**
     * @throws Exception
     *
     * @return \CraftedSystems\LaravelSMS\SMS
     */
    function sms()
    {
        return new \Khbd\LaravelSmsBD\SMS();
    }
}

if (!function_exists('getCurrentDateTime')) {

    function getCurrentDateTime()
    {
        if(class_exists(\Carbon\Carbon::class)){
            return \Carbon\Carbon::now();
        }
        return date('Y-m-d H:i:s');
    }
}
