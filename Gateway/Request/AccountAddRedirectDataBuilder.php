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
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\Store\Model\StoreManagerInterface;

class AccountAddRedirectDataBuilder implements BuilderInterface
{
    const CONTROLLER_PATH_CALLBACK_RESPONSE = 'citizen/callback/response';

    /**
     * @var StoreManagerInterface StoreManager
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        $buildSubject['url'] = $baseUrl . static::CONTROLLER_PATH_CALLBACK_RESPONSE;

        return $buildSubject;
    }
}
