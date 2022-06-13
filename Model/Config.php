<?php

/**
 * Citizen payment gateway by ZingyBits - Magento 2 extension
 *
 * NOTICE OF LICENSE
 *
 * Unauthorized copying of this file, via any medium, is strictly prohibited
 * Proprietary and confidential
 *
 * @category ZingyBits
 * @package ZingyBits_CitizenCore
 * @copyright Copyright (c) 2022 ZingyBits s.r.o.
 * @license http://www.zingybits.com/business-license
 * @author ZingyBits s.r.o. <support@zingybits.com>
 */

declare(strict_types=1);

namespace ZingyBits\CitizenCore\Model;

use ZingyBits\CitizenCore\Api\ConfigInterface;
use Magento\Payment\Gateway\Config\Config as GatewayConfig;
use Magento\Payment\Gateway\ConfigInterface as PaymentGatewayConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;


/**
 * Class Config
 *
 * @package ZingyBits\CitizenCore\Model
 */
class Config implements ConfigInterface
{
    public const IS_ACTIVE = 'active';
    public const CLIENT_EMAIL = 'client_email';
    public const PUBLIC_KEY = 'public_key';
    public const PRIVATE_KEY = 'private_key';
    public const GATEWAY_API_URL = 'gateway_api_url';
    public const MERCHANT_EMAIL = 'merchant_email';
    public const CMS_FAILURE_PAGE = 'advanced/cms_payment_failure';
    public const CMS_SUCCESS_PAGE = 'advanced/cms_payment_success';


    /**
     * @var PaymentGatewayConfigInterface
     */
    private $config;

    /**
     * Config constructor
     *
     * @param PaymentGatewayConfigInterface $config
     */
    public function __construct(
        PaymentGatewayConfigInterface $config
    ) {
        $this->config = $config;
    }

    public function getIsActive(): bool
    {
        return (bool) $this->config->getValue(self::IS_ACTIVE);
    }

    public function getClientEmail(): string
    {
        return (string) $this->config->getValue(self::CLIENT_EMAIL);
    }

    public function getPrivateKey(): string
    {
        return (string) $this->config->getValue(self::PRIVATE_KEY);
    }

    public function getPublicKey(): string
    {
        return (string) $this->config->getValue(self::PUBLIC_KEY);
    }

    public function getGatewayApiUrl(): string
    {
        return (string) $this->config->getValue(static::GATEWAY_API_URL);
    }

    public function getMerchantEmail(): string
    {
        return (string) $this->config->getValue(self::MERCHANT_EMAIL);
    }

    public function getSuccessPage(): string
    {
        return (string) $this->config->getValue(self::CMS_SUCCESS_PAGE);
    }

    public function getFailurePage(): string
    {
        return (string) $this->config->getValue(self::CMS_FAILURE_PAGE);
    }

}
