<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Region_name_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function get_role_access_dto()
    {
        include_once(APPPATH . "/libraries/dao/Role_dao.php");
        Role_dao::get_role_access_dto();
    }

    public static function get_role_service_dto()
    {
        include_once(APPPATH . "/libraries/dao/Role_dao.php");
        Role_dao::get_role_service_dto();
    }

}
