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

namespace ZingyBits\CitizenCore\Model\Order;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;
use Psr\Log\LoggerInterface;

class AddNewOrderStatus
{
    /**
     * Status Factory
     *
     * @var StatusFactory
     */
    protected $statusFactory;

    /**
     * Status Resource Factory
     *
     * @var StatusResourceFactory
     */
    protected $statusResourceFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AddNewOrderStatus constructor.
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     * @param LoggerInterface $logger
     */
    public function __construct (
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory,
        LoggerInterface $logger
    ) {
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $customOrderStatusData)
    {
        $statusResource = $this->statusResourceFactory->create();
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => $customOrderStatusData['status_code'],
            'label' => $customOrderStatusData['status_label']
        ]);

        try {
            $statusResource->save($status);
            if ($customOrderStatusData['assign_to_state'] !== null) {
                $status->assignState(
                    $customOrderStatusData['assign_to_state'],
                    $customOrderStatusData['is_default'],
                    $customOrderStatusData['visible_on_front']
                );
            }
            $this->logger->info(
                'Created custom order status "' . $customOrderStatusData['status_label'] . '" with code "' . $customOrderStatusData['status_code'] . '"'
            );
        } catch (AlreadyExistsException $e) {
            $this->logger->warning($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        }
    }
}
