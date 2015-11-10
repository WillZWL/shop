<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Db_cache_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
    }

    public function save_xml_skype_feed($data = array())
    {
        include_once(APPPATH . 'libraries/service/Db_cache_product_feed_service.php');
        $serv = new Db_cache_product_feed_service();

        $serv->save_xml_skype_feed($data);
    }

    public function get_xml_skype_feed($data = array())
    {
        include_once(APPPATH . 'libraries/service/Db_cache_product_feed_service.php');
        $cache_serv = new Db_cache_product_feed_service();

        include_once(APPPATH . 'libraries/service/Product_service.php');
        $prod_serv = new Product_service();

        $cache_data = $cache_serv->get_xml_skype_feed($data);

        if ($cache_data) {
            $sku = explode('/', $data['sku']);

            // Collect the quantity of the item
            $prod_obj = $prod_serv->get(array('sku' => $sku[0]));

            // Special code to include dto.  It is not a proper way
            $prod_serv->get_dao()->include_dto('Product_cost_dto');
            $obj = new Product_cost_dto();

            include_once(APPPATH . "helpers/object_helper.php");

            set_value($obj, $prod_obj);
            $obj->set_prod_status($prod_obj->get_status());

            $cache_data['obj'] = $obj;
        }

        return $cache_data;
    }
}

