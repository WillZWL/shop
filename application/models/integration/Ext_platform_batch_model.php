<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ext_platform_batch_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('service/ext_platform_batch_service.php');
    }

    public function gen_shipping_override($platform_id)
    {
        $this->ext_platform_batch_service->gen_shipping_override($platform_id);
    }
}
