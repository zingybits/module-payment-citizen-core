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

namespace ZingyBits\CitizenCore\Gateway\Request;

use Magento\Checkout\Model\Session;
use ZingyBits\CitizenCore\Model\Config;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class PaymentInitDataBuilder implements BuilderInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var QuoteIdToMaskedQuoteIdInterface
     */
    private $quoteIdToMaskedQuoteId;

    public function __construct(
        Config $config,
        StoreManager    $storeManager,
        Session $checkoutSession,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId
    )
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        $paymentDO = SubjectReader::readPayment($buildSubject);
        $order = $paymentDO->getOrder();

        $merchantEmail = $this->config->getMerchantEmail();

        $quoteId = $buildSubject['order']->getQuoteId();
        $quoteIdMask = $this->quoteIdToMaskedQuoteId->execute($quoteId);
        $callbackUrl = 'callback/response?hash='.$quoteIdMask;

        // todo customerDevise parse $_SERVER useragent
        $customerDeviceOS = 'null';

        $body = [
            "customerEmailAddress" => $order->getBillingAddress()->getEmail(),
            "merchantEmailAddress" => $merchantEmail,
            "amount" => $order->getGrandTotalAmount(),
            "currency" => $order->getCurrencyCode(),
            "paymentGiro" => "FPS", // payment type (SEPA or FPS)
            "shortReference" => "yourRef",
            "customerDeviceOs" => $customerDeviceOS,
            "customerIpAddress" => $order->getRemoteIp(),
            "successRedirectUrl" => $callbackUrl.'&status=success',
            "failureRedirectUrl" => $callbackUrl.'&status=failure'
        ];
        return $body;

    }
}
