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

namespace ZingyBits\CitizenCore\Gateway\Http;

use ZingyBits\CitizenCore\Api\ConfigInterface;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Psr\Log\LoggerInterface as Logger;

class AccountAddRedirectTransferFactory implements TransferFactoryInterface
{
    public const LOGGER_PREFIX = 'Citizen_payment_gateway::Gateway/AccountAddRedirectTransferFactory - ';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var TransferBuilder
     */
    private $transferBuilder;

    /**
     * @param Logger $logger
     * @param ConfigInterface $config
     * @param TransferBuilder $transferBuilder
     */
    public function __construct(
        Logger $logger,
        ConfigInterface $config,
        TransferBuilder $transferBuilder
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->transferBuilder = $transferBuilder;
    }

    /**
     * Builds gateway transfer object
     *
     * @param array $request
     * @return TransferInterface
     */
    public function create(array $request)
    {
        // build the REST API call URL
        $restCallUrl = $request['sendUrl'];
        unset($request['sendUrl']);

        $headers = [
            'User-Agent' => 'Curl',
            'Content-Type' => 'application/json',
            'AuthorizationCitizen' => $this->config->getPrivateKey()
        ];

        if ((! $request) || (! is_array($request))) {
            $this->logger->error(self::LOGGER_PREFIX . 'Command request data built failed');
            throw new \UnexpectedValueException('Command request data built failed');
        }
        $request = json_encode($request);

        return $this->transferBuilder
            ->setBody($request)
            ->setMethod('PATCH')
            ->setUri($restCallUrl)
            ->setHeaders($headers)
            ->build();
    }
}
