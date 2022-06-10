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

use ZingyBits\CitizenCore\Gateway\Config\Config;
use ZingyBits\CitizenCore\Gateway\Config\Enum\Response\PaymentStatus;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * class PaymentStatusHandler
 */
class PaymentStatusHandler implements HandlerInterface
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
        OrderPaymentRepositoryInterface $orderPaymentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
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
        if (! isset($handlingSubject['payment_instance'])
            || (! $handlingSubject['payment_instance'] instanceof \Magento\Sales\Model\Order\Payment)
        ) {
            throw new \InvalidArgumentException('Invalid Payment instance data');
        }

        /** @var $payment \Magento\Sales\Model\Order\Payment */
        $payment = $handlingSubject['payment_instance'];
        /** @var $order \Magento\Sales\Model\Order */
        $order = $payment->getOrder();

        $paymentStatusGateway = $response[Config::GATEWAY_RESPONSE_TRANSACTION_STATUS];
        $paymentStatusOrder = $payment->getData(Config::M2_PAYMENT_STATE);

        // if the payment status has changed
        if ($paymentStatusGateway && ($paymentStatusGateway !== $paymentStatusOrder)) {
            // save the new payment status against the payment
            $payment->setData(Config::M2_PAYMENT_STATE, $paymentStatusGateway);
            $payment->setIsTransactionClosed(false);

            switch ($paymentStatusGateway) {
                case PaymentStatus::ACCEPTED:
                    $order->setStatus(Config::CITIZEN_ORDER_STATUS_COMPLETE);
                    $order->addCommentToStatusHistory(Config::CITIZEN_ORDER_STATUS_COMPLETE_COMMENT);
                    $payment->setIsTransactionClosed(true);
                    break;
                case PaymentStatus::CANCELED:
                    $order->setState(Order::STATE_CANCELED);
                    $order->setStatus(Config::CITIZEN_ORDER_STATUS_CANCELED);
                    $order->addCommentToStatusHistory(Config::CITIZEN_ORDER_STATUS_CANCELED_COMMENT);
                    $payment->setIsTransactionClosed(true);
                    break;
                case PaymentStatus::REJECTED:
                    $order->setStatus(Config::CITIZEN_ORDER_STATUS_TIMEOUT);
                    $order->addCommentToStatusHistory(Config::CITIZEN_ORDER_STATUS_TIMEOUT_COMMENT);
                    break;
                default:
            }
            $this->orderRepository->save($order);
            $this->orderPaymentRepository->save($payment);
        }
    }
}
