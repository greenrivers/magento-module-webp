<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Block\Adminhtml\Conversion\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class CronButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $url = $this->getCronUrl();

        return [
            'label' => __('Cron'),
            'on_click' => "setLocation('${url}')",
            'class' => '',
            'sort_order' => 20,
        ];
    }

    /**
     * @return string
     */
    private function getCronUrl(): string
    {
        return $this->getUrl('*/*/*', ['id' => 'cron']);
    }
}