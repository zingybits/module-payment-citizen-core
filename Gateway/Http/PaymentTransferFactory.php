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
use ZingyBits\CitizenCore\Gateway\Request\Mapper\RequestMapperInterface;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Psr\Log\LoggerInterface as Logger;

class PaymentTransferFactory implements TransferFactoryInterface
{
    public const LOGGER_PREFIX = 'Citizen_payment_gateway::Gateway/PaymentTransferFactory - ';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var RequestMapperInterface
     */
    protected $mapper;

    /**
     * @var TransferBuilder
     */
    private $transferBuilder;

    /**
     * @param Logger $logger
     * @param ConfigInterface $config
     * @param TransferBuilder $transferBuilder
     * @param RequestMapperInterface $mapper
     */
    public function __construct(
        Logger                 $logger,
        ConfigInterface        $config,
        TransferBuilder        $transferBuilder,
        RequestMapperInterface $mapper
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->transferBuilder = $transferBuilder;
        $this->mapper = $mapper;
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
        $uriSuffix = $this->mapper->getUriSuffix();

        if (!$uriSuffix) {
            $this->logger->error(self::LOGGER_PREFIX . 'REST URI suffix must be provided');
            throw new \InvalidArgumentException('REST URI suffix must be provided');
        }

        // if dynamic params are part of the URL - map their values
        if ((strpos($uriSuffix, '{') !== false)
            && (strpos($uriSuffix, '}') !== false)
        ) {
            $staticParamsMapping = $this->mapper->getStaticParamsMapping();
            if (!empty($staticParamsMapping)) {
                foreach ($staticParamsMapping as $urlVariable => $configCode) {
                    $configValue = $this->config->getValue($configCode);
                    if ($configValue) {
                        $uriSuffix = str_replace('{' . $urlVariable . '}', $configValue, $uriSuffix);
                    }
                }
            }
            $dynamicParamsMapping = $this->mapper->getDynamicParamsMapping();
            if (!empty($dynamicParamsMapping)) {
                foreach ($dynamicParamsMapping as $urlVariable => $paramCode) {
                    $configValue = $request[$paramCode] ?? null;
                    if ($configValue) {
                        $uriSuffix = str_replace('{' . $urlVariable . '}', $configValue, $uriSuffix);
                    }
                }
            }
        }

        // build the REST API call URL
        $restCallUrl = $this->config->getGatewayApiUrl() . '/' . $uriSuffix;

        // HEADERS
        $headersContentType = $this->mapper->getHeaderContentType();
        $headers = [
            'Content-Type' => $headersContentType
        ];

        // headers auth key
        $isHeadersAuthKeyRequired = true;
        $headersAuthKey = null;
        switch ($this->mapper->getHeaderAuthKeyMode()) {
            case 'private':
                $headersAuthKey = $this->config->getPrivateKey();
                break;
            case 'public':
                $headersAuthKey = $this->config->getPublicKey();
                break;
            case 'none':
                $isHeadersAuthKeyRequired = false;
                break;
            default:
                //;
        }
        if ($isHeadersAuthKeyRequired) {
            if (!$headersAuthKey) {
                $this->logger->error(self::LOGGER_PREFIX . 'Headers AuthKey must be provided');
                throw new \InvalidArgumentException('Headers AuthKey must be provided');
            }
            $headers['AuthorizationCitizen'] = $headersAuthKey;
        }

        // GET method handling
        if ('GET' === $this->mapper->getTransferMethod()) {

            return $this->transferBuilder
                ->setMethod('GET')
                ->setUri($restCallUrl)
                ->setHeaders($headers)
                ->build();
        }

        // POST, PUT, PATCH.. methods handling
        if ((!$request) || (!is_array($request))) {
            $this->logger->error(self::LOGGER_PREFIX . 'Command request data built failed');
            throw new \UnexpectedValueException('Command request data built failed');
        }
        $request = json_encode($request);

        return $this->transferBuilder
            ->setBody($request)
            ->setMethod($this->mapper->getTransferMethod())
            ->setUri($restCallUrl)
            ->setHeaders($headers)
            ->build();
    }
}
