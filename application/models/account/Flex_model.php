<?php
class Flex_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/flex_service');
        $this->load->library('service/worldpay_pmgw_report_service');
        //$this->load->library('service/pmgw_report_service');
        $this->load->library('service/worldpay_moto_pmgw_report_service');
        $this->load->library('service/paypal_au_pmgw_report_service');
        $this->load->library('service/paypal_hk_pmgw_report_service');
        $this->load->library('service/paypal_nz_pmgw_report_service');
        $this->load->library('service/inpendium_pmgw_report_service');
        $this->load->library('service/trustly_pmgw_report_service');
        $this->load->library('service/paypal_uk_pmgw_report_service');
        $this->load->library('service/moneybookers_pmgw_report_service');
        $this->load->library('service/trademe_pmgw_report_service');
        $this->load->library('service/global_collect_pmgw_report_service');
        $this->load->library('service/fnac_pmgw_report_service');
        $this->load->library('service/lzmy_pmgw_report_service');
        $this->load->library('service/lzdth_pmgw_report_service');
        $this->load->library('service/lzdph_pmgw_report_service');
        $this->load->library('service/adyen_pmgw_report_service');
        $this->load->library('service/altapay_pmgw_report_service');
        $this->load->library('service/newegg_us_pmgw_report_service');
        $this->load->library('service/qoo10_pmgw_report_service');
    }

    public function process_report($pmgw, $filename)
    {
        $pmgw_service = $pmgw."_pmgw_report_service";
        return $this->$pmgw_service->process_report($filename);
    }

    public function generate_feedback_report($where = array(), $option = array())
    {
        return $this->flex_service->generate_feedback_report($where, $option);
    }

    public function get_flex_batch_list($where = array(), $option = array())
    {
        return $this->flex_service->get_flex_batch_list($where, $option);
    }

    public function get_flex_batch_obj($where = array())
    {
        return $this->flex_service->get_flex_batch_obj($where);
    }

    public function get_flex_batch_num_rows($where = array())
    {
        return $this->flex_service->get_flex_batch_num_rows($where);
    }

    public function get_sales_invoice($start_date, $end_date, $folder_name="",$gen_exception_only, $ignore_status)
    {
        return $this->flex_service->get_sales_invoice($start_date, $end_date, $folder_name, $gen_exception_only, $ignore_status);
    }

    public function reverse_sales_invoice_status($date)
    {
        return $this->flex_service->reverse_sales_invoice_status($date);
    }

    public function reverse_refund_invoice_status($date)
    {
        return $this->flex_service->reverse_refund_invoice_status($date);
    }

    public function get_refund_invoice($start_date, $end_date, $type = "R", $folder_name="")
    {
        return $this->flex_service->get_refund_invoice($start_date, $end_date, $type, $folder_name);
    }

    public function get_so_fee_invoice($start_date, $end_date, $gateway_id)
    {
        return $this->flex_service->get_so_fee_invoice($start_date, $end_date, $gateway_id);
    }

    public function get_gateway_fee_invoice($start_date, $end_date, $gateway_id)
    {
        return $this->flex_service->get_gateway_fee_invoice($start_date, $end_date, $gateway_id);
    }

    public function get_rolling_reserve_report($start_date, $end_date, $gateway_id)
    {
        return $this->flex_service->get_rolling_reserve_report($start_date, $end_date, $gateway_id);
    }

    public function get_pending_order_report($ship_date)
    {
        return $this->flex_service->get_pending_order_report($ship_date);
    }

    public function get_rakuten_shipped_order($platform_order_id)
    {
        return $this->flex_service->get_rakuten_shipped_order($platform_order_id);
    }

    public function get_rakuten_shipped_order_from_interface()
    {
        return $this->flex_service->get_rakuten_shipped_order_from_interface();
    }

    public function platfrom_order_insert_interface_flex_ria($gateway_id, $so_no_list)
    {
        return $this->flex_service->platfrom_order_insert_interface_flex_ria($gateway_id, $so_no_list);
    }

    public function platform_order_delete_interface_flex_ria($gateway_id, $so_no_list)
    {
        return $this->flex_service->platform_order_delete_interface_flex_ria($gateway_id, $so_no_list);
    }

    public function platfrom_order_insert_flex_ria($gateway_id, $so_no)
    {
        return $this->flex_service->platfrom_order_insert_flex_ria($gateway_id, $so_no);
    }
}
?>