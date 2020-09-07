<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class Image
{
    /** @var Config */
    private $config;

    /** @var File */
    private $file;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Image constructor.
     * @param Config $config
     * @param File $file
     * @param LoggerInterface $logger
     */
    public function __construct(Config $config, File $file, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->file = $file;
        $this->logger = $logger;
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

            $webpPath = substr_replace(
                $webpPath,
                'webp',
                strrpos($webpPath, '.') + 1
            );

            try {
                if ($this->file->isExists($webpPath)) {
                    $imagePath = $webpPath;
                }
            } catch (FileSystemException $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $imagePath;
    }
}
