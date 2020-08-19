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
use Symfony\Component\Finder\Finder;

class Files extends Action
{
    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /**
     * Files constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $filesystem
     * @param Finder $finder
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Filesystem $filesystem,
        Finder $finder
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->filesystem = $filesystem;
        $this->finder = $finder;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $extensions = $this->getRequest()->getParam('extensions');

        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        $images = $this->finder
            ->ignoreDotFiles(false)
            ->files()
            ->in($mediaPath)
            ->name($extensions);

        $result->setData(['files' => $images->count()]);

        return $result;
    }
}