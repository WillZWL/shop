<?php
class Customer_extraction_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/customer_extraction_service');
        $this->load->library('service/rpt_customer_extraction_service');
    }

    public function get_platform_ex($full_list,$input)
    {
        return $this->customer_extraction_service->get_platform_ex($full_list,$input);
    }

    public function get_platform_list($where=array(), $option=array())
    {
        return $this->customer_extraction_service->get_platform_list($where, $option);
    }

    public function get_category_ex($full_list,$input)
    {
        return $this->customer_extraction_service->get_category_ex($full_list,$input);
    }

    public function get_combined_cat_list($where=array(), $option=array())
    {
        return $this->customer_extraction_service->get_combined_cat_list($where, $option);
    }

    public function get_exchange_rate($where=array(), $option=array())
    {
        return $this->customer_extraction_service->get_exchange_rate($where, $option);
    }

    public function get_csv($record, $where)
    {
        return $this->rpt_customer_extraction_service->get_csv($record, $where);
    }

}
?>