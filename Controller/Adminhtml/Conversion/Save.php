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
use Unexpected\Webp\Helper\Cron;

class Save extends Action
{
    const CRON_PATH = 'cron';

    const CONFIG_CACHE = 'config';
    const FULL_PAGE_CACHE = 'full_page';

    /** @var Config */
    private $config;

    /** @var TypeListInterface */
    private $cacheTypeList;

    /** @var Cron */
    private $cron;

    /**
     * Save constructor.
     * @param Context $context
     * @param Config $config
     * @param TypeListInterface $cacheTypeList
     * @param Cron $cron
     */
    public function __construct(Context $context, Config $config, TypeListInterface $cacheTypeList, Cron $cron)
    {
        parent::__construct($context);

        $this->config = $config;
        $this->cacheTypeList = $cacheTypeList;
        $this->cron = $cron;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        $requestData = $request->getPost()->toArray();
        $refererUrl = $this->_redirect->getRefererUrl();

        $this->saveConfigValues($requestData, $refererUrl);

        $this->messageManager->addSuccessMessage(__('Config saved.'));
        $resultRedirect->setUrl($refererUrl);
        return $resultRedirect;
    }

    /**
     * @param array $requestData
     * @param string $refererUrl
     */
    private function saveConfigValues(array $requestData, string $refererUrl): void
    {
        [
            'conversion_folders' => $conversionFolders,
            'conversion_image_formats' => $conversionImageFormats,
            'cron_folders' => $cronFolders,
            'cron' => $cron,
            'frequency' => $frequency,
            'time' => $time,
            'cron_image_formats' => $cronImageFormats
        ] = $requestData;

        if (strpos($refererUrl, self::CRON_PATH) !== false) {
            $this->config->setValueConfig(Config::XML_CRON_FOLDERS_CONFIG_PATH, $cronFolders);
            $this->config->setValueConfig(Config::XML_CRON_ENABLED_CONFIG_PATH, $cron);
            $this->config->setValueConfig(Config::XML_CRON_FREQUENCY_CONFIG_PATH, $frequency);
            $this->config->setValueConfig(Config::XML_CRON_TIME_CONFIG_PATH, $time);
            $this->config->setValueConfig(Config::XML_CRON_IMAGE_FORMATS_CONFIG_PATH, $cronImageFormats);

            $this->cron->saveConfig($time, $frequency);
        } else {
            $this->config->setValueConfig(Config::XML_CONVERSION_FOLDERS_CONFIG_PATH, $conversionFolders);
            $this->config->setValueConfig(Config::XML_CONVERSION_IMAGE_FORMATS_CONFIG_PATH, $conversionImageFormats);
        }

        $this->cacheTypeList->invalidate([self::CONFIG_CACHE, self::FULL_PAGE_CACHE]);
    }
}