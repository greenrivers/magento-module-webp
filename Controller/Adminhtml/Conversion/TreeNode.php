<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Controller\Adminhtml\Conversion;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use GreenRivers\Webp\Helper\TreeNodes;

class TreeNode extends Action
{
    /** @var JsonFactory */
    private $jsonResultFactory;

    /** @var TreeNodes */
    private $treeNodes;

    /**
     * TreeNode constructor.
     * @param Context $context
     * @param JsonFactory $jsonResultFactory
     * @param TreeNodes $treeNodes
     */
    public function __construct(Context $context, JsonFactory $jsonResultFactory, TreeNodes $treeNodes)
    {
        parent::__construct($context);

        $this->jsonResultFactory = $jsonResultFactory;
        $this->treeNodes = $treeNodes;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultJson = $this->jsonResultFactory->create();
        $nodeId = $this->getRequest()->getParam('node');

        $directories = $this->treeNodes->getDirectories($nodeId);
        $resultJson->setData($directories);

        return $resultJson;
    }
}
