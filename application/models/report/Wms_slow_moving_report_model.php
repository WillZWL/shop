<?php

class Wms_slow_moving_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/wms_slow_moving_report_service');
    }

    public function get_report($date_after)
    {
        $post_data = array();
        $post_data[] = 'retailer[]=VB';
        $post_data[] = 'retailer[]=ALN';
        $post_data[] = 'is_query=1';
        $post_data[] = 'date_after=' . $date_after;

        $result = array();
        $result['filename'] = 'report.csv';
        $result['output'] = $this->wms_slow_moving_report_service->get_report($post_data);

        return $result;
    }
}

?>