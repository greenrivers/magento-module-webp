<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Plugin\Filter\DirectiveProcessor;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filter\DirectiveProcessor\LegacyDirective as Subject;
use Greenrivers\Webp\Helper\Image;

class LegacyDirective
{
    /** @var Image */
    private $image;

    /**
     * LegacyDirective constructor.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @param Subject $subject
     * @param string $result
     * @param array $construction
     * @return string
     */
    public function afterProcess(Subject $subject, string $result, array $construction): string
    {
        if ($construction[1] === DirectoryList::MEDIA) {
            $result = $this->image->changePath($result);
        }

        return $result;
    }
}
