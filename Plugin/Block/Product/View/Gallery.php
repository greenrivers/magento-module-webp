<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Plugin\Block\Product\View;

use Magento\Catalog\Block\Product\View\Gallery as Subject;
use Magento\Framework\Data\Collection;
use Greenrivers\Webp\Helper\Image;

class Gallery
{
    /** @var Image */
    private $image;

    /**
     * Gallery constructor.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @param Subject $subject
     * @param Collection $result
     * @return Collection
     */
    public function afterGetGalleryImages(Subject $subject, Collection $result): Collection
    {
        $images = $result->getItems();

        foreach ($images as $image) {
            $image->setUrl($this->image->changePath($image->getUrl()));
            $image->setSmallImageUrl($this->image->changePath($image->getSmallImageUrl()));
            $image->setMediumImageUrl($this->image->changePath($image->getMediumImageUrl()));
            $image->setLargeImageUrl($this->image->changePath($image->getLargeImageUrl()));
        }

        return $result;
    }
}
