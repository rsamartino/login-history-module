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
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Curl
     */
    private $curlClient;

    /**
     * @var HttpHeader
     */
    private $httpHeader;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LoginRecordInterfaceFactory
     */
    private $loginRecordFactory;

    /**
     * @var LoginRecordRepositoryInterface
     */
    private $loginRecordRepository;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * SaveLoginHistory constructor.
     * @param Config $config
     * @param Curl $curlClient
     * @param HttpHeader $httpHeader
     * @param LoggerInterface $logger
     * @param LoginRecordInterfaceFactory $loginRecordFactory
     * @param LoginRecordRepositoryInterface $loginRecordRepository
     * @param RemoteAddress $remoteAddress
     * @param Json $serializer
     */
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


    /**
     * @param \Magento\Customer\Model\Session $subject
     * @param $result
     * @return mixed
     */
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

    /**
     * @param $ipAddress
     * @return string|null
     */
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
