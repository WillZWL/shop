<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Sequence_service extends Base_service
{

    private $sequence_dao;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Sequence_dao.php");
        $this->set_dao(new Sequence_dao());
    }
}




