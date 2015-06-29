<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Func_option_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Func_option_dao.php");
        $this->set_dao(new Func_option_dao());
    }

    public function text_of($func_id, $lang_id="en")
    {
        return $this->get_dao()->text_of($func_id, $lang_id);
    }

    public function get_list_w_key($where=array(), $option=array())
    {
        $data = array();
        if ($obj_list = $this->get_list($where, $option))
        {
            foreach ($obj_list as $obj)
            {
                $data[$obj->get_lang_id()][$obj->get_func_id()] = $obj;
            }
        }
        return $data;
    }

}
