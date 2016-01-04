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
        $select_str = "pr.platform_id, p.sku, sm.ext_sku, p.name, pr.vb_price, pr.price, pm.margin, ''";
        $data = $this->getDao('Product')->getProductOverview($where, $option, $select_str, 'array');
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        // insert the CSV header
        $csv->insertOne(['Platform Id', 'SKU', 'Master SKU', 'Proudct Name', 'VB Price', 'Panther Price', 'Panther Margin', 'Input New Price']);
        $csv->insertAll($data);
        $csv->output('product.csv');
        exit;
    }

    public function importSkuPrice($file_path)
    {
        $csv = Reader::createFromPath($file_path);
        $csv->setOffset(1);     // ignore the header

        $result_csv = Writer::createFromFileObject(new \SplTempFileObject());
        // set reuslt csv file header, add 3 new columns.
        $result_csv->insertOne([
            'Platform Id',
            'SKU',
            'Master SKU',
            'Proudct Name',
            'VB Price',
            'Panther Price',
            'Panther Margin',
            'Input New Price',
            'New Panther Price',        // $row[8]
            'New Panther Margin',       // $row[9]
            'Failed Reason'             // $row[10]
            ]);

        $data = $csv->fetch();
        foreach ($data as $row) {
            $platform_id = $row[0];
            $sku = $row[1];
            $master_sku = $row[2];
            $vb_price = $row[4];
            $old_panther_price = $row[5];
            $old_margin = $row[6];
            $require_selling_price = trim($row[7]);

            $row[8] = '';
            $row[9] = '';
            $row[10] = '';

            if (empty($platform_id)) {
                $row[10] = 'No platform id provided.';
                $result_csv->insertOne($row);
                continue;
            }

            if (empty($sku)) {
                $row[10] = 'No SKU provided.';
                $result_csv->insertOne($row);
                continue;
            }

            if (empty($master_sku)) {
                $row[10] = 'No master sku provided.';
                $result_csv->insertOne($row);
                continue;
            }

            if (!is_numeric($require_selling_price) || $require_selling_price < 0) {
                $row[10] = "New input price is not acceptable.";
                $result_csv->insertOne($row);
                continue;
            }

            $require_price_json = json_decode($this->getService('Price')->getProfitMarginJson($platform_id, $sku, $require_selling_price));

            if (isset($require_price_json->error)) {
                $row[10] = "Unable to get profit margin.";
                $result_csv->insertOne($row);
                continue;
            }

            $new_margin = $require_price_json->get_margin;
            $affected_rows = $this->getService('Price')->updateSkuPrice($platform_id, $sku, $require_selling_price);

            // TODO
            // insert new margin to price_margin table.

            if ($affected_rows > 0) {
                $row[8] = $require_selling_price;
                $row[9] = $new_margin;
            }

            $result_csv->insertOne($row);
            // $this->getService('PriceChangeFollow')->processPriceChange($sku, $platform_id);
        }

        $result_csv->output('feedback.csv');
    }

    public function uploadClearanceSku($file_path)
    {
        $csv = Reader::createFromPath($file_path);
        $csv->setOffset(1);     // ignore the header

        $result_csv = Writer::createFromFileObject(new \SplTempFileObject());

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
