<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Controller\Adminhtml\Convert;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem\Driver\File;

class TreeNode extends Action
{
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

        if ($nodeId !== 'root') {
            $path .= '/' . $nodeId;
        }

        $paths = $this->driverFile->readDirectory($path);
        $directories = [];

        foreach ($paths as $path) {
            if ($this->driverFile->isDirectory($path)) {
                $dirName = substr(strrchr($path, '/'), 1);
                $dirId = substr($path, strpos($path, DirectoryList::MEDIA . '/') + 6);

                $directories[] = [
                    'text' => $dirName,
                    'id' => $dirId,
                    'path' => 'some/path',
                    'cls' => 'folder'
                ];
            }
        }

        $resultJson = $this->jsonResultFactory->create();
        $resultJson->setData($directories);
        return $resultJson;
    }
}