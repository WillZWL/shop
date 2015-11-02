<?php
namespace ESG\Panther\Service;

class ClwmsService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->soService = new SoService;
    }

    public function getSalesOrder($where = [], $option = [])
    {
        set_time_limit(0);

        $so_list = $this->getDao('So')->getSalesOrder($where, $option);

        if ($so_list !== FALSE) {
            $xml = [];
            $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml[] = '<orders>';

            $current_so_no = '';

            foreach ($so_list as $so) {
                if ($so["status"] == 2) {
                    $refAmount = $so["amount"] * $so["rate"];
                    if (($refAmount >= 2000)
                        || ($so["sub_cat_id"] == 45)
                        || ($so["sub_cat_id"] == 46)
                        || ($so["sub_cat_id"] == 47)
                        || ($so["sub_cat_id"] == 360)
                        || (!(($refAmount < 1000) && ($so["sub_cat_id"] != 52)))
                    )
                        continue;
                }
                if ($current_so_no != $so['so_no']) {
                    if ($current_so_no != '') {
                        $xml[] = '</skus>';
                        $xml[] = '</order>';
                    }
                    $score = $so['score'];
                    if (is_null($score)) {
                        $score = $this->getService('So')->getPriorityScore($so['so_no']);
                    }
                    $xml[] = '<order>';
                    $xml[] = '<bundle/>';
                    $xml[] = '<retailer_order_reference>' . $so['so_no'] . '</retailer_order_reference>';
                    $xml[] = '<biz_type>' . $so['biz_type'] . '</biz_type>';
                    $xml[] = '<platform_id>' . $so['platform_id'] . '</platform_id>';
                    $xml[] = '<purchased_date>' . $so['order_create_date'] . '</purchased_date>';
                    $xml[] = '<name><![CDATA[' . $so['name'] . ']]></name>';
                    $xml[] = '<address><![CDATA[' . $so['delivery_address'] . ']]></address>';
                    $xml[] = '<postcode>' . $so['delivery_postcode'] . '</postcode>';
                    $xml[] = '<city><![CDATA[' . $so['delivery_city'] . ']]></city>';
                    $xml[] = '<state><![CDATA[' . $so['delivery_state'] . ']]></state>';
                    $xml[] = '<country>' . $so['delivery_country_id'] . '</country>';
                    $xml[] = '<score>' . $score . '</score>';
                    $xml[] = '<amount>';
                    $xml[] = '<currency>' . $so['currency_id'] . '</currency>';
                    $xml[] = '<delivery>' . $so['delivery_charge'] . '</delivery>';
                    $xml[] = '<total>' . $so['amount'] . '</total>';
                    $xml[] = '</amount>';
                    $xml[] = '<skus>';
                    $current_so_no = $so['so_no'];
                }
                $xml[] = '<sku>';
                $xml[] = '<price>' . $so['price'] . '</price>';
                $xml[] = '<item_cost>' . ($so['item_unit_cost'] * $so['qty']) . '</item_cost>';
                $xml[] = '<retailer_sku>' . $so['prod_sku'] . '</retailer_sku>';
                $xml[] = '<master_sku>' . strtoupper($so['ext_sku']) . '</master_sku>';
                $xml[] = '<merchant_sku>' . strtoupper($so['merchant_sku']) . '</merchant_sku>';
                $xml[] = '<quantity>' . $so['qty'] . '</quantity>';
                $xml[] = '<is_clearance>' . (($so['clearance']) ? 'TRUE' : 'FALSE') . '</is_clearance>';
                $xml[] = '<qtyallocated/>';
                $xml[] = '<sourcingcomment/>';
                $xml[] = '<hold>' . (($so['hold_status']) ? 'TRUE' : 'FALSE') . '</hold>';
                $xml[] = '<refund>' . (($so['refund_status']) ? 'TRUE' : 'FALSE') . '</refund>';
                $xml[] = '<rec_courier>' . $so['rec_courier'] . '</rec_courier>';
                $xml[] = '</sku>';
            }
            if ($current_so_no != '') {
                $xml[] = '</skus>';
                $xml[] = '</order>';
            }

            $xml[] = '</orders>';

            $feed = implode("\n", $xml);
            return $feed;
        }

        return FALSE;
    }
}