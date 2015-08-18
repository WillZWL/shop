<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Warranty_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Warranty_dao.php");
        $this->set_dao(new Warranty_dao());
        include_once(APPPATH . "libraries/service/Price_service.php");
        $this->set_price_srv(new Price_service());
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function get_warranty_by_sku($sku = "", $platform_id = "", $lang_id = "en")
    {
        if ($warranty_sku = $this->get_dao()->get_warranty_by_sku($sku, $platform_id)) {
            return $this->get_price_srv()->get_listing_info($warranty_sku, $platform_id, $lang_id);
        }
        return false;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_warranty_list_by_sku($sku = "", $platform_id = "", $lang_id = "en")
    {
        if ($warranty_list = $this->get_dao()->get_warranty_list_by_sku($sku, $platform_id, $lang_id)) {
            foreach ($warranty_list as $key => $val) {
                $temp[$key]['group_name'] = $val['group_name'];
                $temp[$key]['wlist'] = $this->get_price_srv()->get_listing_info_list($val['wlist'], $platform_id, $lang_id);
            }
            return $temp;
        }
        return false;
    }

}

?>