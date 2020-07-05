<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{
    const WEBP_TYPE = 'webp';
    const CWEBP_TYPE = 'cwebp';
    const VIPS_TYPE = 'vips';

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        $optionArray = [];
        foreach ($this->toArray() as $key => $value) {
            $optionArray[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $optionArray;
    }

    /**
     * @return array
     */
    private function toArray(): array
    {
        return [
            self::WEBP_TYPE => __('Webp'),
            self::CWEBP_TYPE => __('Cwebp'),
            self::VIPS_TYPE => __('Vips'),
        ];
    }
}