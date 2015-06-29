<?php

include_once "Base_service.php";

class Order_reason_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Order_reason_dao.php");
        $this->set_dao(new Order_reason_dao());
    }
}

/* End of file order_reason_service.php */
/* Location: ./app/libraries/service/Order_reason_service.php */