<?php

include_once "Base_service.php";

class Auto_refund_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Auto_refund_dao.php");
        $this->set_dao(new Auto_refund_dao());
    }
}


