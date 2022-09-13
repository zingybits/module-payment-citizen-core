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

/**
 * Interface RequestMapperInterface
 * @package ZingyBits\CitizenCore\Gateway\Request\Mapper
 * @api
 */
interface RequestMapperInterface
{
    /**
     * Return the transfer method for the REST API call - GET, POST, PUT ..
     *
     * @return string
     */
    public function getTransferMethod(): string;

    /**
     * Return the authorisation key mode for the Headers - privateKey, PublicKey ..
     *
     * @return string
     */
    public function getHeaderAuthKeyMode(): string;

    /**
     * Return the Headers content type
     *
     * @return string
     */
    public function getHeaderContentType(): string;

    /**
     * Return URI suffix for the REST API call
     *
     * @return string
     */
    public function getUriSuffix(): string;

    /**
     * Return list of response params
     *
     * @return array
     */
    public function getResponseParams(): array;

    /**
     * Return mapping array of static params (saved in DB - core_config_data)
     *
     * @return array
     */
    public function getStaticParamsMapping(): array;

    /**
     * Return mapping array of dynamic params - sent within the DataBuilder request
     *
     * @return array
     */
    public function getDynamicParamsMapping(): array;
}
