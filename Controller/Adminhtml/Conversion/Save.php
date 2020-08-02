<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Controller\Adminhtml\Conversion;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\TypeListInterface;
use Unexpected\Webp\Helper\Config;

class Save extends Action
{
    const CONFIG_CACHE = 'config';
    const FULL_PAGE_CACHE = 'full_page';

    /** @var Config */
    private $config;

    /** @var TypeListInterface */
    private $cacheTypeList;

    /**
     * Save constructor.
     * @param Context $context
     * @param Config $config
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(Context $context, Config $config, TypeListInterface $cacheTypeList)
    {
        parent::__construct($context);

        $this->config = $config;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        $requestData = $request->getPost()->toArray();

        $this->saveConfigValues($requestData);

        $this->messageManager->addSuccessMessage(__('Config saved.'));
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    /**
     * @param array $requestData
     */
    private function saveConfigValues(array $requestData): void
    {
        [
            'conversion_image_formats' => $conversionImageFormats,
            'cron' => $cron,
            'frequency' => $frequency,
            'time' => $time,
            'cron_image_formats' => $cronImageFormats
        ] = $requestData;

        $this->config->setValueConfig(Config::XML_CONVERSION_IMAGE_FORMATS_CONFIG_PATH, $conversionImageFormats);
        $this->config->setValueConfig(Config::XML_CRON_ENABLED_CONFIG_PATH, $cron);
        $this->config->setValueConfig(Config::XML_CRON_FREQUENCY_CONFIG_PATH, $frequency);
        $this->config->setValueConfig(Config::XML_CRON_TIME_CONFIG_PATH, $time);
        $this->config->setValueConfig(Config::XML_CRON_IMAGE_FORMATS_CONFIG_PATH, $cronImageFormats);

        $this->cacheTypeList->invalidate([self::CONFIG_CACHE, self::FULL_PAGE_CACHE]);
    }
}