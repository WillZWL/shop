<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Deliverytime_service extends Base_service
{
    private $product_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Selling_platform_service.php");
        $this->set_selling_platform_service(new Selling_platform_service());

        include_once(APPPATH . "libraries/dao/Delivery_time_dao.php");
        $this->set_dao(new Delivery_time_dao());
        include_once(APPPATH . "libraries/dao/Product_dao.php");
        $this->set_product_dao(new Product_dao());
    }

    private function set_selling_platform_service(Base_service $value)
    {
        $this->selling_platform_service = $value;
    }

    private function set_product_dao(Base_dao $value)
    {
        $this->product_dao = $value;
    }

    public function get_deliverytime_list()
    {
        return $this->get_dao()->get_deliverytime_list();
    }

    public function get_delivery_scenario_list()
    {
        return $this->get_dao()->get_delivery_scenario_list();
    }

    public function get_deliverytime_obj($ctry_id, $scenarioid)
    {
        return $this->get_dao()->get_deliverytime_obj($ctry_id, $scenarioid);
    }

    public function bulk_update_delivery_scenario($platform_id, $update_list)
    {
        $error_msg = "";
        if ($update_list && $platform_id) {
            // $update_list must be in format of 'sku1','sku2','sku3',...
            foreach ($update_list as $scenarioid => $sku_list) {
                $sku_list = trim($sku_list, ',');
                $result = $this->get_dao()->bulk_update_delivery_scenario_by_platform($platform_id, $scenarioid, $sku_list);

                if ($result === false) {
                    $error_msg .= __FILE__ . " LINE: " . __LINE__ . " DB error: " . $this->db->_error_message() . "\n Unable to update platform_id<$platform_id>, scenarioid<$scenarioid> for SKU LIST: \n$sku_list <hr></hr>\n";
                }
            }

        }

        if ($error_msg == "") {
            return TRUE;
        } else {
            $this->send_notification_email("update_fail", $error_msg);
            $this->error_msg = $error_msg;
            return FALSE;
        }


    }

    private function get_selling_platform_service()
    {
        return $this->selling_platform_service;
    }

    private function get_product_dao()
    {
        return $this->product_dao;
    }


}


