<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Courier_service extends Base_service
{

    private $crc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Courier_dao.php");
        $this->set_dao(new Courier_dao());
        include_once(APPPATH."libraries/dao/Courier_region_dao.php");
        $this->set_crc_dao(new Courier_region_dao());
    }

    public function get_crc_dao()
    {
        return $this->crc_dao;
    }

    public function set_crc_dao(Base_dao $dao)
    {
        $this->crc_dao = $dao;
    }
}
