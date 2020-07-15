<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Controller\Adminhtml\Webp;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Symfony\Component\Finder\Finder;
use Unexpected\Webp\Helper\Config;
use Unexpected\Webp\Helper\Converter;

class Convert extends Action
{
    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /** @var Filesystem\Io\File */
    private $file;

    /** @var DirectoryList */
    private $directoryList;

    /** @var Converter */
    private $converter;

    /** @var Config */
    private $config;

    /**
     * Convert constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $filesystem
     * @param Finder $finder
     * @param Filesystem\Io\File $file
     * @param DirectoryList $directoryList
     * @param Converter $converter
     * @param Config $config
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Filesystem $filesystem,
        Finder $finder,
        Filesystem\Io\File $file,
        DirectoryList $directoryList,
        Converter $converter,
        Config $config
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        $this->file = $file;
        $this->directoryList = $directoryList;
        $this->converter = $converter;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        $images = $this->finder
            ->ignoreDotFiles(false)
            ->files()
            ->in($mediaPath)
            ->name($this->config->getExtensionConfig());

//        $i = 0;

        foreach ($images as $image) {
            $imagePath = $image->getPathname();

            $webpDir = $mediaPath . 'unexpected/webp/' . $image->getRelativePath();
            $webpImage = substr_replace($image->getFilename(), 'webp', strrpos($image->getFilename(), '.') + 1);
            $webpPath = $webpDir . '/' . $webpImage;

            if (!file_exists($webpDir)) {
                $this->file->mkdir($webpDir);
            }

            $this->converter->convert($imagePath, $webpPath);

//            $i++;
//            if ($i === 100) {
//                break;
//            }
        }

        $result->setData(['output' => $mediaPath]);

        return $result;
    }
}