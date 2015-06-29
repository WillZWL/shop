<?php

class Marketing_report_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_marketing_service');
    }

    public function get_html($from_time, $to_time)
    {
        $arr = $this->rpt_marketing_service->get_data($from_time, $to_time);


        $cost_inc = array();
        $cost_dec = array();
        $status_chg = array();
        $new_item = array();

        foreach ($arr as $obj) {
            if ($obj->get_is_new()) {
                $new_item[] = $obj;
            } else if ($obj->get_cost_diff() == 0) {
                $status_chg[] = $obj;
            } else if ($obj->get_cost_diff() > 0) {
                $cost_inc[] = $obj;
            } else if ($obj->get_cost_diff() < 0) {
                $cost_dec[] = $obj;
            }
            /*else
            {
                $new_item[] = $obj;
            }*/
        }

        $result['cost_inc'] = $cost_inc;
        $result['cost_dec'] = $cost_dec;
        $result['status_chg'] = $status_chg;
        $result['new_product'] = $new_item;

        return $result;
    }

}
