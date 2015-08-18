<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Country_state_service extends Base_service
{

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Country_state_dao.php");
        $this->set_dao(new Country_state_dao());
    }
}


