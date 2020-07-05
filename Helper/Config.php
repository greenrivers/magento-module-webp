<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_ENABLE_CONFIG_PATH = 'webp/general/enable';
    const XML_EXTENSION_CONFIG_PATH = 'webp/backend/extension';
    const XML_TYPE_CONFIG_PATH = 'webp/backend/type';

    const XML_QUALITY_CONFIG_PATH = 'webp/advanced/quality';

    /**
     * @return bool
     */
    public function getEnableConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_ENABLE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getExtensionConfig(): array
    {
        $extensions = $this->scopeConfig->getValue(self::XML_EXTENSION_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(',', $extensions);
    }

    /**
     * @return string
     */
    public function getTypeConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_TYPE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getQualityConfig(): int
    {
        return $this->scopeConfig->getValue(self::XML_QUALITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
