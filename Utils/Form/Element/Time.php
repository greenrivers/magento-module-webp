<?php

namespace Unexpected\Webp\Utils\Form\Element;

class Time extends \Magento\Framework\Data\Form\Element\Time
{
    /**
     * @inheritDoc
     */
    public function getHtmlId()
    {
        return 'time';
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'time';
    }
}