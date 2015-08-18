<?php

class Pricing_tool_amfr_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/product_service');
        $this->load->library('service/price_service');
        $this->load->library('service/inventory_service');
        $this->load->library('service/shiptype_service');
        $this->load->library('service/warehouse_service');
        $this->load->library('service/amfr_price_service');
        $this->load->library('service/currency_service');
        $this->load->library('service/so_service');
        $this->load->library('service/product_note_service');
        $this->load->library('service/freight_cat_service');
    }

    public function __autoload()
    {
        $this->price_service->get_dao()->include_vo();
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, array("num_rows" => 1));
    }

    public function get_prod($sku = "")
    {
        if ($sku != "") {
            return $this->product_service->get_dao()->get(array("sku" => $sku));
        } else {
            return $this->product_service->get_dao()->get();
        }
    }

    public function __autoload_product_vo()
    {
        $this->product_service->get_dao()->include_vo();
    }

    public function add($obj)
    {
        return $this->price_service->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->price_service->get_dao()->update($obj);
    }

    public function get_price_obj($where = array())
    {
        if (empty($where)) {
            return $this->price_service->get_dao()->get();
        } else {
            return $this->price_service->get_dao()->get($where);
        }
    }

    public function get_shiptype_list($where = array())
    {
        return $this->shiptype_service->get_dao()->get_list($where);
    }

    public function get_product_cost_dto($sku, $platform)
    {
        return $this->price_service->get_dao()->get_price_cost_dto($sku, $platform);
    }

    public function set_dto_ps($dto)
    {
        $this->amfr_price_service->set_dto($dto);
    }

    public function calc_profit_ps()
    {
        $this->amfr_price_service->calc_profit();
    }

    public function get_table_header_ps()
    {
        return $this->amfr_price_service->draw_table_header_row();
    }

    public function get_table_row_ps($default_st)
    {
        return $this->amfr_price_service->draw_table_row_for_pricing_tool($default_st);
    }

    public function get_table_row_ps_pg($default_st)
    {
        return $this->amfr_price_service->draw_table_row_for_pricing_tool_for_pg($default_st);
    }

    public function get_js_ps()
    {
        return $this->amfr_price_service->print_js_for_pricing_tool();
    }

    public function get_currency()
    {
        return $this->amfr_price_service->get_dto()->get_platform_currency_id();
    }

    public function update_product($obj)
    {
        return $this->product_service->get_dao()->update($obj);
    }

    public function get_currency_detail($id = "")
    {
        return $this->currency_service->get_dao()->get(array("id" => $id));
    }

    public function get_note($sku = "", $type = "")
    {
        if ($sku == "") {
            return $this->product_note_service->get_dao()->get();
        } else {
            return $this->product_note_service->get_dao()->get_note_with_author_name($type == "M" ? "AMFR" : "", $sku, $type);
        }
    }

    public function add_note($obj)
    {
        return $this->product_note_service->get_dao()->insert($obj);
    }

    public function get_inventory($where = array())
    {
        return $this->inventory_service->get_inventory($where);
    }

    public function get_quantity_in_orders($sku = "")
    {
        $ret[7] = $this->so_service->get_dao()->get_quantity_in_orders($sku, 7);
        $ret[30] = $this->so_service->get_dao()->get_quantity_in_orders($sku, 30);
        return $ret;
    }

    public function get_current_supplier($sku = "")
    {
        return $this->product_service->get_dao()->get_current_supplier($sku);
    }

    public function get_freight_cat($id = "")
    {
        return $this->freight_cat_service->get_dao()->get(array("id" => $id));
    }
}

?>