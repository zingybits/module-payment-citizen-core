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

use ZingyBits\CitizenCore\Api\ConfigInterface as ApiConfig;
use ZingyBits\CitizenCore\Gateway\Config\Config as GatewayConfig;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Api\Data\TransactionInterface as Transaction;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;

/**
 * Class AddNewTransactionHandler
 * @package ZingyBits\CitizenCore\Gateway\Response
 */
class AddNewTransactionHandler implements HandlerInterface
{
    /**
     * @inheritdoc
     */
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        /** @var Payment $payment */
        $payment = $paymentDO->getPayment();
        /** @var Order $order */
        $order = $payment->getOrder();

        if (! empty($response)) {
            $transactionId = is_array($response) ? $response[0] : $response;

            // save the TxnID against the payment
            $payment->setData(
                GatewayConfig::M2_PAYMENT_TXN_ID,
                $transactionId
            );
            $additionalData = json_decode($payment->getAdditionalData() ?? '[]', true) ?? [];
            $transactionIdData = ['transaction_id' => $transactionId];
            $payment->setAdditionalData(json_encode(array_merge($additionalData, $transactionIdData)));

            $parentTxnId = $transactionId;
            $authorizationTransaction = $payment->getAuthorizationTransaction();
            if ($authorizationTransaction && $authorizationTransaction->getParentTxnId()) {
                $parentTxnId = $authorizationTransaction->getParentTxnId();
            }
            $payment->setParentTransactionId($parentTxnId);

            // add new transaction
            // -- create custom transaction ID for each transaction
            $transactionId = $transactionId . '_' . uniqid();
            $payment->setTransactionId($transactionId);
            $payment->setLastTransId($transactionId);
            $transaction = $payment->addTransaction(Transaction::TYPE_ORDER);
            $message = $payment->prependMessage(ApiConfig::NEW_TRANSACTION_ORDER_MESSAGE);
            $payment->addTransactionCommentsToOrder($transaction, $message);

            // also pass the amount data to the transaction if set
            if (isset($handlingSubject['amount']) && is_numeric($handlingSubject['amount'])) {
                $payment->setTransactionAdditionalInfo('amount', $handlingSubject['amount']);
            }
        }

        $payment->setIsTransactionClosed(false);
        $payment->setShouldCloseParentTransaction(false);

        $order->setStatus(ApiConfig::CITIZEN_ORDER_STATUS_INITIATED);
    }
}
