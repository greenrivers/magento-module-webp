<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Controller\Adminhtml\Webp;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Symfony\Component\Finder\Finder;

class Clear extends Action
{
    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /** @var File */
    private $file;

    /**
     * Clear constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $filesystem
     * @param Finder $finder
     * @param File $file
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Filesystem $filesystem,
        Finder $finder,
        File $file
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $removedFiles = 0;
        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        $webpDir = $mediaPath . 'unexpected/webp/';

        $images = $this->finder
            ->ignoreDotFiles(false)
            ->files()
            ->in($webpDir)
            ->name('*.webp');

        foreach ($images as $image) {
            if ($removedFiles < 100) {
                $imagePath = $image->getPathname();

                if ($this->file->isExists($imagePath)) {
                    $this->file->deleteFile($imagePath);
                }

                $removedFiles++;
            } else {
                break;
            }
        }

        $result->setData(['removed_files' => $removedFiles]);

        return $result;
    }
}