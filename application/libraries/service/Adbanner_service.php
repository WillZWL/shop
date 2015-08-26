<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Adbanner_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->load->library('dao/adbanner_dao');
        include_once(APPPATH . "libraries/dao/Adbanner_dao.php");
        $this->set_dao(new Adbanner_dao());
    }

    public function get($value = "")
    {
        if ($value != "") {
            $ret = $this->get_dao()->get(array("id" => $value));
        } else {
            $ret = $this->get_dao()->get();
        }
        return $ret;
    }

    public function get_list_with_name($value)
    {
        return $this->get_dao()->get_list_with_name($value);
    }

}
