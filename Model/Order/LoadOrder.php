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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Order;

class LoadOrder
{
    public const LOGGER_PREFIX = 'Citizen_Core::LoadOrder - ';
    public const ORDER_ID_PARAM = 'order';
    public const ORDER_INCREMENT_ID_PARAM = 'orderId';
    public const QUOTE_ID_PARAM = 'qid';

    /**
     * @var QuoteFactory
     */
    private $quoteFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MaskedQuoteIdToQuoteIdInterface
     */
    private $maskToQuoteId;

    /**
     * @var OrderFactory
     */
    private $orderFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Order
     */
    private $order;

    /**
     * @param QuoteFactory $quoteFactory
     * @param LoggerInterface $logger
     * @param MaskedQuoteIdToQuoteIdInterface $maskToQuoteId
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        QuoteFactory                    $quoteFactory,
        LoggerInterface                 $logger,
        MaskedQuoteIdToQuoteIdInterface $maskToQuoteId,
        OrderFactory                    $orderFactory
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->logger = $logger;
        $this->maskToQuoteId = $maskToQuoteId;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Return order
     *
     * @param RequestInterface $request
     * @return mixed
     */
    public function getOrder(RequestInterface $request)
    {
        $this->request = $request;
        $orderEntityId = $this->request->getParam(self::ORDER_ID_PARAM);
        $orderIncrementId = null;

        if ($orderEntityId) {
            $this->order = $this->orderFactory->create()->loadByAttribute('entity_id', $orderEntityId);
        } elseif ($orderIncrementId = $this->getOrderIdByQuoteId()) {
            $this->order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
        } elseif ($orderIncrementId = $this->request->getParam(self::ORDER_INCREMENT_ID_PARAM)) {
            $this->order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
        } else {
            $this->logger->error(self::LOGGER_PREFIX . 'no order or quote provided');
        }
        $logContext = [
            'orderEntityId' => $orderEntityId,
            'orderIncrementId' => $orderIncrementId
        ];

        if (!$this->order || !$this->order->getEntityId()) {
            $this->logger->error(self::LOGGER_PREFIX . 'no order loaded', $logContext);
            throw new \InvalidArgumentException('no order loaded');
        }

        return $this->order;
    }

    /**
     * Return order by quoteId
     *
     * @return string|null
     * @throws NoSuchEntityException
     */
    private function getOrderIdByQuoteId(): ?string
    {
        $quoteId = $this->request->getParam(self::QUOTE_ID_PARAM);

        if ($quoteId) {
            // since 2.3.1 quoteId is being sent as masked_quote_id
            if (!is_int($quoteId)) {
                $quoteId = $this->maskToQuoteId->execute($quoteId);
            }

            $quote = $this->quoteFactory->create()->loadByIdWithoutStore($quoteId);
            if ($quote->getId() && $quote->getReservedOrderId()) {

                return $quote->getReservedOrderId();

            } else {
                $logContext = ['quote ID' => $quoteId];
                $this->logger->error(self::LOGGER_PREFIX . 'unable to load quote or get its order id', $logContext);
            }
        }

        return null;
    }
}
