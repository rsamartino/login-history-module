<?php

namespace Rich\LoginHistory\Api\Data;

interface LoginRecordInterface 
{
    const IP_ADDRESS = 'ip_address';
    const USER_AGENT = 'user_agent';
    const LOGIN_DATE = 'login_date';
    const CUSTOMER_ID = 'customer_id';
    const IP_LOCATION = 'ip_location';

    public function getId();

    public function getIpAddress();

    public function getUserAgent();

    public function getLoginDate();

    public function getCustomerId();

    public function getIpLocation();

    public function setIpAddress($ipAddress);

    public function setUserAgent($userAgent);

    public function setCustomerId($customerId);

    public function setIpLocation($ipLocation);
}
