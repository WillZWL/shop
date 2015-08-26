<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Colour_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Colour_dao.php");
        $this->set_dao(new Colour_dao());
    }

    public function get($where = array())
    {
        if (!count($where)) {
            return $this->get_dao()->get();
        } else {
            return $this->get_dao()->get($where);
        }
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_list_index($where, $option);
    }
}


