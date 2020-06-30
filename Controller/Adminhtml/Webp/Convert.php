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
use Magento\Framework\View\Result\PageFactory;
use Symfony\Component\Finder\Finder;
use WebPConvert\Convert\Converters\Gd;
use WebPConvert\WebPConvert;

class Convert extends Action
{
    /** @var PageFactory */
    private $resultPageFactory;

    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /**
     * Convert constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Filesystem $filesystem,
        Finder $finder
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        $images = $this->finder->ignoreDotFiles(false)->files()->in($mediaPath)->name(['*.jpg', '*.jpeg', '*.png']);

        foreach ($images as $image) {
            $extension = $image->getExtension();
            $webpPath = substr_replace($image->getPathname(), 'webp', strrpos($image->getPathname(), '.') +1);
            WebPConvert::convert($image->getPathname(), $webpPath);
        }

//        $result->setData(['output' => $mediaPath, 'c' => $c]);
        return $result;
    }
}