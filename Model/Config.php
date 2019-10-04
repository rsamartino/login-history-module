<?php

namespace Rich\LoginHistory\Model;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const XML_PATH_GEOIP_API_KEY = 'login_history/geoip_api/api_key';

    /**
     * @var EncryptorInterface
     */
    private $encryptor;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param EncryptorInterface $encryptor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        EncryptorInterface $encryptor,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->encryptor = $encryptor;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getGeoIpApiKey()
    {
        return $this->encryptor->decrypt($this->scopeConfig->getValue(self::XML_PATH_GEOIP_API_KEY));
    }

}
