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
 * Class PayInTransactionStatusRequestMapper
 * @package ZingyBits\CitizenCore\Gateway\Request\Mapper
 */
class PayInTransactionStatusRequestMapper extends AbstractRequestMapper
{
    public const URI_SUFFIX = 'payins/{transactionId}';
    public const TRANSFER_METHOD = 'GET';
    public const DYNAMIC_CONFIG_PARAMS_MAPPING = [
        'transactionId' => 'transactionId'
    ];
}
