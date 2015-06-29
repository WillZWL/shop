<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "wms_connection_service.php";

class Wms_slow_moving_report_service extends Wms_connection_service
{
    public function __construct()
    {
        parent::Wms_connection_service();
    }

    public function get_report($post_data)
    {
        return $this->get_xml($this->get_config()->value_of('wms_slow_moving_report_url'), $post_data);
    }
}
?>