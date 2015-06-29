<?php

include_once "Base_service.php";

class Price_extend_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Price_extend_dao.php");
        $this->set_dao(new Price_extend_dao());
    }
}

/* End of file price_extend_service.php */
/* Location: ./app/libraries/service/Price_extend_service.php */