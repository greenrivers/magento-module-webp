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

    const XML_ALGORITHM_CONFIG_PATH = 'webp/settings/algorithm';
    const XML_QUALITY_CONFIG_PATH = 'webp/settings/quality';

    const XML_CONVERT_UPLOAD_CONFIG_PATH = 'webp/conversion/convert_upload';

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
    public function getAlgorithmConfig(): array
    {
        $algorithms = $this->scopeConfig->getValue(self::XML_ALGORITHM_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(',', $algorithms);
    }

    /**
     * @return int
     */
    public function getQualityConfig(): int
    {
        return $this->scopeConfig->getValue(self::XML_QUALITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function getConvertUploadConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_CONVERT_UPLOAD_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
