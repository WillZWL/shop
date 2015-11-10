<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

abstract class Handle_action_service extends Base_service
{

    var $_dto;

    function __construct()
    {
        parent::__construct();
    }

    abstract function run();

    function get_dto()
    {
        return $this->_dto;
    }

    function set_dto(Base_dto $dto)
    {
        $this->_dto = $dto;
    }

}


