<?php

include_once "Base_service.php";

class Ra_group_product_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Ra_group_product_dao.php");
        $this->set_dao(new Ra_group_product_dao());
    }
}


