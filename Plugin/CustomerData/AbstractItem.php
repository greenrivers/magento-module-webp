<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Plugin\CustomerData;

use Magento\Checkout\CustomerData\AbstractItem as Subject;
use Greenrivers\Webp\Helper\Image;

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
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetItemData(Subject $subject, array $result): array
    {
        if ($result['product_id'] > 0) {
            $webpImage = $this->image->changePath($result['product_image']['src']);
            $result['product_image']['src'] = $webpImage;
        }

        return $result;
    }
}
