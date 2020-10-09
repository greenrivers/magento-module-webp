<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Block\Adminhtml\Conversion;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use GreenRivers\Webp\Helper\Config;
use GreenRivers\Webp\Utils\Form\Element\Time as TimeElement;

class Time extends Template
{
    /** @var string */
    protected $_template = 'GreenRivers_Webp::conversion/time.phtml';

    /** @var TimeElement */
    private $timeElement;

    /** @var Config */
    private $config;

    /**
     * Time constructor.
     * @param Context $context
     * @param TimeElement $timeElement
     * @param Config $config
     * @param array $data
     */
    public function __construct(Context $context, TimeElement $timeElement, Config $config, array $data = [])
    {
        parent::__construct($context, $data);

        $this->timeElement = $timeElement;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getTimeElementHtml(): string
    {
        return $this->timeElement->getElementHtml();
    }

    /**
     * @return string
     */
    public function getTimeElementName(): string
    {
        return $this->timeElement->getName();
    }

    /**
     * @return string
     */
    public function getTimeElementValue(): string
    {
        return $this->config->getCronTimeConfig();
    }
}