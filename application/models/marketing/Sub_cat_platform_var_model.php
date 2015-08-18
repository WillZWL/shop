<?php

class Sub_cat_platform_var_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/sub_cat_platform_var_service');
    }

}

?>