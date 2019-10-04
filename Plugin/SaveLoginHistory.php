<?php

namespace Rich\LoginHistory\Plugin;

use Rich\LoginHistory\Api\Data\LoginRecordInterfaceFactory;
use Rich\LoginHistory\Api\Data\LoginRecordInterface;
use Rich\LoginHistory\Api\LoginRecordRepositoryInterface;
use Rich\LoginHistory\Model\Config;
use \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\HTTP\Header as HttpHeader;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

class SaveLoginHistory
{
    private $config;

    private $curlClient;

    private $httpHeader;

    private $logger;

    private $loginRecordFactory;

    private $loginRecordRepository;

    private $remoteAddress;

    private $serializer;

    public function __construct(
        Config $config,
        Curl $curlClient,
        HttpHeader $httpHeader,
        LoggerInterface $logger,
        LoginRecordInterfaceFactory $loginRecordFactory,
        LoginRecordRepositoryInterface $loginRecordRepository,
        RemoteAddress $remoteAddress,
        Json $serializer
    )
    {
        $this->config = $config;
        $this->curlClient = $curlClient;
        $this->httpHeader = $httpHeader;
        $this->logger = $logger;
        $this->loginRecordFactory = $loginRecordFactory;
        $this->loginRecordRepository = $loginRecordRepository;
        $this->remoteAddress = $remoteAddress;
        $this->serializer = $serializer;
    }


    public function afterSetCustomerDataAsLoggedIn(\Magento\Customer\Model\Session $subject, $result)
    {
        /** @var LoginRecordInterface $loginRecord */
        $loginRecord = $this->loginRecordFactory->create();
        $ipAddress = $this->remoteAddress->getRemoteAddress();

        $loginRecord->setIpAddress($ipAddress);
        $loginRecord->setUserAgent($this->httpHeader->getHttpUserAgent());
        $loginRecord->setCustomerId($subject->getCustomerId());
        $loginRecord->setIpLocation($this->getIpLocation($ipAddress));

        $this->loginRecordRepository->save($loginRecord);

        return $result;
    }

    private function getIpLocation($ipAddress)
    {
        $accessKey = $this->config->getGeoIpApiKey();

        try {
            $this->curlClient->get('http://api.ipstack.com/'.$ipAddress.'?access_key='.$accessKey.''); //https only available with paid plan
            $response = $this->serializer->unserialize($this->curlClient->getBody());

            if (array_key_exists('error', $response)) {
                $this->logger->error($response['error']['info']);
                return null;
            } else {
                return $response['country_name'];
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}
