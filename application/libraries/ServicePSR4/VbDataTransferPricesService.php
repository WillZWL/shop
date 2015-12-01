<?php

namespace ESG\Panther\Service;

class VbDataTransferPricesService extends VbDataTransferService
{
    /*******************************************************************
    *   processVbData, get the VB data to save it in the price table
    ********************************************************************/
    public function processVbData($feed)
    {
        //Read the data sent from VB
        $xml_vb = simplexml_load_string($feed);

        $task_id = $xml_vb->attributes();

        //Create return xml string
        $xml = array();
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<prices task_id="'.$task_id.'">';

        foreach ($xml_vb->price as $vb_price_obj) {
            //Get the master sku to search the corresponding sku in atomv2 database
            $master_sku = (string) $vb_price_obj->master_sku;
            $platform_id = (string) $vb_price_obj->platform_id;
            $required_selling_price = floatval($vb_price_obj->prod_price);
            $VB_price = floatval($vb_price_obj->prod_price); //update the VB price field in the atomv2 price table

            $sku = $this->getService('SkuMapping')->getLocalSku($master_sku);
            if (empty($sku)) {
                $xml[] = '<price>';
                $xml[] = '<sku>'.$vb_price_obj->sku.'</sku>';
                $xml[] = '<master_sku>'.$vb_price_obj->master_sku.'</master_sku>';
                $xml[] = '<platform_id>'.$vb_price_obj->platform_id.'</platform_id>';
                $xml[] = '<status>2</status>'; //no sku mapping
                $xml[] = '<is_error>'.$vb_price_obj->is_error.'</is_error>';
                $xml[] = '<reason>No SKU mapping</reason>';
                $xml[] = '</price>';
                continue;
            }

            $prod_obj = $this->getDao('Product')->get(['sku' => $sku]);
            if (!$prod_obj) {
                $xml[] = '<price>';
                $xml[] = '<sku>'.$vb_price_obj->sku.'</sku>';
                $xml[] = '<master_sku>'.$vb_price_obj->master_sku.'</master_sku>';
                $xml[] = '<platform_id>'.$vb_price_obj->platform_id.'</platform_id>';
                $xml[] = '<status>2</status>'; //no sku mapping
                $xml[] = '<is_error>'.$vb_price_obj->is_error.'</is_error>';
                $xml[] = '<reason>No this product</reason>';
                $xml[] = '</price>';
                continue;
            }

            try {
                $this->applyPriceRule($vb_price_obj);
                $price_obj = $this->getDao('Price')->get(['sku' => $sku, 'platform_id' => $platform_id]);

                if ($price_obj) {
                    $this->getService('Price')->updatePrice($price_obj, $vb_price_obj);
                    $action = 'update';
                } else {
                    $price_obj = $this->getService('Price')->createNewPrice($sku, $vb_price_obj);
                    $action = 'insert';
                }

                if (($prod_obj->getClearance() == 0)) {
                    // TODO:
                    // need to finish getTrailCalcuMargin function
                    // $new_margin = $this->getService('Price')->getTrailCalcuProfitMargin($price_obj);
                    $new_margin = -1;
                    if ($new_margin < $minimun_margin) {
                        $reason = "Error in  margin";
                        $result_status = 6;
                    } else {
                        $this->getDao('Price')->$action($price_obj);
                        $result_status = 5;
                    }
                } else {
                    // clearance, no need check minimun margin
                    $this->getDao('Price')->$action($price_obj);
                    $result_status = 5;
                }

                $xml[] = '<price>';
                $xml[] = '<sku>'.$vb_price_obj->sku.'</sku>';
                $xml[] = '<master_sku>'.$vb_price_obj->master_sku.'</master_sku>';
                $xml[] = '<platform_id>'.$vb_price_obj->platform_id.'</platform_id>';
                $xml[] = '<status>'.$result_status.'</status>'; //updated
                $xml[] = '<is_error>'.$vb_price_obj->is_error.'</is_error>';
                $xml[] = '<reason>'.$reason.' - '.$price_change.'</reason>';
                $xml[] = '</price>';
            } catch (Exception $e) {
                $xml[] = '<price>';
                $xml[] = '<sku>'.$vb_price_obj->sku.'</sku>';
                $xml[] = '<master_sku>'.$vb_price_obj->master_sku.'</master_sku>';
                $xml[] = '<platform_id>'.$vb_price_obj->platform_id.'</platform_id>';
                $xml[] = '<status>4</status>'; //error
                $xml[] = '<is_error>'.$vb_price_obj->is_error.'</is_error>';
                $xml[] = '<reason>'.$e->getMessage().'</reason>';
                $xml[] = '</price>';
            }
        }

        $xml[] = '</prices>';
        $return_feed = implode("\n", $xml);

        return $return_feed;
    }

    public function applyPriceRule(&$vb_price_obj)
    {
        $required_selling_price = floatval($vb_price_obj->prod_price);
        $platform_id = (string) $vb_price_obj->platform_id;

        //get pricing rules filter
        $where = [];

        $dayofweek = date('w');
        switch ($dayofweek) {
            case 0: //sunday
                $where['sunday'] = '1';
                break;
            case 1:
                $where['monday'] = '1';
                break;
            case 2:
                $where['tuesday'] = '1';
                break;
            case 3:
                $where['wednesday'] = '1';
                break;
            case 4:
                $where['thursday'] = '1';
                break;
            case 5:
                $where['friday'] = '1';
                break;
            case 6:
                $where['saturday'] = '1';
                break;
        }

        $where['pbv.selling_platform_id'] = $platform_id;
        $where[$required_selling_price.' between pr.range_min and pr.range_max'] = null;
        $option = ['limit' => 1];

        $pricing_rule_obj = $this->getService('PricingRules')->getPricingRulesByPlatform($where, $option);

        //if exist, apply the pricing rules to the VB price
        //if dont exist rules for the VB price, we use directly the VB price
        if ($pricing_rule_obj) {
            $rule_type = $pricing_rule_obj->getMarkUpType();
            $rule_markup = $pricing_rule_obj->getMarkUpValue();
            $min_margin = $pricing_rule_obj->getMinMargin();

            if ($rule_type == 'A') {
                $required_selling_price += floatval($rule_markup * 1.0);
            } elseif ($rule_type == 'P') {
                $required_selling_price = $required_selling_price + ($required_selling_price * $rule_markup);
            }
        }

        $vb_price_obj->required_selling_price = $required_selling_price;
    }
}
