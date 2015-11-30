<?php

namespace ESG\Panther\Service;

class VbDataTransferSupplierProductService extends VbDataTransferService
{
    public function processVbData($feed)
    {
        $xml_vb = simplexml_load_string($feed);
        unset($xml_vb);

        $task_id = $xml_vb->attributes()->task_id;

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<products task_id="'.$task_id.'">';

        foreach ($xml_vb->product as $sp) {
            try {
                $master_sku = (string) $product->master_sku;
                $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);

                if (empty($sku)) {
                    $xml[] = '<product>';
                    $xml[] = '<prod_sku>' . (string) $sp->prod_sku . '</prod_sku>';
                    $xml[] = '<supplier_id>' . (string) $sp->supplier_id . '</supplier_id>';
                    $xml[] = '<master_sku>' . (string) $sp->master_sku . '</master_sku>';
                    $xml[] = '<status>2</status>';
                    $xml[] = '<is_error>' . (string) $sp->is_error . '</is_error>';
                    $xml[] = '<reason>No SKU mapping</reason>';
                    $xml[] = '</product>';
                    continue;
                }

                $sp_obj = $this->getDao('SupplierProd')->get(['prod_sku' => $sku, 'order_default' => 1]);

                if ((string) $sp_obj) {
                    $reason = 'update';
                    $this->getService('SupplierProd')->updateSupplierProd($sp_obj, $sp);
                    if ($this->getDao('SupplierProd')->update($sp_obj)) {
                        $process_status = 5;
                    } else {
                        $process_status = 3;
                    }
                } else {
                    $reason = 'insert';
                    $sp_obj = $this->getService('SupplierProd')->createNewSupplierProd($sku, $sp);
                    if ($this->getDao('SupplierProd')->insert($sp_obj)) {
                        $process_status = 5;
                    } else {
                        $process_status = 3;
                    }
                }

                $xml[] = '<product>';
                $xml[] = '<prod_sku>' . (string) $sp->prod_sku . '</prod_sku>';
                $xml[] = '<supplier_id>' . (string) $sp->supplier_id . '</supplier_id>';
                $xml[] = '<master_sku>' . (string) $sp->master_sku . '</master_sku>';
                $xml[] = '<status>' . $process_status . '</status>';
                $xml[] = '<is_error>' . (string) $sp->is_error . '</is_error>';
                $xml[] = '<reason>' . $reason . '</reason>';
                $xml[] = '</product>';
            } catch (Exception $e) {
                $xml[] = '<product>';
                $xml[] = '<prod_sku>' . (string) $sp->prod_sku . '</prod_sku>';
                $xml[] = '<supplier_id>' . (string) $sp->supplier_id . '</supplier_id>';
                $xml[] = '<master_sku>' . (string) $sp->master_sku . '</master_sku>';
                $xml[] = '<status>4</status>';
                $xml[] = '<is_error>' . (string) $sp->is_error . '</is_error>';
                $xml[] = '<reason>' . $e->getMessage() . '</reason>';
                $xml[] = '</product>';
            }
        }
        $xml[] = '</products>';
        $return_feed = implode("\n", $xml);

        return $return_feed;
    }
}
