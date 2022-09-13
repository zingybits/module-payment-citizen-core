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

namespace ZingyBits\CitizenCore\Gateway\Http\Converter;

use Magento\Payment\Gateway\Http\ConverterException;
use Magento\Payment\Gateway\Http\ConverterInterface;

/**
 * Class JsonToArray
 */
class JsonToArray implements ConverterInterface
{
    /**
     * Convert gateway json response to ENV structure
     *
     * @param mixed $response
     * @return string
     * @throws ConverterException
     */
    public function convert($response)
    {
        if (! \is_string($response)) {
            throw new ConverterException(__('Wrong response type'));
        }

        return json_decode($response, true);
    }
}
