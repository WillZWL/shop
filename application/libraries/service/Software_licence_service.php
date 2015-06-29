<?php

include_once "Base_service.php";

class Software_licence_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Software_licence_dao.php");
        $this->set_dao(new Software_licence_dao());
    }
}


