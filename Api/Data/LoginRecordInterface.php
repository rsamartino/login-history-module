<?php

namespace Rich\LoginHistory\Api\Data;

interface LoginRecordInterface 
{
    const IP_ADDRESS = 'ip_address';
    const USER_AGENT = 'user_agent';
    const LOGIN_DATE = 'login_date';

    public function getId();

    public function getIpAddress();

    public function getUserAgent();

    public function getLoginDate();

    public function setIpAddress($ipAddress);

    public function setUserAgent($userAgent);

//    public function setLoginDate();
}
