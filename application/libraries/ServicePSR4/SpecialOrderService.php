<?php
namespace ESG\Panther\Service;

class SpecialOrderService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addSoForSpecialOrder($post_data, $platform_id)
    {
        $client_id = $post_data["client"]["id"];

        if (empty($client_id)) {
            $_SESSION["NOTICE"] = "ERROR " . __LINE__. " no such client in db";
        } else {
            $client_obj = $this->getDao('Client')->get(["id"=>$client_id]);
        }

        if (empty($_SESSION["NOTICE"])) {
            $item_arr = [];
            $soi_price = 0;
            $soi_data = $post_data["soi"];
            if ($soi_data) {
                foreach ($soi_data as $rskey=>$rsvalue) {
                    if (!empty($soi_data[$rskey]["sku"])) {
                        $sku = $soi_data[$rskey]["sku"];
                        $qty = $soi_data[$rskey]["qty"];

                        $price = sprintf("%.2f", $soi_data[$rskey]["price"]);

                        if (isset($item_arr[$sku][$price])) {
                            $item_arr[$sku][$price] += $qty;
                        } else {
                            $item_arr[$sku][$price] = $qty;
                        }

                        $soi_price += $qty * $price;
                    }
                }
            }
            unset($post_data["soi"]);
            $post_data["soi"] = $item_arr;

            $vars = $post_data;
            $parent_so_obj = $this->getDao('So')->get(["so_no" => $vars["parent_so_no"]]);
            if ($parent_so_obj->getSplitSoGroup()) {
                if ($split_parent_so_no = $parent_so_obj->getSplitSoGroup())
                {
                    $vars["parent_so_no"]  = $split_parent_so_no;
                    $vars["split_so_group"]  = "";
                }
            }

            $vars["client"] = $client_obj;
            $vars["platform_id"] = $platform_id;
            $vars["biz_type"] = "special";
            $vars["special"] = $special;
            $vars["soi_price"] = $soi_price;
            $vars["vat_exempt"] = $vars['vat_exempt'];
            $vars["customized_delivery"] = $vars['delivery_charge'];

            $_SESSION["NOTICE"] =  "Here is POST_data_to_so code";
            /*

            Here is POST_data_to_so code
            $this->sc['So']->cart_to_so($vars);
            // redirect($_SESSION["LISTPAGE"]);

            */
        }
    }

    public function processDataForOnHold($post_data)
    {
        if ($so_obj = $this->getDao('So')->get(["so_no"=>$post_data['so_no']])) {

            if ($post_data['status'] == 2) {
                $platform_obj = $this->getDao('PlatformBizVar')->get(["selling_platform_id"=>$so_obj->getPlatformId()]);
                $so_obj->setExpectDeliveryDate(date("Y-m-d H:i:s", time()+$platform_obj->getLatencyInStock()*86400));
            }

            $so_obj->setStatus($post_data['status']);
            $so_obj->setHoldStatus($post_data['hold_status']);

            if ($is_aps_payment_page) {
                $so_obj->setTxnId(trim( $post_data['txn_id']));
            }

            if ($this->getDao('So')->update($so_obj)) {
                if (!$is_aps_payment_page) {

                    if ($post_data['hold_status'] == 3) {
                        $this->getService('So')->sendNotificationToCs($so_obj);
                    }

                } else {

                    $ps_obj = $this->getDao('SoPaymentStatus')->get(["so_no"=>$so_obj->getSoNo()]);

                    if (($ps_obj !== FALSE) && ($ps_obj)) {
                        $action = "update";
                    } else {
                        $action = "insert";
                        $sops_vo = $this->getDao('SoPaymentStatus')->get();
                        $ps_obj = clone $sops_vo;
                        $ps_obj->setSoNo($so_obj->getSoNo());
                    }

                    $ps_obj->setPaymentGatewayId($post_data['payment_gateway']);
                    $ps_obj->setPaymentStatus("S");

                    if ($post_data['payment_gateway'] == "paypal") {
                        $ps_obj->setPayToAccount($post_data['pay_to_account']);
                    }

                    $update_result = $this->getDao('SoPaymentStatus')->$action($ps_obj);

                    if ($update_reuslt === FALSE) {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->getDao('SoPaymentStatus')->db->display_error();
                    }

                }
                redirect($_SESSION["LISTPAGE"]);
            }
        } else {
            $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->getDao('So')->db->display_error();
        }
    }

    public function processDataForPending($post_data)
    {
        if ($post_data['type']) {
            if ($so_obj = $this->getDao('So')->get(["so_no"=>$post_data['so_no']])) {
                switch ($post_data['type']) {
                    case "b":
                        $so_obj->setStatus('1');
                        break;
                    case "c":
                        $so_obj->setHoldStatus('1');
                        break;
                    case "p":
                        if (!check_app_feature_access_right($this->getAppId(), "ORD001102_process_order")) {
                            show_error("Access Denied!");
                        }
                        $so_obj->setStatus('3');   // marked as fulfilled/creditchecked
                        break;
                    case "s":
                        if (!check_app_feature_access_right($this->getAppId(), "ORD001102_process_order")) {
                            show_error("Access Denied!");
                        }
                        $so_obj->setStatus('6');   // marked as shipped
                        break;
                }

                if (!$this->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->display_error();
                } else {
                    if($post_data['type'] == 's' || $post_data['type'] == 'p') {
                        $perm_hold_result = $this->getService('So')->permanentHoldParentForAps($so_obj);
                        if ($perm_hold_result["status"] === false) {
                            $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$perm_hold_result["error_message"] ;
                        } else {
                            if (isset($perm_hold_result['update_message'])) {
                                $_SESSION["NOTICE"] = $perm_hold_result['update_message'];
                            }
                        }
                    }

                    // send notification email to client
                    if ($post_data['type'] == 'p') {
                        if ($so_ext_obj = $this->getDao('SoExtend')->get(["so_no" => $so_obj->getSoNo()])) {
                            if ( ($so_ext_obj->getOrderReason() == 19) || ($so_ext_obj->getOrderReason() == 22) ) {
                                $this->getService('So')->sendApsOrderClientNotificationEmail($so_obj);
                            }
                        }
                    }
                    redirect($_SESSION["LISTPAGE"]);
                }
            } else {
                $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->display_error();
            }
        }
    }

    public function findAllSoByClientId($client_id) {
        $where = ["status >=" => 3, "client_id" => $client_id];
        $option = ["limit" => -1, "orderby" => "so_no asc"];
        if ($so_list = $this->getDao('So')->getList($where, $option)) {
            $so_arr = [];
            foreach($so_list as $so)
            {
                $new_so = [];
                $new_so["so_no"] = $so->getSoNo();
                $new_so["order_create_date"] = $so->getOrderCreateDate();
                $new_so["status"] = $so->getStatus();
                $new_so["amount"] = $so->getAmount();
                $new_so["refund_status"] = $so->getRefundStatus();
                $new_so["currency"] = $so->getCurrencyId();
                $new_so["hold_status"] = $so->getHoldStatus();

                $new_so["split_level"] = $new_so["is_split_child"] = "";
                if($so->getHoldStatus() == 15)
                {
                    $new_so["split_level"] = "";
                    $new_so["is_split_child"] = "0";
                }
                if(($so->getHoldStatus() != 15) && ($so->getSplitSoGroup() != ''))
                {
                    # this so is a child of split order
                    $new_so["split_level"] = "(split_so_group: ".$so->getSplitSoGroup()." )";
                    $new_so["is_split_child"] = "1";
                }

                array_push($so_arr, $new_so);
            }

            return $so_arr;
        }

        return false;
    }
}
