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

namespace ZingyBits\CitizenCore\Gateway\Request\Mapper;

abstract class AbstractRequestMapper implements RequestMapperInterface
{
    public const URI_SUFFIX = '';
    public const TRANSFER_METHOD = 'POST';
    public const HEADER_AUTH_KEY_MODE = 'private';
    public const HEADER_CONTENT_TYPE = 'application/json';
    public const RESPONSE_PARAMS = [];
    public const STATIC_CONFIG_PARAMS_MAPPING = [];
    public const DYNAMIC_CONFIG_PARAMS_MAPPING = [];

    /**
     * @inheritdoc
     */
    public function getTransferMethod(): string
    {
        return (string)$this::TRANSFER_METHOD;
    }

    /**
     * @inheritdoc
     */
    public function getHeaderAuthKeyMode(): string
    {
        return (string)$this::HEADER_AUTH_KEY_MODE;
    }

    /**
     * @inheritdoc
     */
    public function getHeaderContentType(): string
    {
        return (string)$this::HEADER_CONTENT_TYPE;
    }

    /**
     * @inheritdoc
     */
    public function getUriSuffix(): string
    {
        return (string)$this::URI_SUFFIX;
    }

    /**
     * @inheritdoc
     */
    public function getResponseParams(): array
    {
        return $this::RESPONSE_PARAMS;
    }

    /**
     * @inheritdoc
     */
    public function getStaticParamsMapping(): array
    {
        return $this::STATIC_CONFIG_PARAMS_MAPPING;
    }

    /**
     * @inheritdoc
     */
    public function getDynamicParamsMapping(): array
    {
        return $this::DYNAMIC_CONFIG_PARAMS_MAPPING;
    }
}
