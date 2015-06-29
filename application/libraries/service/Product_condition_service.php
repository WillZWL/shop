<?php

include_once "Base_service.php";

class Product_condition_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Product_condition_dao.php");
        $this->set_dao(new Product_condition_dao());
    }
}

/* End of file product_condition_service.php */
/* Location: ./app/libraries/service/Product_condition_service.php */