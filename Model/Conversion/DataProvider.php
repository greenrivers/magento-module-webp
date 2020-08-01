<?php

namespace Unexpected\Webp\Model\Conversion;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
//    public function __construct($name, $primaryFieldName, $requestFieldName, array $meta = [], array $data = [])
//    {
//        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
//    }

    public function getData()
    {
        return [
            '' => [
                'document_set_id_3' => 'test3',
                'myCheckbox_test' => true
            ],
        ];
    }

    public function getMeta()
    {
        $meta = parent::getMeta();

//        $meta['cron']['arguments']['data']['config']['visible'] = 0;
        return $meta;
    }
}