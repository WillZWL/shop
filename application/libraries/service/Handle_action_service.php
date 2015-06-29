<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

abstract class Handle_action_service extends Base_service {

    abstract function run();

    var $_dto;

    function __construct(){
        parent::__construct();
    }

    function get_dto(){
        return $this->_dto;
    }

    function set_dto(Base_dto $dto){
        $this->_dto = $dto;
    }

}

/* End of file handle_action_service.php */
/* Location: ./system/application/libraries/service/Handle_action_service.php */