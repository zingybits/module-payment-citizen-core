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

namespace ZingyBits\CitizenCore\Setup\Patch\Data;

use ZingyBits\CitizenCore\Api\ConfigInterface as Config;
use ZingyBits\CitizenCore\Model\Order\AddNewOrderStatus;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Model\Order;

/**
 * Class AddOrderStatuses
 *
 * @package ZingyBits\CitizenCore\Setup\Patch\Data
 */
class AddOrderStatuses implements DataPatchInterface
{
    private const NEW_ORDER_STATUSES = [
        Config::CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION => [
            'status_code' => Config::CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION,
            'status_label' => Config::CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION_COMMENT,
            'assign_to_state' => Order::STATE_PROCESSING,
            'is_default' => false,
            'visible_on_front' => false
        ],
        Config::CITIZEN_ORDER_STATUS_ACCEPTED => [
            'status_code' => Config::CITIZEN_ORDER_STATUS_ACCEPTED,
            'status_label' => Config::CITIZEN_ORDER_STATUS_ACCEPTED_COMMENT,
            'assign_to_state' => Order::STATE_PROCESSING,
            'is_default' => false,
            'visible_on_front' => false
        ],
        Config::CITIZEN_ORDER_STATUS_INITIATED => [
            'status_code' => Config::CITIZEN_ORDER_STATUS_INITIATED,
            'status_label' => Config::CITIZEN_ORDER_STATUS_INITIATED_COMMENT,
            'assign_to_state' => Order::STATE_PROCESSING,
            'is_default' => false,
            'visible_on_front' => false
        ],
        Config::CITIZEN_ORDER_STATUS_CANCELLED => [
            'status_code' => Config::CITIZEN_ORDER_STATUS_CANCELLED,
            'status_label' => Config::CITIZEN_ORDER_STATUS_CANCELLED_COMMENT,
            'assign_to_state' => Order::STATE_CANCELED,
            'is_default' => false,
            'visible_on_front' => false
        ],
        Config::CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP => [
            'status_code' => Config::CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP,
            'status_label' => Config::CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP_COMMENT,
            'assign_to_state' => Order::STATE_CANCELED,
            'is_default' => false,
            'visible_on_front' => false
        ],
        Config::CITIZEN_ORDER_STATUS_FAILED => [
            'status_code' => Config::CITIZEN_ORDER_STATUS_FAILED,
            'status_label' => Config::CITIZEN_ORDER_STATUS_FAILED_COMMENT,
            'assign_to_state' => Order::STATE_CANCELED,
            'is_default' => false,
            'visible_on_front' => false
        ],
    ];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var AddNewOrderStatus
     */
    private $addNewOrderStatus;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AddNewOrderStatus $addNewOrderStatus
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AddNewOrderStatus $addNewOrderStatus
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->addNewOrderStatus = $addNewOrderStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        foreach (self::NEW_ORDER_STATUSES as $statusData) {
            $this->addNewOrderStatus->execute($statusData);
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
