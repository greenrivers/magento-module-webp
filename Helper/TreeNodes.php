<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;

class TreeNodes
{
    const ROOT_ID = 'root';
    const HASH_ID = '#';
    const GREENRIVERS_DIR = 'greenrivers';

    /** @var DirectoryList */
    private $directoryList;

    /** @var File */
    private $driverFile;

    /** @var LoggerInterface */
    private $logger;

    /**
     * TreeNodes constructor.
     * @param DirectoryList $directoryList
     * @param File $driverFile
     * @param LoggerInterface $logger
     */
    public function __construct(DirectoryList $directoryList, File $driverFile, LoggerInterface $logger)
    {
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        $this->logger = $logger;
    }

    /**
     * @param string $nodeId
     * @return array
     */
    public function getDirectories(string $nodeId): array
    {
        $directories = [];

        try {
            $path = $this->directoryList->getPath(DirectoryList::MEDIA);
            if (!in_array($nodeId, [self::ROOT_ID, self::HASH_ID])) {
                $path .= DIRECTORY_SEPARATOR . $nodeId;
            }
            $paths = $this->driverFile->readDirectory($path);
            foreach ($paths as $path) {
                $dirName = substr(strrchr($path, DIRECTORY_SEPARATOR), 1);
                if ($this->driverFile->isDirectory($path) && $dirName !== self::GREENRIVERS_DIR) {
                    $dirId = substr($path, strpos($path, DirectoryList::MEDIA . DIRECTORY_SEPARATOR) + 6);

                    $directories[] = [
                        'text' => $dirName,
                        'id' => $dirId,
                        'path' => $path,
                        'cls' => 'folder'
                    ];
                }
            }
        } catch (FileSystemException $e) {
            $this->logger->error($e->getMessage());
        }

        return $directories;
    }
}
