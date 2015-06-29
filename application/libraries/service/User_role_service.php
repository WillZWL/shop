<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class User_role_service extends Base_service
{

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/User_role_dao.php");
        $this->set_dao(new User_role_dao());
    }
}
