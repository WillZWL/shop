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
                    $data = json_decode($this->getService('Price')->getProfitMarginJson($platform_id, $sku, $price_obj->getPrice()));
                    $new_margin = $data->get_margin;

                    $pricing_rule_obj = $this->getPriceRule($vb_price_obj);

                    if ($pricing_rule_obj) {
                        $min_margin = $pricing_rule_obj->getMinMargin();

                        if ($new_margin < $minimun_margin) {
                            $reason = "Error in margin";
                            $result_status = 6;

                            //In the case of a new (insert) price whose new margin is less than the minimum margin in the rules table
                            //We will insert this price record with the original VB price (not the price after applying the rules)
                            if ($action == 'update') {
                                continue;
                            }
                            else
                            {
                                $price_obj->setPrice($required_selling_price);
                            }
                        }
                    }
                    $this->getDao('Price')->$action($price_obj);
                    $result_status = 5;
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
        $return_feed = implode("", $xml);

        return $return_feed;
    }

    public function applyPriceRule(&$vb_price_obj)
    {
        $required_selling_price = (string) $vb_price_obj->prod_price;
        $pricing_rule_obj = $this->getPriceRule($vb_price_obj);
        if ($pricing_rule_obj) {
            $rule_type = $pricing_rule_obj->getMarkUpType();
            $rule_markup = $pricing_rule_obj->getMarkUpValue();

            if ($rule_type == 'A') {
                $required_selling_price = (float)$required_selling_price + (float)$rule_markup;
            } elseif ($rule_type == 'P') {
                $required_selling_price = $required_selling_price + ($required_selling_price * $rule_markup);
            }
        }

        $vb_price_obj->required_selling_price = $required_selling_price;
    }

    public function getPriceRule($vb_price_obj)
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

        return $pricing_rule_obj;
    }
}
