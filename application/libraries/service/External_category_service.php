<?php

include_once "Base_service.php";

class External_category_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/External_category_dao.php");
        $this->set_dao(new External_category_dao());
    }
}

/* End of file external_category_service.php */
/* Location: ./app/libraries/service/External_category_service.php */