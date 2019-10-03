<?php

namespace Rich\LoginHistory\Plugin;

use Rich\LoginHistory\Api\Data\LoginRecordInterfaceFactory;
use Rich\LoginHistory\Api\Data\LoginRecordInterface;
use Rich\LoginHistory\Api\LoginRecordRepositoryInterface;
use \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\HTTP\Header as HttpHeader;

class SaveLoginHistory
{
    private $httpHeader;

    private $loginRecordFactory;

    private $loginRecordRepository;

    private $remoteAddress;

    public function __construct(
        HttpHeader $httpHeader,
        LoginRecordInterfaceFactory $loginRecordFactory,
        LoginRecordRepositoryInterface $loginRecordRepository,
        RemoteAddress $remoteAddress
    )
    {
        $this->httpHeader = $httpHeader;
        $this->loginRecordFactory = $loginRecordFactory;
        $this->loginRecordRepository = $loginRecordRepository;
        $this->remoteAddress = $remoteAddress;
    }


    public function afterSetCustomerDataAsLoggedIn(\Magento\Customer\Model\Session $subject, $result)
    {
        /** @var LoginRecordInterface $loginRecord */
        $loginRecord = $this->loginRecordFactory->create();

        $loginRecord->setIpAddress($this->remoteAddress->getRemoteAddress());
        $loginRecord->setUserAgent($this->httpHeader->getHttpUserAgent());
        $loginRecord->setCustomerId($subject->getCustomerId());

        $this->loginRecordRepository->save($loginRecord);

        return $result;
    }
}
