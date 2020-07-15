<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Algorithm implements OptionSourceInterface
{
    const WEBP_ALGORITHM = 'webp';
    const CWEBP_ALGORITHM = 'cwebp';
    const VIPS_ALGORITHM = 'vips';

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
            self::WEBP_ALGORITHM => __('Webp'),
            self::CWEBP_ALGORITHM => __('Cwebp'),
            self::VIPS_ALGORITHM => __('Vips'),
        ];
    }
}