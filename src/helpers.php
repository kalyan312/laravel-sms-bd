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
