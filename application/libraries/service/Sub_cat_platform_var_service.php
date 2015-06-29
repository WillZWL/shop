<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Sub_cat_platform_var_service extends Base_service {

    public function __construct(){
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH."libraries/dao/Sub_cat_platform_var_dao.php");
        $this->set_dao(new Sub_cat_platform_var_dao());
    }

    public function insert($data)
    {
        return $this->get_dao()->insert($data);
    }

    public function update($data)
    {
        return $this->get_dao()->update($data);
    }

    public function load_vo()
    {
        $this->get_dao()->include_vo();
    }

    public function get($where=array())
    {
        if(count($where) == 0)
        {
            return $this->get_dao()->get();
        }
        else
        {
            return $this->get_dao()->get($where);
        }

    }
}

?>