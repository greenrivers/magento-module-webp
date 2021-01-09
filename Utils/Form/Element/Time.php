<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Utils\Form\Element;

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
