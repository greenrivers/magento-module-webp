<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Image
{
    /** @var Config */
    private $config;

    /**
     * Image constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $imagePath
     * @return string
     */
    public function changePath(string $imagePath): string
    {
        if ($this->config->getEnabledConfig()) {
            $webpPath = str_replace(
                DirectoryList::MEDIA,
                DirectoryList::MEDIA . DIRECTORY_SEPARATOR . Process::WEBP_PATH,
                $imagePath
            );

            $imagePath = substr_replace(
                $webpPath,
                'webp',
                strrpos($webpPath, '.') + 1
            );
        }

        return $imagePath;
    }
}