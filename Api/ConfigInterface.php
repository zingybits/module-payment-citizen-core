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

namespace ZingyBits\CitizenCore\Api;

/**
 * Interface ConfigInterface
 *
 * @package ZingyBits\CitizenCore\Api
 * @api
 */
interface ConfigInterface
{
    public const METHOD_CODE = 'citizen';

    public const CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION = 'citizen_payment_pending_user_authorisation';
    public const CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION_COMMENT = 'Citizen - The payment selecting';
    public const CITIZEN_ORDER_STATUS_ACCEPTED	= 'citizen_payment_accepted';
    public const CITIZEN_ORDER_STATUS_ACCEPTED_COMMENT = 'Citizen - The payment has been accepted';
    public const CITIZEN_ORDER_STATUS_INITIATED	= 'citizen_payment_payment_initiated';
    public const CITIZEN_ORDER_STATUS_INITIATED_COMMENT = 'Citizen - The payment has been initiated';
    public const CITIZEN_ORDER_STATUS_CANCELLED	= 'citizen_payment_cancelled';
    public const CITIZEN_ORDER_STATUS_CANCELLED_COMMENT = 'Citizen - The payment has been cancelled';
    public const CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP = 'citizen_payment_rejected_by_aspsp';
    public const CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP_COMMENT = 'Citizen - The payment has been rejected';
    public const CITIZEN_ORDER_STATUS_FAILED	= 'citizen_payment_payment_failed';
    public const CITIZEN_ORDER_STATUS_FAILED_COMMENT = 'Citizen - The payment has been failed';

    public const NEW_TRANSACTION_ORDER_MESSAGE = 'A new Citizen transaction has been created with the order';

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @return string
     */
    public function isProduction(): string;

    /**
     * @return string
     */
    public function getClientEmail(): string;

    /**
     * @return string
     */
    public function getPrivateKey(): string;

    /**
     * @return string
     */
    public function getPublicKey(): string;

    /**
     * @return string
     */
    public function getGatewayApiUrl(): string;

    /**
     * @return string
     */
    public function getMerchantEmail(): string;

    /**
     * @return string
     */
    public function getSuccessPage(): string;

    /**
     * @return string
     */
    public function getFailurePage(): string;

    /**
     * @return string
     */
    public function getRegistrationUrl(): string;

    /**
     * @return string
     */
    public function getCheckoutPhraseTitle(): string;

    /**
     * @return string
     */
    public function getCheckoutDesktopDesc(): string;

    /**
     * @return string
     */
    public function getCheckoutDesktopButtonText(): string;

    /**
     * @return string
     */
    public function getCheckoutMobileButtonText(): string;

    /**
     * @return string
     */
    public function getUrlProdSdk(): string;

    /**
     * @return string
     */
    public function getUrlTestSdk(): string;

    /**
     * @return string
     */
    public function getCancelOrderOnCancelledPayment(): bool;

    /**
     * @return string
     */
    public function canSendMailAfterComplete(): string;
}
