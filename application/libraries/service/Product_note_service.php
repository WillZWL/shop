<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_note_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/Product_note_dao.php");
        $this->set_dao(new Product_note_dao());
    }

    public function get_note_by_sku($sku = '')
    {
        $obj = $this->get_dao()->get_list(array('sku' => $sku, 'type' => 'S'), array('order_by' => 'modify_on DESC', 'limit' => 1));

        return $obj;
    }
}

?>