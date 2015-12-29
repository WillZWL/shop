<?php
namespace ESG\Panther\Service;

class ManualOrderService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addSoForManualOrder($post_data, $password, $platform_type, $platform_id)
    {
        $this->getService("SoFactory")->createSaleOrder();

        // if (empty($post_data["client"]["id"])) {
        //     if (!($client_obj = $this->getDao('Client')->get(["email" => $post_data["client"]["email"]]))) {
        //         $client_obj = $this->getDao('Client')->get();
        //         $post_data["client"]["password"] = $password;
        //         set_value($client_obj, $post_data["client"]);
        //         $client_obj->setMobile($post_data["client"]["mtel_1"] . $post_data["client"]["mtel_2"] . $post_data["client"]["mtel_3"]);

        //         if ($post_data["billaddr"] != 1) {
        //             $client_obj->setDelName($post_data["client"]["title"] . " " . $post_data["client"]["forename"] . " " . $post_data["client"]["surname"]);
        //             $client_obj->setDelCompany($post_data["client"]["companyname"]);
        //             $client_obj->setDelAddress1($post_data["client"]["address_1"]);
        //             $client_obj->setDelAddress2($post_data["client"]["address_2"]);
        //             $client_obj->setDelCity($post_data["client"]["city"]);
        //             $client_obj->setDelState($post_data["client"]["state"]);
        //             $client_obj->setDelCountryId($post_data["client"]["country_id"]);
        //             $client_obj->setDelMobile($post_data["client"]["mtel_1"] . $post_data["client"]["mtel_2"] . $post_data["client"]["mtel_3"]);
        //             $client_obj->setDelName($post_data["client"]["title"] . " " . $post_data["client"]["forename"] . " " . $post_data["client"]["surname"]);
        //         } else {
        //             $client_obj->setDelMobile($post_data["client"]["del_mtel_1"] . $post_data["client"]["del_mtel_2"] . $post_data["client"]["del_mtel_3"]);
        //             $client_obj->setDelName($post_data["client"]["del_title"] . " " . $post_data["client"]["del_forename"] . " " . $post_data["client"]["del_surname"]);
        //         }
        //         $client_obj->setPartySubscriber(0);
        //         $client_obj->setStatus(1);

        //         if ($client_obj = $this->getDao('Client')->insert($client_obj)) {
        //             $post_data["client"]["id"] = $client_obj->getId();
        //         } else {
        //             $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->getDao('Client')->db->_error_message();
        //         }
        //     } else {
        //         $post_data["client"]["id"] = $client_obj->getId();
        //         $post_data["client"]["password"] = $password;
        //         set_value($client_obj, $post_data["client"]);
        //         $client_obj->setMobile($post_data["client"]["mtel_1"] . $post_data["client"]["mtel_2"] . $post_data["client"]["mtel_3"]);
        //         if ($post_data["billaddr"] != 1) {
        //             $client_obj->setDelName($post_data["client"]["title"] . " " . $post_data["client"]["forename"] . " " . $post_data["client"]["surname"]);
        //             $client_obj->setDelCompany($post_data["client"]["companyname"]);
        //             $client_obj->setDelAddress1($post_data["client"]["address_1"]);
        //             $client_obj->setDelAddress2($post_data["client"]["address_2"]);
        //             $client_obj->setDelCity($post_data["client"]["city"]);
        //             $client_obj->setDelState($post_data["client"]["state"]);
        //             $client_obj->setDelCountryId($post_data["client"]["country_id"]);
        //             $client_obj->setDelMobile($post_data["client"]["mtel_1"] . $post_data["client"]["mtel_2"] . $post_data["client"]["mtel_3"]);
        //             $client_obj->setDelName($post_data["client"]["title"] . " " . $post_data["client"]["forename"] . " " . $post_data["client"]["surname"]);
        //         } else {
        //             $client_obj->setDelMobile($post_data["client"]["del_mtel_1"] . $post_data["client"]["del_mtel_2"] . $post_data["client"]["del_mtel_3"]);
        //             $client_obj->setDelName($post_data["client"]["del_title"] . " " . $post_data["client"]["del_forename"] . " " . $post_data["client"]["del_surname"]);
        //         }
        //         if ($this->getDao('Client')->update($client_obj) === FALSE) {
        //             $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->getDao('Client')->db->_error_message();
        //         }
        //     }
        // } else {
        //     $client_obj = $this->getDao('Client')->get(array("id" => $post_data["client"]["id"]));
        //     $post_data["client"]["password"] = $password;
        //     set_value($client_obj, $post_data["client"]);
        //     $client_obj->setMobile($post_data["client"]["mtel_1"] . $post_data["client"]["mtel_2"] . $post_data["client"]["mtel_3"]);
        //     if ($post_data["billaddr"] != 1) {
        //         $client_obj->setDelName($post_data["client"]["title"] . " " . $post_data["client"]["forename"] . " " . $post_data["client"]["surname"]);
        //         $client_obj->setDelCompany($post_data["client"]["companyname"]);
        //         $client_obj->setDelAddress1($post_data["client"]["address_1"]);
        //         $client_obj->setDelAddress2($post_data["client"]["address_2"]);
        //         $client_obj->setDelCity($post_data["client"]["city"]);
        //         $client_obj->setDelState($post_data["client"]["state"]);
        //         $client_obj->setDelCountryId($post_data["client"]["country_id"]);
        //         $client_obj->setDelMobile($post_data["client"]["mtel_1"] . $post_data["client"]["mtel_2"] . $post_data["client"]["mtel_3"]);
        //         $client_obj->setDelName($post_data["client"]["title"] . " " . $post_data["client"]["forename"] . " " . $post_data["client"]["surname"]);
        //     } else {
        //         $client_obj->setDelMobile($post_data["client"]["del_mtel_1"] . $post_data["client"]["del_mtel_2"] . $post_data["client"]["del_mtel_3"]);
        //         $client_obj->setDelName($post_data["client"]["del_title"] . " " . $post_data["client"]["del_forename"] . " " . $post_data["client"]["del_surname"]);
        //     }
        //     if ($this->getDao('Client')->update($client_obj) === FALSE) {
        //         $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->getDao('Client')->db->_error_message();
        //     }
        // }
        // if (empty($_SESSION["NOTICE"])) {
        //     $item_arr = [];
        //     $soi_price = 0;
        //     $soi_data = $post_data["soi"];
        //     if ($soi_data) {
        //         foreach ($soi_data as $rskey => $rsvalue) {
        //             if (!empty($soi_data[$rskey]["sku"])) {
        //                 $sku = $soi_data[$rskey]["sku"];
        //                 $qty = $soi_data[$rskey]["qty"];

        //                 $price = sprintf("%.2f", $soi_data[$rskey]["price"]);

        //                 if (isset($item_arr[$sku][$price])) {
        //                     $item_arr[$sku][$price] += $qty;
        //                 } else {
        //                     $item_arr[$sku][$price] = $qty;
        //                 }

        //                 $soi_price += $qty * $price;
        //             }
        //         }
        //     }
        //     unset($post_data["soi"]);
        //     $post_data["soi"] = $item_arr;

        //     $vars = $post_data;
        //     $vars["client"] = $client_obj;
        //     $vars["platform_id"] = $platform_id;
        //     $vars["platform_type"] = $platform_type;
        //     $vars["biz_type"] = "manual";
        //     $vars["soi_price"] = $soi_price;
        //     $vars["vat_exempt"] = $vars['vat_exempt'];
        //     $vars["customizedDelivery"] = $vars['delivery_charge'];
        //     //$vars["delivery"] = "STDPG";
        //     $vars["txn_id"] = $vars['txn_id'];
        //     $vars["platform_order_id"] = $vars['platform_order_id'];
        //     $vars["payment_date"] = $vars['payment_date'];
        //     $vars["payment_gateway"] = $vars['payment_gateway'];
        //     $pg_obj = $this->getDao('PaymentGateway')->get(["payment_gateway_id" => $vars['payment_gateway']]);

        //     $vars["pay_to_account"] = $pg_obj->getRefId();

        //     if (empty($_SESSION["NOTICE"])) {
        //         if ($so_obj = $this->getService('So')->cartToSo($vars)) {
        //             if ($platform_type == 'QOO10') {

        //                 $so_no = $so_obj->getSoNo();
        //                 $so_obj_updated = $this->getService('So')->processQoo10ManualOrders($so_no);

        //                 if ($so_obj_updated) {
        //                     $_SESSION["DISPLAY"] = [$so_obj_updated->getSoNo() . " Created Success", "success"];
        //                     redirect($_SESSION["LISTPAGE"]);
        //                 } else {
        //                     $_SESSION["NOTICE"] = "SO Service " . $_SESSION["NOTICE"];
        //                 }
        //             }

        //             $_SESSION["DISPLAY"] = [$so_obj->getSoNo() . " Created Success", "success"];
        //             redirect($_SESSION["LISTPAGE"]);
        //         } else {
        //             $_SESSION["NOTICE"] = "SO Service " . $_SESSION["NOTICE"];
        //         }
        //     }
        // }
    }

    public function processDataForOnHold($post_data)
    {
        if ($so_obj = $this->getDao('So')->get(["so_no"=>$post_data['so_no']])) {
            if ($post_data['status'] == 2) {
                $platform_obj = $this->getDao('PlatformBizVar')->get(["selling_platform_id"=>$so_obj->getPlatformId()]);
                $so_obj->setExpectDeliveryDate(date("Y-m-d H:i:s", time()+$platform_obj->getLatencyInStock()*86400));
            }
            $so_obj->setStatus($post_data['status']);
            if (!$this->getDao('So')->update($so_obj)) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->getDao('So')->db->display_error();
            } else {
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
                        $so_obj->setStatus('0');
                        break;
                    case "c":
                        $so_obj->setHoldStatus('1');
                        break;
                    case "p":
                        $so_obj->setStatus('3');
                        break;
                }
                if (!$this->getDao('So')->update($so_obj)) {
                    $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->display_error();
                } else {
                    redirect($_SESSION["LISTPAGE"]);
                }
            } else {
                $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->display_error();
            }
        }
    }
}