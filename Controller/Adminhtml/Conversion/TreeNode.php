<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Controller\Adminhtml\Conversion;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem\Driver\File;

class TreeNode extends Action
{
    const ROOT_ID = 'root';
    const UNEXPECTED_DIR = 'unexpected';

    /** @var JsonFactory */
    private $jsonResultFactory;

    /** @var DirectoryList */
    private $directoryList;

    /** @var File */
    private $driverFile;

    /**
     * TreeNode constructor.
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param DirectoryList $directoryList
     * @param File $driverFile
     */
    public function __construct(Context $context, JsonFactory $jsonResultFactory, DirectoryList $directoryList, File $driverFile)
    {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $nodeId = $this->getRequest()->getParam('node');

        $path = $this->directoryList->getPath(DirectoryList::MEDIA);

        if ($nodeId !== self::ROOT_ID) {
            $path .= '/' . $nodeId;
        }

        $paths = $this->driverFile->readDirectory($path);
        $directories = [];

        foreach ($paths as $path) {
            $dirName = substr(strrchr($path, '/'), 1);
            if ($this->driverFile->isDirectory($path) && $dirName !== self::UNEXPECTED_DIR) {
                $dirId = substr($path, strpos($path, DirectoryList::MEDIA . '/') + 6);

                $directories[] = [
                    'text' => $dirName,
                    'id' => $dirId,
                    'path' => $path,
                    'cls' => 'folder'
                ];
            }
        }

        $resultJson = $this->jsonResultFactory->create();
        $resultJson->setData($directories);
        return $resultJson;
    }
}