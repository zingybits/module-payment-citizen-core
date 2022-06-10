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

namespace ZingyBits\CitizenCore\Gateway\Config\Enum\Response;

/**
 * Class PaymentStatus
 *
 * @package ZingyBits\CitizenCore\Gateway\Config\Enum\Response
 */
class PaymentStatus
{
    const CREATED = 'PENDING_USER_AUTHORISATION';
    const INITIATED = 'INITIATED';
    const ACCEPTED = 'ACCEPTED';
    const CANCELED = 'CANCELLED';
    const REJECTED = 'REJECTED_BY_ASPSP';
    const FAILED = 'FAILED';
    const ERROR = 'ERROR';
}
