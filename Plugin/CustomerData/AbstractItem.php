<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Plugin\CustomerData;

use Magento\Checkout\CustomerData\AbstractItem as Subject;
use Unexpected\Webp\Helper\Image;

class AbstractItem
{
    /** @var Image */
    private $image;

    /**
     * AbstractItem constructor.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @param Subject $item
     * @param array $result
     * @return array
     */
    public function afterGetItemData(Subject $item, array $result): array
    {
        if ($result['product_id'] > 0) {
            $webpImage = $this->image->changePath($result['product_image']['src']);
            $result['product_image']['src'] = $webpImage;
        }

        return $result;
    }
}
