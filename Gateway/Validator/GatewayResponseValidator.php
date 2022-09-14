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

namespace ZingyBits\CitizenCore\Gateway\Validator;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;
use Psr\Log\LoggerInterface;

class GatewayResponseValidator extends AbstractValidator
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $errors;

    /**
     * PaymentCreateValidator constructor
     *
     * @param ResultInterfaceFactory $resultFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        LoggerInterface        $logger
    ) {
        $this->logger = $logger;
        $this->errors = [];
        parent::__construct($resultFactory);
    }

    /**
     * Performs gateway response validation
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject): ResultInterface
    {
        $response = SubjectReader::readResponse($validationSubject);

        if ($this->isErrorResult($response)) {
            return $this->createResult(false, $this->errors);
        } else {
            return $this->createResult(
                true,
                []
            );
        }
    }

    /**
     * Validate result
     *
     * @param array $response
     * @return bool
     */
    private function isErrorResult(array $response): bool
    {
        $isError = false;

        if (isset($response['errors'])) {
            $isError = true;
            foreach ($response['errors'] as $error) {
                $message = "PaymentResultValidator - {$error['message']}".
                    "Error code = {$error['error_code']}. Field - {$error['field']}";
                $this->errors[] = $message;
                $this->logger->error($message);
            }
        }

        return $isError;
    }
}
