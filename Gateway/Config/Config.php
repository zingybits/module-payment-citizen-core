<?php

/**
 * CitizenCore payment gateway by ZingyBits - Magento 2 extension
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

namespace ZingyBits\CitizenCore\Gateway\Config;

/**
 * Class Config
 *
 * @package ZingyBits\CitizenCore\Gateway\Config
 */
class Config extends \Magento\Payment\Gateway\Config\Config
{
    public const CODE = 'citizen';

    public const GATEWAY_RESPONSE_TRANSACTION_ID = 'citizenTransactionId';
    public const GATEWAY_RESPONSE_TRANSACTION_STATUS = 'transactionStatus';
    public const GATEWAY_RESPONSE_TRANSACTION_MERCHANT_ID = 'merchantId';
    public const GATEWAY_RESPONSE_TRANSACTION_JOURNEY_TYPE = 'journeyType';

    public const INTERNAL_QUOTE_MASK_ID_CODE = 'hash';

    public const M2_PAYMENT_TXN_ID = 'cc_trans_id';

}
