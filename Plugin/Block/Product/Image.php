<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Plugin\Block\Product;

use Magento\Catalog\Block\Product\Image as Subject;
use GreenRivers\Webp\Helper\Image as ImageHelper;

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
     * @param Subject $subject
     * @param $result
     * @param string $method
     * @return string
     */
    public function after__call(Subject $subject, $result, string $method): string
    {
        if ($method == 'getImageUrl' && $subject->getProductId() > 0) {
            $result = $this->imageHelper->changePath($result);
        }

        return $result;
    }
}
