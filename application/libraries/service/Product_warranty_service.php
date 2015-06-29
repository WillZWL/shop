<?php

include_once "Base_service.php";

class Product_warranty_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Product_warranty_dao.php");
        $this->set_dao(new Product_warranty_dao());
    }

    public function get_sku_warranty($sku='', $platform_id='')
    {
        return $this->get_dao()->get_sku_warranty($sku, $platform_id);
    }
}

/* End of file product_warranty_service.php */
/* Location: ./app/libraries/service/Product_warranty_service.php */Å˜