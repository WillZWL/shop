<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Inv_movement_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH."libraries/dao/Inv_movement_dao.php");
        $this->set_dao(new Inv_movement_dao());
    }

    public function get_outstanding_w_imvo($wh="",$where=array(),$option=array(),$status="")
    {
        return $this->get_dao()->get_outstanding_open_shipment($wh,$where,$option,$status);
    }

    public function get_outstanding_w_imvo2($wh="",$where=array(),$option=array(),$status="")
    {
        return $this->get_dao()->get_outstanding_open_shipment_new($wh,$where,$option,$status);
    }

    public function get_imvo($wh="",$where=array(),$option=array(),$status="")
    {
        return $this->get_dao()->get_shipment($wh,$where,$option,$status);
    }

    public function get_inventory_movement($where=array())
    {
        return $this->get_dao()->get_inventory_movement($where);
    }
}

?>