<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Unexpected\Webp\Helper\Config;

class SliderRange extends Field
{
    /** @var string */
    protected $_template = 'Unexpected_Webp::system/config/slider_range.phtml';

    /** @var Config */
    private $config;

    /**
     * Checkbox constructor.
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(Context $context, Config $config, array $data = [])
    {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->config->getQualityConfig();
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element): string
    {
        $html = "<td class='label'>" . $element->getLabel() . '</td><td>' . $this->toHtml() . '</td><td></td>';
        return $this->_decorateRowHtml($element, $html);
    }
}
