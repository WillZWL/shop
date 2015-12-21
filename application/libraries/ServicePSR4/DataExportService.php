<?php

namespace ESG\Panther\Service;

use League\Csv\Reader;
use League\Csv\Writer;

/**
* Data Export
*/
class DataExportService extends BaseService
{
    public function exportSku($where, $option)
    {
        $select_str = 'pr.platform_id, p.sku, sm.ext_sku, p.name, pr.vb_price, pr.price, pm.margin';
        $data = $this->getDao('Product')->getProductOverview($where, $option, $select_str, 'array');
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        // insert the CSV header
        $csv->insertOne(['Platform Id', 'SKU', 'Master SKU', 'Proudct Name', 'VB Price', 'Panther Price', 'Margin', 'Input New Price']);
        $csv->insertAll($data);
        $csv->output('product.csv');die;
    }

    public function importSkuPrice($file_path)
    {
        $csv = Reader::createFromPath($file_path);
        $csv->setOffset(1);
    }
}
