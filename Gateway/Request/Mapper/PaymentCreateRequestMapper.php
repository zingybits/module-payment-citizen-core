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

namespace ZingyBits\CitizenCore\Gateway\Request\Mapper;

/**
 * Class PaymentStatusRequestMapper
 * @package ZingyBits\CitizenCore\Gateway\Request\Mapper
 */
class PaymentCreateRequestMapper extends AbstractRequestMapper
{
    public const URI_SUFFIX = 'api/payments/payment/{id}';
    public const TRANSFER_METHOD = 'POST';
    public const DYNAMIC_CONFIG_PARAMS_MAPPING = [
        'id' => 'payment_id'
    ];
    public const RESPONSE_PARAMS = [
        'id',
        'order_number',
        'state',
        'sub_state',
        'amount',
        'currency',
        'payment_instrument',
        'payer',
        'target',
        'additional_params',
        'lang',
        'recurrence',
        'preauthorization',
        'eet_code',
        'gw_url'
    ];
}
