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

class ToggleSwitch extends Field
{
    const WEBP_GENERAL_ENABLED = 'webp-general-enabled';
    const WEBP_CONVERT_PRODUCT_IMAGES = 'webp-conversion-convert-product-images';

    /** @var string */
    protected $_template = 'Unexpected_Webp::system/config/toggle_switch.phtml';

    /** @var Config */
    private $config;

    /** @var AbstractElement */
    private $element;

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
     * @return array
     */
    public function getComponent(): array
    {
        $element = $this->getElement();
        $id = str_replace('_', '-', $element->getHtmlId());
        $name = $element->getName();
        $component = ['id' => $id, 'name' => $name];

        switch ($id) {
            case self::WEBP_GENERAL_ENABLED:
                $component['value'] = $this->config->getEnabledConfig();
                break;
            case self::WEBP_CONVERT_PRODUCT_IMAGES:
                $component['value'] = $this->config->getConvertProductImagesConfig();
                break;
        }

        return $component;
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element): string
    {
        $this->setElement($element);
        $html = "<td class='label'>" . $element->getLabel() . '</td><td>' . $this->toHtml() . '</td><td></td>';
        return $this->_decorateRowHtml($element, $html);
    }

    /**
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->element;
    }

    /**
     * @param AbstractElement $element
     */
    public function setElement(AbstractElement $element): void
    {
        $this->element = $element;
    }
}
