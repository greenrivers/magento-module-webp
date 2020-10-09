<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Controller\Adminhtml\Webp;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use GreenRivers\Webp\Helper\Process;

class Clear extends Action
{
    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Process */
    private $process;

    /**
     * Clear constructor.
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
        $images = $this->process->getImages();

        $removedImages = $this->process->clear($images);
        $result->setData(['removed_images' => $removedImages]);

        return $result;
    }
}
