<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Plugin\Block\Product;

use Magento\Catalog\Block\Product\Image as ProductImage;
use Unexpected\Webp\Helper\Image as ImageHelper;

class Image
{
    /** @var ImageHelper */
    private $imageHelper;

    /**
     * Image constructor.
     * @param ImageHelper $imageHelper
     */
    public function __construct(ImageHelper $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    /**
     * @param ProductImage $image
     * @param $result
     * @param string $method
     * @return string
     */
    public function after__call(ProductImage $image, $result, string $method): string
    {
        if ($method == 'getImageUrl' && $image->getProductId() > 0) {
            $result = $this->imageHelper->changePath($result);
        }

        return $result;
    }
}
