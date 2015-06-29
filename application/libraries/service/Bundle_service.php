<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Bundle_service extends Base_service
{
    private $class_factory_service;
    private $price_service;

    public function __construct()
    {
        parent::__construct();
        //include_once(APPPATH . 'helpers/string_helper.php');
        include_once(APPPATH . 'libraries/dao/Bundle_dao.php');
        $this->set_dao(new Bundle_dao());
        include_once(APPPATH . 'libraries/service/Class_factory_service.php');
        $this->set_class_factory_service(new Class_factory_service());
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_service(new Price_service());
        include_once(APPPATH . 'libraries/service/Product_service.php');
        $this->set_product_service(new Product_service());
    }

    public function get_avail_prod_bundle_list_w_price($sku, $platform_id='WSGB', $lang_id)
    {
        $bundle_list = $this->get_dao()->get_avail_prod_bundle_list($sku, $platform_id, $lang_id);
        foreach ($bundle_list as $rskey=>$bundle)
        {
            $where["platform_id"] = $platform_id;
            $where["prod_sku"] = $bundle->get_prod_sku();

            $total = 0;

            if ($objlist = $this->price_service->get_dao()->get_items_w_price($where))
            {
                foreach ($objlist as $obj)
                {
                    if (($cur_price = $this->price_service->get_item_price($obj)) !== FALSE)
                    {
                        $total += $cur_price;
                    }
                }
                $total = number_format($total, 2, ".", "");
                $bundle->set_total_price($total);
            }
        }
        return $bundle_list;
    }

    public function set_class_factory_service($serv)
    {
        $this->class_factory_service = $serv;
    }

    public function get_class_factory_service()
    {
        return $this->class_factory_service;
    }

    public function set_price_service($serv)
    {
        $this->price_service = $serv;
    }

    public function get_price_service()
    {
        return $this->price_service;
    }

    public function set_product_service($serv)
    {
        $this->product_service = $serv;
    }

    public function get_product_service()
    {
        return $this->product_service;
    }

    public function get_main_sku($sku)
    {
        $obj = $this->get_dao()->get(array("prod_sku"=>$sku,"component_order <"=>"1"));
        if($obj === FALSE)
        {
            return $obj;
        }
        else
        {
            if($obj)
            {
                return $obj->get_component_sku();
            }
            else
            {
                return $sku;
            }
        }
    }

    public function get_bundle_component_sku($bundle_sku)
    {
        return $this->get_dao()->get_bundle_component_sku($bundle_sku);
    }

    public function check_bundle($sku)
    {
        if($this->get_dao()->get(array('prod_sku'=>$sku)))
        {
            return TRUE;
        }
        return FALSE;
    }

    public function get_bundle_listing_info($sku, $platform_id)
    {
        $this->include_dto('listing_info_dto');
        $obj = new listing_info_dto();

        $bundle_obj = $this->get_product_service()->get(array('sku'=>$sku));
        $obj->set_platform_id($platform_id);
        $obj->set_sku($sku);
        $obj->set_prod_name($bundle_obj->get_name());
        if($obj)
        {
            $price = 0;
            $first = TRUE;
            $status_arr = array('L'=>0, 'A'=>1, 'P'=>2, 'N'=>3);
            $comp_sku_arr = $this->get_bundle_component_sku($sku);
            foreach($comp_sku_arr AS $comp_arr)
            {
                $comp_obj = $this->get_price_service()->get_listing_info($comp_arr, $platform_id);
                $price += $comp_obj->get_price();
                if($first)
                {
                    $obj->set_platform_id($comp_obj->get_platform_id());
                    $obj->set_currency_id($comp_obj->get_currency_id());
                    $qty = $comp_obj->get_qty();
                    $status = $comp_obj->get_status();
                    $first = FALSE;
                }
                else
                {
                    if($comp_obj->get_qty() < $qty)
                    {
                        $qty = $comp_obj->get_qty();
                    }
                    if($status_arr[$status] < $status_arr[$comp_obj->get_status()])
                    {
                        $status = $comp_obj->get_status();
                    }
                }
            }
        }
        $price = round(($price * (100 - $bundle_obj->get_discount())) / 100, 2);
        $obj->set_price($price);
        $obj->set_qty($qty);
        $obj->set_status($status);

        return $obj;

    }
}

/* End of file bundle_service.php */
/* Location: ./system/application/libraries/service/Bundle_service.php */
