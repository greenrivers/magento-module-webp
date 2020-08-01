<?php

namespace Unexpected\Webp\Utils\Form\Element;

class Time extends \Magento\Framework\Data\Form\Element\Time
{
    public function getHtmlId()
    {
        return 'bbb';
    }

    public function getName()
    {
        return 'aaa';
    }
}