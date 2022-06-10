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

use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use ZingyBits\CitizenCore\Gateway\Config\Enum\Response\PaymentStatus;
use ZingyBits\CitizenCore\Model\Config;
use ZingyBits\CitizenCore\Gateway\Config\Enum;
use ZingyBits\CitizenCore\Api\ConfigInterface;

class OrderStatus
{
    public const LOGGER_PREFIX = 'Citizen_Core::PaymentStatusChanger - ';

    private $orderRepository;
    private $orderPaymentRepository;
    private $logger;
    private $config;

    public function __construct(
        OrderRepositoryInterface        $orderRepository,
        OrderPaymentRepositoryInterface $orderPaymentRepository,
        LoggerInterface                 $logger,
        Config                          $config
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->logger = $logger;
        $this->config = $config;
    }

    public function changeStatus($order, $status): bool
    {
        $payment = $order->getPayment();
        $payment->setIsTransactionClosed(false);

        if ($status) {
            // save the new payment status against the payment
            switch ($status) {
                case PaymentStatus::CREATED:
                    $order->setStatus(ConfigInterface::CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION);
                    $order->setState(Order::STATE_NEW);
                    $order->addCommentToStatusHistory(ConfigInterface::CITIZEN_ORDER_STATUS_PENDING_USER_AUTHORISATION_COMMENT);
                    break;
                case PaymentStatus::ACCEPTED:
                    $order->setStatus(ConfigInterface::CITIZEN_ORDER_STATUS_ACCEPTED);
                    $order->setState(Order::STATE_COMPLETE);
                    $order->addCommentToStatusHistory(ConfigInterface::CITIZEN_ORDER_STATUS_ACCEPTED_COMMENT);
                    $payment->setIsTransactionClosed(true);
                    break;
                case PaymentStatus::INITIATED:
                    $order->setStatus(ConfigInterface::CITIZEN_ORDER_STATUS_INITIATED);
                    $order->setState(Order::STATE_PROCESSING);
                    $order->addCommentToStatusHistory(ConfigInterface::CITIZEN_ORDER_STATUS_INITIATED_COMMENT);
                    break;
                case PaymentStatus::CANCELED:
                    $order->setStatus(ConfigInterface::CITIZEN_ORDER_STATUS_CANCELLED);
                    $order->setState(Order::STATE_CANCELED);
                    $order->addCommentToStatusHistory(ConfigInterface::CITIZEN_ORDER_STATUS_CANCELLED_COMMENT);
                    $payment->setIsTransactionClosed(true);
                    break;
                case PaymentStatus::REJECTED:
                    $order->setStatus(ConfigInterface::CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP);
                    $order->setState(Order::STATE_CANCELED);
                    $order->addCommentToStatusHistory(ConfigInterface::CITIZEN_ORDER_STATUS_REJECTED_BY_ASPSP_COMMENT);
                    $payment->setIsTransactionClosed(true);
                    break;
                case PaymentStatus::FAILED:
                    $order->setStatus(ConfigInterface::CITIZEN_ORDER_STATUS_FAILED);
                    $order->setState(Order::STATE_CANCELED);
                    $order->addCommentToStatusHistory(ConfigInterface::CITIZEN_ORDER_STATUS_FAILED_COMMENT);
                    $payment->setIsTransactionClosed(true);
                    break;
                default:
                    return false;
            }
            $this->orderRepository->save($order);
            $this->orderPaymentRepository->save($payment);

            return true;
        } else {
            $this->logger->error(static::LOGGER_PREFIX . 'Change order status failed');
            return false;
        }


    }

    public function getRedirectPageUrl($order)
    {
        $status = $order->getStatus();
        $redirectPageUrl = null;
        if (in_array($status, Enum::TERMINAL_STATUSES_SUCCESS_PAGE)) {
            $redirectPageUrl = $this->config->getSuccessPage();

        }
        if (in_array($status, Enum::TERMINAL_STATUSES_FAILURE_PAGE)) {
            $redirectPageUrl = $this->config->getFailurePage();

        }
        return $redirectPageUrl;
    }

}
