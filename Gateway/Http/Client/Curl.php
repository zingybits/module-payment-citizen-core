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

namespace ZingyBits\CitizenCore\Gateway\Http\Client;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Model\Method\Logger;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Gateway\Http\ConverterInterface;
use ZingyBits\CitizenCore\Gateway\Http\Converter\StringToZendResponse as ResponseFactory;
use ZingyBits\CitizenCore\Gateway\Http\Adapter;

class Curl implements ClientInterface
{
    public const CONFIG_PATH_ALL_GATEWAY_COMMUNICATION = 'advanced/log_all_communication';
    public const LOG_FILE_PATH_ALL_GATEWAY_COMMUNICATION = '/var/log/citizen_all_communication.log';

    /**
     * HTTP protocol versions
     */
    public const HTTP_1 = '1.1';
    public const HTTP_0 = '1.0';

    /**
     * HTTP request methods
     */
    public const GET     = 'GET';
    public const POST    = 'POST';
    public const PUT     = 'PUT';
    public const HEAD    = 'HEAD';
    public const DELETE  = 'DELETE';
    public const TRACE   = 'TRACE';
    public const OPTIONS = 'OPTIONS';
    public const CONNECT = 'CONNECT';
    public const MERGE   = 'MERGE';
    public const PATCH   = 'PATCH';

    /**
     * Request timeout
     */
    public const REQUEST_TIMEOUT = 30;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var Adapter\Curl
     */
    private $curl;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Constructor
     *
     * @param Logger $logger
     * @param ConverterInterface $converter
     * @param ResponseFactory $responseFactory
     * @param Adapter\Curl $curl
     * @param ConfigInterface $config
     */
    public function __construct(
        Logger $logger,
        ConverterInterface $converter,
        ResponseFactory $responseFactory,
        Adapter\Curl $curl,
        ConfigInterface $config
    ) {
        $this->logger = $logger;
        $this->converter = $converter;
        $this->responseFactory = $responseFactory;
        $this->curl = $curl;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $requestBody = $transferObject->getBody();
        $log = [
            'method'      => $transferObject->getMethod(),
            'request'     => $requestBody,
            'request_uri' => $transferObject->getUri()
        ];
        $response = [];

        try {
            $this->curl->setOptions(
                [
                    CURLOPT_TIMEOUT => self::REQUEST_TIMEOUT,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_RETURNTRANSFER => true,
                ]
            );

            $headers = [];
            foreach ($transferObject->getHeaders() as $name => $value) {
                $headers[] = sprintf('%s: %s', $name, $value);
            }
            $this->curl->write(
                $transferObject->getMethod(),
                $transferObject->getUri(),
                self::HTTP_1,
                $headers,
                $requestBody
            );

            //$response = $this->converter->convert($this->read());
            $response = $this->read();

        } catch (\Exception $e) {
            throw new ClientException(__($e->getMessage()));
        } finally {
            $logCommunication = $this->config->getValue(static::CONFIG_PATH_ALL_GATEWAY_COMMUNICATION);
            if ($logCommunication) {
                $log['response'] = $response;
                $log = [
                    'request'  => [
                        'method' => $transferObject->getMethod(),
                        'body'   => $transferObject->getBody(),
                        'url'    => $transferObject->getUri(),
                    ],
                    'response' => $response,
                ];
                $this->logExternal($log);
            }
        }

        return (array) $response;
    }

    /**
     * Return response body
     *
     * @return string|null
     */
    public function read(): ?string
    {
        $response = $this->responseFactory->create($this->curl->read());
        if (200 != $response->getStatus()) {
            $logMessage = $response->getStatus() .' - '. $response->getMessage();
            $this->logger->debug(['gateway response error' => $logMessage]);
            $this->logExternal($logMessage);
        }

        return $response->getBody();
    }

    /**
     * Log response
     *
     * @param $logEntry
     * @return void
     */
    public function logExternal($logEntry): void
    {
        // sanitize the token data
        if (isset($logEntry['response']['access_token'])) {
            $logEntry['response']['access_token'] =
                substr($logEntry['response']['access_token'], 0, 10) . '*****';
        }
        if (isset($logEntry['response']['refresh_token'])) {
            $logEntry['response']['refresh_token'] =
                substr($logEntry['response']['refresh_token'], 0, 10) . '*****';
        }

        file_put_contents(
            BP . static::LOG_FILE_PATH_ALL_GATEWAY_COMMUNICATION,
            date('[Y-m-d H:i:s] ') . json_encode($logEntry) . "\n",
            FILE_APPEND
        );
    }
}
