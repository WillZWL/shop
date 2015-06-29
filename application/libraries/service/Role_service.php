<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Role_service extends Base_service {

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Role_dao.php");
        $this->set_dao(new Role_dao());
    }
}
