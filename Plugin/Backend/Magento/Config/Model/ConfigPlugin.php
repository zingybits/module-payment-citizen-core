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

namespace ZingyBits\CitizenCore\Plugin\Backend\Magento\Config\Model;

use Magento\Payment\Gateway\Command\CommandPoolInterface;
use ZingyBits\CitizenCore\Model\Config as ConfigGateway;

class ConfigPlugin
{
    public const PAY_IN_SUCCESS_REDIRECT = '/merchant/pay-in-success-redirect';
    public const PAY_IN_FAILURE_REDIRECT = '/merchant/pay-in-failure-redirect';

    /**
     * @var ConfigGateway
     */
    private $configGateway;

    /**
     * @var CommandPoolInterface
     */
    private $commandPool;

    /**
     * @param ConfigGateway $configGateway
     * @param CommandPoolInterface $commandPool
     */
    public function __construct(
        ConfigGateway        $configGateway,
        CommandPoolInterface $commandPool
    ) {
        $this->configGateway = $configGateway;
        $this->commandPool = $commandPool;
    }

    /**
     * Sending requests before saving the configuration
     *
     * @param \Magento\Config\Model\Config $subject
     * @return null
     */
    public function beforeSave(\Magento\Config\Model\Config $subject)
    {
        $groups = $subject->getGroups();

        if (empty($groups)) {
            return null;
        }

        $oldPrivateKey = $this->configGateway->getPrivateKey();
        $newPrivateKey = $groups['citizen']["fields"]["private_key"]["value"] ?? '';

        if (!$newPrivateKey || ($oldPrivateKey == $newPrivateKey)) {
            return null;
        }

        $allUri = [
            self::PAY_IN_SUCCESS_REDIRECT,
            self::PAY_IN_FAILURE_REDIRECT
        ];

        $response = [];
        foreach ($allUri as $uri) {
            $response[] = $this->commandPool->get('account-add-redirect')->execute(
                ['sendUrl' => $this->configGateway->getGatewayApiUrl() . $uri]
            );
        }

        return null;
    }
}
