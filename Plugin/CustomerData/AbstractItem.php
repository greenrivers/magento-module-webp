<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Plugin\CustomerData;

use Magento\Checkout\CustomerData\AbstractItem as Subject;
use GreenRivers\Webp\Helper\Image;

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
