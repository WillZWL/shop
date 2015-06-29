<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/service/Product_service.php");
        $this->product_service = new Product_service();
    }

    public function get_product_list($where=array(), $option=array())
    {
        return $this->product_service->get_dao()->get_list_w_country_id($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where=array(),$option=array())
    {
        return $this->product_service->get_dao()->get_list_w_country_id($where, $option,  "Product_list_w_name_dto");
    }

    public function get_video_list($where=array(), $option=array())
    {
        return $this->product_service->get_video_list($where, $option);
    }

    public function get_video_list_w_country($sku="", $country_arr=array())
    {
        return $this->product_service->get_video_list_w_country($sku, $country_arr);
    }

    public function get_num_rows($where=array(), $option=array())
    {
        return $this->product_service->get_video_num_rows($where, $option);
    }

    public function get_num_rows_w_country($sku="", $country_arr=array())
    {
        return $this->product_service->get_video_num_rows_w_country($sku, $country_arr);
    }
}

/* End of file video_service.php */
/* Location: ./app/libraries/service/Video_service.php */