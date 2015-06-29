<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_global_collect_service.php";

class Pmgw_global_collect_iframe_mode_service extends Pmgw_global_collect_service
{
    protected $checkout_controller_name = 'checkout_facebook';
    protected $checkout_pmgw_name = 'global_collect_iframe_mode';

    public function __construct()
    {
        parent::Pmgw_global_collect_service();
        $CI =& get_instance();
        $CI->load->helper(array('url', 'string'));
        $this->input=$CI->input;
    }

    public function redirect($url)
    {
        echo "<script>document.location.href='" . $url . "';</script>";
    }

    public function redirect_success()
    {
        echo "<script>parent.document.location.href='".base_url()."{$this->checkout_controller_name}/payment_result/1/{$this->so->get_so_no()}';</script>";
    }

    public function redirect_fail($so_no="")
    {
        echo "<script>parent.document.location.href='".base_url()."{$this->checkout_controller_name}/payment_result/0/{$so_no}';</script>";
    }

}

/* End of file pmgw_global_collect_iframe_mode_service.php */
/* Location: ./system/application/libraries/service/Pmgw_global_collect_iframe_mode_service.php */