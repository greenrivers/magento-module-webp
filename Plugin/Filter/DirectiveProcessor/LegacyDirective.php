<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Plugin\Filter\DirectiveProcessor;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filter\DirectiveProcessor\LegacyDirective as Subject;
use Unexpected\Webp\Helper\Image;

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