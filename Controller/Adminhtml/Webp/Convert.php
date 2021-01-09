<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Controller\Adminhtml\Webp;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Greenrivers\Webp\Helper\Process;

class Convert extends Action
{
    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Process */
    private $process;

    /**
     * Convert constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Process $process
     */
    public function __construct(Context $context, JsonFactory $resultJsonFactory, Process $process)
    {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->process = $process;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $extensions = $this->getRequest()->getParam('extensions');
        $folders = $this->getRequest()->getParam('folders');

        $images = $this->process->getImages($extensions, $folders);
        $imagesToConversion = $this->process->getImagesToConversion($images);

        $convertData = $this->process->convert($imagesToConversion, false, null);

        $result->setData($convertData);

        return $result;
    }
}
