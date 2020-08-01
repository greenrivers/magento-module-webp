<?php

namespace Unexpected\Webp\Block\Adminhtml\Conversion;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ConvertNowButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label' => __('Convert now'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 20,
        ];
    }
}