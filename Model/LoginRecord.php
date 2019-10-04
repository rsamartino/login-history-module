<?php

namespace Rich\LoginHistory\Model;

use Rich\LoginHistory\Api\Data\LoginRecordInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class LoginRecord extends AbstractModel implements LoginRecordInterface, IdentityInterface
{
    const CACHE_TAG = 'rich_loginhistory_loginrecord';

    protected function _construct()
    {
        $this->_init(\Rich\LoginHistory\Model\ResourceModel\LoginRecord::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getIpAddress()
    {
        return $this->getData(self::IP_ADDRESS);
    }

    public function getUserAgent()
    {
        return $this->getData(self::USER_AGENT);
    }

    public function getLoginDate()
    {
        return $this->getData(self::LOGIN_DATE);
    }

    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    public function getIpLocation()
    {
        return $this->getData(self::IP_LOCATION);
    }

    public function setIpAddress($ipAddress)
    {
        return $this->setData(self::IP_ADDRESS, $ipAddress);
    }

    public function setUserAgent($userAgent)
    {
        return $this->setData(self::USER_AGENT, $userAgent);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function setIpLocation($ipLocation)
    {
        return $this->setData(self::IP_LOCATION, $ipLocation);
    }
}
