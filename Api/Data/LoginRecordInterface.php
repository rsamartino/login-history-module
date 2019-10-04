<?php

namespace Rich\LoginHistory\Api\Data;

interface LoginRecordInterface 
{
    const IP_ADDRESS = 'ip_address';
    const USER_AGENT = 'user_agent';
    const LOGIN_DATE = 'login_date';
    const CUSTOMER_ID = 'customer_id';
    const IP_LOCATION = 'ip_location';

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getIpAddress();

    /**
     * @return string
     */
    public function getUserAgent();

    /**
     * @return string
     */
    public function getLoginDate();

    /**
     * @return integer
     */
    public function getCustomerId();

    /**
     * @return string
     */
    public function getIpLocation();

    /**
     * @param string $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress);

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent);

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @param string $ipLocation
     * @return $this
     */
    public function setIpLocation($ipLocation);
}
