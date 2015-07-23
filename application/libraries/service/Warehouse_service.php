<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Warehouse_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/Warehouse_dao.php");
        $this->set_dao(new Warehouse_dao());
    }

}

?>