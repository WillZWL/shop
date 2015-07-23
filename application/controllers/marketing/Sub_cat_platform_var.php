<?php

class Sub_cat_platform_var extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/sub_cat_category_var_model');
    }

}



