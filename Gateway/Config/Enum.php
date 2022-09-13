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

namespace ZingyBits\CitizenCore\Gateway\Config;

use ZingyBits\CitizenCore\Gateway\Config\Enum\Response\PaymentStatus;
use ZingyBits\CitizenCore\Api\ConfigInterface as ApiConfig;

/**
 * Class Enum
 *
 * @package ZingyBits\CitizenCore\Gateway\Config
 */
class Enum
{
    public const TERMINAL_STATUSES = [
        PaymentStatus::CREATED,
        PaymentStatus::INITIATED,
        PaymentStatus::ACCEPTED,
        PaymentStatus::CANCELED,
        PaymentStatus::REJECTED,
        PaymentStatus::FAILED,
        PaymentStatus::ERROR
    ];

    public const TERMINAL_STATUSES_MAPPING = [
        PaymentStatus::CREATED => ApiConfig::CITIZEN_ORDER_STATUS_INITIATED,
        PaymentStatus::INITIATED => ApiConfig::CITIZEN_ORDER_STATUS_INITIATED,
        PaymentStatus::ACCEPTED => ApiConfig::CITIZEN_ORDER_STATUS_ACCEPTED,
        PaymentStatus::CANCELED => ApiConfig::CITIZEN_ORDER_STATUS_CANCELLED,
        PaymentStatus::REJECTED => ApiConfig::CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP,
        PaymentStatus::FAILED => ApiConfig::CITIZEN_ORDER_STATUS_FAILED,
        PaymentStatus::ERROR => ApiConfig::CITIZEN_ORDER_STATUS_CANCELLED
    ];

    public const TERMINAL_STATUSES_SUCCESS_PAGE = [
        PaymentStatus::ACCEPTED
    ];

    public const TERMINAL_STATUSES_FAILURE_PAGE = [
        PaymentStatus::CANCELED,
        PaymentStatus::REJECTED,
        PaymentStatus::FAILED,
        PaymentStatus::ERROR
    ];

    public const TERMINAL_STATUSES_ERROR = [
        PaymentStatus::ERROR
    ];
}
