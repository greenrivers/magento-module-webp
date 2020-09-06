<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Plugin\Block\Product\View;

use Magento\Catalog\Block\Product\View\Gallery as Subject;
use Magento\Framework\Data\Collection;
use Unexpected\Webp\Helper\Image;

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
