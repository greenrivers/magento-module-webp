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

    /**
     * @return bool
     */
    public function getEnableConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_ENABLE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
