<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_custom_classification_service extends Base_service
{

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Product_custom_classification_dao.php");
        $this->set_dao(new Product_custom_classification_dao());
    }

}


