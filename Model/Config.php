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

use ZingyBits\CitizenCore\Api\ConfigInterface as CitizenConfigInterface;
use Magento\Payment\Gateway\ConfigInterface as PaymentGatewayConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package ZingyBits\CitizenCore\Model
 */
class Config implements CitizenConfigInterface
{
    public const IS_ACTIVE = 'active';
    public const CLIENT_EMAIL = 'client_email';
    public const PUBLIC_KEY = 'public_key';
    public const PRIVATE_KEY = 'private_key';
    public const MERCHANT_EMAIL = 'merchant_email';
    public const CMS_FAILURE_PAGE = 'advanced/cms_payment_failure';
    public const CMS_SUCCESS_PAGE = 'advanced/cms_payment_success';
    public const REGISTRATION_URL = 'registration_url';
    public const IS_PRODUCTION = 'is_production';
    public const URL_GATEWAY_API_PROD = 'url_gateway_api_prod';
    public const URL_GATEWAY_API_TEST = 'url_gateway_api_test';
    public const URL_PROD_SDK = 'url_sdk_prod';
    public const URL_TEST_SDK = 'url_sdk_test';
    public const CANCEL_ORDER_ON_CANCELLED_PAYMENT = 'cancel_order_on_cancelled_payment';
    public const SEND_MAIL_AFTER_COMPLETE = 'send_mail_after_complete';

    public const CHECKOUT_PHRASE_TITLE = 'phrase_title';
    public const CHECKOUT_DESKTOP_DESC = 'desktop_desc';
    public const CHECKOUT_DESKTOP_BUTTON_TEXT = 'desktop_button_text';
    public const CHECKOUT_MOBILE_BUTTON_TEXT = 'mobile_button_text';

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

    public function isProduction(): string
    {
        return (string) $this->config->getValue(self::IS_PRODUCTION);
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
        $urlGatewayApiProduction = (string) $this->config->getValue(self::URL_GATEWAY_API_PROD);
        $urlGatewayApiTest = (string) $this->config->getValue(self::URL_GATEWAY_API_TEST);

        return $this->isProduction()
            ? $urlGatewayApiProduction
            : $urlGatewayApiTest;
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

    public function getRegistrationUrl(): string
    {
        return (string) $this->config->getValue(self::REGISTRATION_URL);
    }

    public function getCheckoutPhraseTitle(): string
    {
        return (string) $this->config->getValue(self::CHECKOUT_PHRASE_TITLE);
    }

    public function getCheckoutDesktopDesc(): string
    {
        return (string) $this->config->getValue(self::CHECKOUT_DESKTOP_DESC);
    }

    public function getCheckoutDesktopButtonText(): string
    {
        return (string) $this->config->getValue(self::CHECKOUT_DESKTOP_BUTTON_TEXT);
    }

    public function getCheckoutMobileButtonText(): string
    {
        return (string) $this->config->getValue(self::CHECKOUT_MOBILE_BUTTON_TEXT);
    }

    public function getUrlTestSdk(): string
    {
        return (string) $this->config->getValue(self::URL_TEST_SDK);
    }

    public function getUrlProdSdk(): string
    {
        return (string) $this->config->getValue(self::URL_PROD_SDK);
    }

    public function getCancelOrderOnCancelledPayment(): bool
    {
        return (bool) $this->config->getValue(self::CANCEL_ORDER_ON_CANCELLED_PAYMENT);
    }
    public function canSendMailAfterComplete(): string
    {
        return (string) $this->config->getValue(self::SEND_MAIL_AFTER_COMPLETE);
    }

}
