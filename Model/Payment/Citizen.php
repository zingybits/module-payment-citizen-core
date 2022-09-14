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

namespace ZingyBits\CitizenCore\Model\Payment;

use Magento\Quote\Api\Data\CartInterface;

class Citizen extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * @var string
     */
    protected $_code = "citizen";

    /**
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * Return quote is it available
     *
     * @param CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(
        CartInterface $quote = null
    ): bool {
        return parent::isAvailable($quote);
    }
}
