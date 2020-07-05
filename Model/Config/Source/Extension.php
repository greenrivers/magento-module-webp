<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Extension implements OptionSourceInterface
{
    const JPG_EXTENSION = '*.jpg';
    const JPEG_EXTENSION = '*.jpeg';
    const PNG_EXTENSION = '*.png';

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
            self::JPG_EXTENSION => __('jpg'),
            self::JPEG_EXTENSION => __('jpeg'),
            self::PNG_EXTENSION => __('png'),
        ];
    }
}