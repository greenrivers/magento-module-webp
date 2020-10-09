<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Helper;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_ENABLED_CONFIG_PATH = 'webp/general/enabled';

    const XML_ALGORITHM_CONFIG_PATH = 'webp/settings/algorithm';
    const XML_QUALITY_CONFIG_PATH = 'webp/settings/quality';

    const XML_CONVERT_PRODUCT_IMAGES_CONFIG_PATH = 'webp/conversion/convert_product_images';

    const XML_CONVERSION_FOLDERS_CONFIG_PATH = 'webp/conversion/folders';
    const XML_CONVERSION_IMAGE_FORMATS_CONFIG_PATH = 'webp/conversion/image_formats';

    const XML_CRON_FOLDERS_CONFIG_PATH = 'webp/cron/folders';
    const XML_CRON_ENABLED_CONFIG_PATH = 'webp/cron/enabled';
    const XML_CRON_FREQUENCY_CONFIG_PATH = 'webp/cron/frequency';
    const XML_CRON_TIME_CONFIG_PATH = 'webp/cron/time';
    const XML_CRON_IMAGE_FORMATS_CONFIG_PATH = 'webp/cron/image_formats';

    /** @var WriterInterface */
    private $configWriter;

    /**
     * Config constructor.
     * @param Context $context
     * @param WriterInterface $configWriter
     */
    public function __construct(Context $context, WriterInterface $configWriter)
    {
        parent::__construct($context);

        $this->configWriter = $configWriter;
    }

    /**
     * @return bool
     */
    public function getEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_ENABLED_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getAlgorithmConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_ALGORITHM_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
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
    public function getConvertProductImagesConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_CONVERT_PRODUCT_IMAGES_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getConversionFoldersConfig(): array
    {
        $folders = $this->scopeConfig->getValue(self::XML_CONVERSION_FOLDERS_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(',', $folders);
    }

    /**
     * @return array
     */
    public function getConversionImageFormatsConfig(): array
    {
        $formats = $this->scopeConfig->getValue(self::XML_CONVERSION_IMAGE_FORMATS_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(',', $formats);
    }

    /**
     * @return array
     */
    public function getCronFoldersConfig(): array
    {
        $folders = $this->scopeConfig->getValue(self::XML_CRON_FOLDERS_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(',', $folders);
    }

    /**
     * @return bool
     */
    public function getCronEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_CRON_ENABLED_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getCronFrequencyConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_CRON_FREQUENCY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getCronTimeConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_CRON_TIME_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getCronImageFormatsConfig(): array
    {
        $formats = $this->scopeConfig->getValue(self::XML_CRON_IMAGE_FORMATS_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(',', $formats);
    }

    /**
     * @param string $path
     * @param $value
     */
    public function setValueConfig(string $path, $value): void
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $this->configWriter->save($path, $value);
    }
}
