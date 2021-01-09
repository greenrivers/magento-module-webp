<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Block\Adminhtml\Conversion;

use Magento\Backend\Block\Widget\Button;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

class Tree extends Template
{
    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl("*/*/add", ['_current' => false, 'id' => null, '_query' => false]);
        if ($this->getStore()->getId() == Store::DEFAULT_STORE_ID) {
            $this->addChild(
                'add_convert_button',
                Button::class,
                [
                    'label' => __('Convert now'),
                    'onclick' => "addNew('" . $addUrl . "', false)",
                    'class' => 'add',
                    'id' => 'add_subcategory_button',
                    'style' => ''
                ]
            );

            $this->addChild(
                'add_cron_button',
                Button::class,
                [
                    'label' => __('Cron'),
                    'onclick' => "addNew('" . $addUrl . "', true)",
                    'class' => 'add',
                    'id' => 'add_root_category_button'
                ]
            );
        }

        return parent::_prepareLayout();
    }

    /**
     * @return StoreInterface|null
     */
    public function getStore(): ?StoreInterface
    {
        $storeId = (int)$this->getRequest()->getParam('store');

        try {
            return $this->_storeManager->getStore($storeId);
        } catch (NoSuchEntityException $e) {
            $this->_logger->error($e->getMessage());

            return null;
        }
    }

    /**
     * @return string
     */
    public function getAddConvertButtonHtml(): string
    {
        return $this->getChildHtml('add_convert_button');
    }

    /**
     * @return string
     */
    public function getAddCronButtonHtml(): string
    {
        return $this->getChildHtml('add_cron_button');
    }
}
