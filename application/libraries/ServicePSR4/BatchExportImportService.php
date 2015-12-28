<?php

namespace ESG\Panther\Service;

use League\Csv\Reader;
use League\Csv\Writer;

/**
* Batch export import
*/
class BatchExportImportService extends BaseService
{
    public function exportSkuPrice($where, $option)
    {
        $select_str = 'pr.platform_id, p.sku, sm.ext_sku, p.name, pr.vb_price, pr.price, pm.margin';
        $data = $this->getDao('Product')->getProductOverview($where, $option, $select_str, 'array');
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        // insert the CSV header
        $csv->insertOne(['Platform Id', 'SKU', 'Master SKU', 'Proudct Name', 'VB Price', 'Panther Price', 'Margin', 'Input New Price']);
        $csv->insertAll($data);
        $csv->output('product.csv');
        die;
    }

    public function importSkuPrice($file_path)
    {
        $csv = Reader::createFromPath($file_path);
        $csv->setOffset(1);     // ignore the header

        $data = $csv->fetch();
        foreach ($data as $row) {
            $platform_id = $row[0];
            $sku = $row[1];
            $master_sku = $row[2];
            $vb_price = $row[4];
            $old_panther_price = $row[5];
            $old_margin = $row[6];
            $require_selling_price = trim($row[7]) ?: $old_panther_price;

            $auto_price_json = json_decode($this->getService('Price')->getProfitMarginJson($platform_id, $sku));
            $auto_price = $auto_price_json->get_price;

            $require_price_json = json_decode($this->getService('Price')->getProfitMarginJson($platform_id, $sku, $require_selling_price));
            $new_margin = $require_price_json->get_margin;

            $this->getService('Price')->updateSkuPrice($platform_id, $sku, $require_selling_price);

            // $this->getService('PriceChangeFollow')->processPriceChange($sku, $platform_id);
        }
        die;
    }

    public function uploadClearanceSku($file_path)
    {
        $csv = Reader::createFromPath($file_path);
        $csv->setOffset(1);     // ignore the header

        $data = $csv->fetch();
        foreach ($data as $row) {
            $master_sku = $row[0];
            $website_qty = $row[2];
            if ($website_qty > 20) {
                $website_qty = 20;
            } elseif ($website_qty <= 0) {
                $fail_resson = 'FAIL: quantity invalid.';
                continue;
            }

            $prod_obj = $this->getDao('Product')->getProdByMasterSku($master_sku);

            if ($prod_obj) {
                $prod_obj->setClearance(1);
                $prod_obj->setWebsiteQuantity($website_qty);
                $prod_obj->setWebsiteStatus('I');

                if ($this->getDao('Product')->update($prod_obj) === false) {
                    $fail_resson = 'UPDATE FAILED';
                }
            }
        }
        die;
    }
}
