<?php

include_once "Base_service.php";

class Ra_product_service extends Base_service
{
    private $price_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . 'libraries/dao/Ra_product_dao.php');
        $this->set_dao(new Ra_product_dao());
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_service(new Price_service());
    }

    public function get_price_service()
    {
        return $this->price_service;
    }

    public function set_price_service($serv)
    {
        $this->price_service = $serv;
    }

    public function get_ra_product_w_group_name($where = array(), $option = array(), $lang_id = "en")
    {
        $data = array();
        $data['ra_list'] = array();
        $data['ra_group_list'] = array();

        if ($rs_list = $this->get_dao()->get_ra_product_w_group_name($where, $option, $lang_id)) {
            if (!$lang_id = $this->get_lang_id()) {
                $lang_id = 'en';
            }

            foreach ($rs_list as $rs) {
                if ($listing_info_dto = $this->price_service->get_listing_info($rs['ra_sku'], $where['platform_id'], $lang_id)) {
                    $data['ra_list'][$rs['ra_group_id']][] = $listing_info_dto;
                }

                $data['ra_group_list'][$rs['ra_group_id']] = $rs['group_name'];
            }
        }

        return $data;
    }

    public function get_ra_group_list_by_prod_sku($main_sku, $where = array(), $option = array())
    {
        $data = $this->get_dao()->get_ra_group_list_by_prod_sku($main_sku, $where, $option);
        return $data;
    }
}

/* End of file ra_product_service.php */
/* Location: ./app/libraries/service/Ra_product_service.php */