<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Context_config_service extends Base_service
{

    public function __construct(){
        parent::__construct();
        include_once(APPPATH."libraries/dao/Config_dao.php");
        $this->set_dao(new Config_dao());
    }

    public function value_of($variable=""){
        return $this->get_dao()->value_of($variable);
    }
}
