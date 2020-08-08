<?php

namespace Unexpected\Webp\Model\Conversion;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as BaseDataProvider;
use Unexpected\Webp\Helper\Config;

class DataProvider extends BaseDataProvider
{
    /** @var Config */
    private $config;

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param Config $config
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        Config $config,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $data = [
            'conversion_folders' => $this->config->getConversionFoldersConfig(),
            'conversion_image_formats' => $this->config->getConversionImageFormatsConfig(),
            'cron_folders' => $this->config->getCronFoldersConfig(),
            'cron' => $this->config->getCronEnabledConfig(),
            'frequency' => $this->config->getCronFrequencyConfig(),
            'time' => $this->config->getCronTimeConfig(),
            'cron_image_formats' => $this->config->getCronImageFormatsConfig()
        ];

        return [
            '' => $data,
            'convert_now' => $data,
            'cron' => $data
        ];
    }

    /**
     * @inheritDoc
     */
    public function getMeta()
    {
        $meta = parent::getMeta();
        $id = $this->request->getParam('id');

        if ($id) {
            $meta[$id]['arguments']['data']['config']['visible'] = true;
        } else {
            $meta['convert_now']['arguments']['data']['config']['visible'] = true;
        }

        return $meta;
    }
}