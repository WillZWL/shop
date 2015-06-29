<?php
class Surplus_report_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/surplus_rpt_service');
    }

    public function get_unmapped_surplus($format='xml')
    {
        return $this->surplus_rpt_service->get_unmapped_surplus($format);
    }

    public function get_unlisted_surplus($format='xml')
    {
        return $this->surplus_rpt_service->get_unlisted_surplus($format);
    }
}
?>