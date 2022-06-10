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

namespace ZingyBits\CitizenCore\Model;

/**
 * @api
 * @since 100.0.2
 */
class CreateSessionUrlOptions implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
        ['value' => 'https://testapi.paywithcitizen.com/v2', 'label' => 'Test url'],
        ['value' => 'https://api.paywithcitizen.com/v2', 'label' => 'Production url']
        ];
    }
}
