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

namespace ZingyBits\CitizenCore\Gateway\Response;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Model\Order\Payment;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use ZingyBits\CitizenCore\Gateway\Config\Config as GatewayConfig;
use ZingyBits\CitizenCore\Model\Order\OrderStatus;

/**
 * class TransactionStatusHandler
 */
class TransactionStatusHandler implements HandlerInterface
{
    /**
     * @var OrderPaymentRepositoryInterface
     */
    private $orderPaymentRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderPaymentRepositoryInterface $orderPaymentRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderPaymentRepositoryInterface $orderPaymentRepository,
        OrderStatus $orderStatus
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->orderStatus = $orderStatus;
    }

    /**
     * Handles response
     *
     * @param array $handlingSubject
     * @param array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response): void
    {
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        $payment = $paymentDO->getPayment();
        $order = $payment->getOrder();

        $paymentStatusGateway = $response[GatewayConfig::GATEWAY_RESPONSE_TRANSACTION_STATUS] ?? 'null';
        if (! $paymentStatusGateway) {
            return;
        }

        $paymentStatusOrder = $order->getStatus();
        $order->setState(Order::STATE_PROCESSING);

        if ($paymentStatusGateway && ($paymentStatusGateway !== $paymentStatusOrder)) {
            // return true if status change has been successful
            $isChanged = $this->orderStatus->changeStatus($order, $paymentStatusGateway);
        }
    }
}
