<?php

class Integration_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/batch_service');
        $this->load->library('service/batch_inventory_service');
        $this->load->library('service/batch_tracking_info_service');
        $this->load->library('service/batch_youtube_video_service');
        $this->load->library('service/ebay_service');
        $this->load->library('service/price_service');
        $this->load->library('service/payment_gateway_redirect_cybersource_service');
        $this->load->library('service/cps_allocated_so_service');
        $this->load->library('service/selling_platform_service');
    }

    public function get_batch_list($where = array(), $option = array())
    {
        return $this->batch_service->get_dao()->get_batch_list($where, $option);
    }

    public function get_batch_list_total($where = array())
    {
        return $this->batch_service->get_dao()->get_batch_list($where, array("num_rows" => 1));
    }

    public function get_list($d = "dao", $where = array(), $option = array())
    {
        $dao = "get_" . $d;
        return $this->batch_service->$dao()->get_list($where, $option);
    }

    public function get_num_rows($dao = "dao", $where = array())
    {
        $dao = "get_" . $dao;
        return $this->batch_service->$dao()->get_num_rows($where);
    }

    public function get_iinv_list($dao = "dao", $where = array(), $option = array())
    {
        //$dao = "get_".$d;
        //echo $d.":".$dao;
        $ret = $this->batch_inventory_service->get_iinv_dao()->get_list($where, $option);
        return $ret;
    }

    public function get_iinv_num_rows($dao = "dao", $where = array())
    {
        //$dao = "get_".$dao;
        return $this->batch_inventory_service->get_iinv_dao()->get_num_rows($where);
    }

    public function get_itinfo_list($dao = "dao", $where = array(), $option = array())
    {
        //$dao = "get_".$d;
        //echo $d.":".$dao;
        $ret = $this->batch_tracking_info_service->get_itinfo_dao()->get_list($where, $option);
        return $ret;
    }

    public function get_itinfo_num_rows($dao = "dao", $where = array())
    {
        //$dao = "get_".$dao;
        return $this->batch_tracking_info_service->get_itinfo_dao()->get_num_rows($where);
    }

    public function get_iyoutube_list($dao = "dao", $where = array(), $option = array())
    {
        return $this->batch_youtube_video_service->get_yt_dao()->get_batch_record_list($where, $option);
    }

    public function get_iyoutube_num_rows($dao = "dao", $where = array(), $option = array())
    {
        $option['num_rows'] = 1;
        return $this->batch_youtube_video_service->get_yt_dao()->get_batch_record_list($where, $option);
    }

    public function get($dao = "dao", $where = array())
    {
        $dao = "get_" . $dao;
        return $this->batch_service->$dao()->get($where);
    }

    public function update($dao = "dao", $obj)
    {
        $dao = "get_" . $dao;
        return $this->batch_service->$dao()->update($obj);
    }

    public function add($dao = "dao", $obj)
    {
        $dao = "get_" . $dao;
        return $this->batch_service->$dao()->insert($obj);
    }

    public function include_vo($dao)
    {
        $dao = "get_" . $dao;
        return $this->batch_service->$dao()->include_vo();
    }

    public function reprocess_amazon($batch_id = "", $trans_id = "", $platform = "")
    {
        return $this->batch_service->reprocess_amazon($batch_id, $trans_id, $platform);
    }

    public function check_and_update_batch_status($batch_id = "")
    {
        return $this->batch_service->check_and_update_batch_status($batch_id);
    }

    public function reprocess_ebay($batch_id, $trans_id)
    {
        return $this->ebay_service->reprocess_ebay($batch_id, $trans_id);
    }

    public function get_platform_price_list($where = array(), $option = array())
    {
        return $this->price_service->get_platform_price_list($where, $option);
    }

    public function update_auto_price($platform_id, $sku)
    {
        return $this->price_service->update_auto_price($platform_id, $sku);
    }

    public function send_order_to_cybs_decision_manager($debug = 0)
    {
        return $this->payment_gateway_redirect_cybersource_service->send_order_to_dm($debug);
    }

    public function cps_allocated_so_no()
    {
        return $this->cps_allocated_so_service->cps_allocated_so_no();
    }
}



