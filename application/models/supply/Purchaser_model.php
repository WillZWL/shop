<?php

class Purchaser_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/brand_service');
        $this->load->library('service/product_service');
        $this->load->library('service/supplier_service');
        $this->load->library('service/currency_service');
        $this->load->library('service/product_note_service');
        $this->load->library('service/exchange_rate_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/inventory_service');
        $this->load->library('service/shiptype_service');
        $this->load->library('service/class_factory_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/sku_mapping_service');
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name_for_purchaser_list($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where = array())
    {
        return $this->product_service->get_dao()->get_list_w_name_for_purchaser_list($where, array("num_rows" => 1));
    }

    public function get_supplier_prod_list_w_name($where = array(), $option = array())
    {
        return $this->supplier_service->get_sp_dao()->get_supplier_prod_list_w_name($where, $option, "Supplier_prod_w_name_dto");
    }

    public function get_prod_st_profit($sku)
    {
        if ($objlist = $this->shiptype_service->get_dao()->get_product_shiptype(array("sku" => $sku, "platform_id LIKE 'WEB%'" => NULL))) {
            $data["low_profit"] = $data["max_cost"] = array();
            foreach ($objlist as $obj) {
                $price_service = $this->class_factory_service->get_platform_price_service($obj->get_platform_id());
                $price_service->calc_logistic_cost($obj);
                $price_service->calculate_profit($obj);

                if (empty($data["low_profit"])) {
                    $data["low_profit"] = $obj;
                } elseif ($obj->get_profit() < $data["low_profit"]->get_profit()) {
                    $data["low_profit"] = $obj;
                }
            }
            return $data;
        }
        return FALSE;
    }

    public function get_list($service, $dao, $where = array(), $option = array())
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->get_list($where, $option);
    }

    public function ge_list_total($service, $dao, $where = array())
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->get_num_rows($where);
    }

    public function get_purchaser_list($where = array(), $option = array())
    {
        return $this->purchaser_service->get_dao()->get_list_w_name($where, $option);
    }

    public function get_purchaser_list_total($where = array())
    {
        return $this->purchaser_service->get_dao()->get_list_w_name($where, array("num_rows" => 1));
    }

    public function get($service, $dao, $where = array())
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->get($where);
    }

    public function update($service, $dao, $obj)
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->update($obj);
    }

    public function add($service, $dao, $obj)
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->insert($obj);
    }

    public function delete($service, $dao, $where)
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->q_delete($where);
    }

    public function include_vo($service, $dao)
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->include_vo();
    }

    public function include_dto($service, $dao, $dto)
    {
        $service = $service . "_service";
        $dao = "get_" . $dao;
        return $this->$service->$dao()->include_dto($dto);
    }

    public function get_note($sku = "", $type = "")
    {
        return $this->product_note_service->get_dao()->get_note_with_author_name("", $sku, $type);
    }

    public function get_prod_inventory($where)
    {
        return $this->inventory_service->get_vpi_dao()->get($where);
    }

    public function get_master_sku($where = array())
    {
        return $this->sku_mapping_service->get_master_sku($where);
    }

}
