<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Po_item_shipment_service extends Base_service
{
    private $wh_dao;
    private $poi_dao;
    private $ss_dao;

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH."libraries/dao/Po_item_shipment_dao.php";
        $this->set_dao(new Po_item_shipment_dao());
        include_once APPPATH."libraries/dao/Warehouse_dao.php";
        $this->set_wh_dao(new Warehouse_dao());
        include_once APPPATH."libraries/dao/Po_item_dao.php";
        $this->set_poi_dao(new Po_item_dao());
        include_once APPPATH."libraries/dao/Supplier_shipment_dao.php";
        $this->set_ss_dao(new Supplier_shipment_dao());
    }

    public function set_wh_dao($obj)
    {
        $this->wh_dao = $obj;
    }

    public function get_wh_dao()
    {
        return $this->wh_dao;
    }

    public function set_poi_dao($obj)
    {
        $this->poi_dao = $obj;
    }

    public function set_ss_dao($obj)
    {
        $this->ss_dao = $obj;
    }

    public function get_poi_dao()
    {
        return $this->poi_dao;
    }

    public function get_supplier_shipment_record($po_number)
    {
        $wh_list = $this->get_wh_dao()->get_list(array(),array("order by"=>"id asc"));
        $poi_list = $this->get_poi_dao()->get_list(array("po_number"=>$po_number), array("limit"=>"-1","order by"=>"line_number asc"));
        $poi_obj = $this->get_poi_dao()->get();

        $shipment_record = array();

        foreach($poi_list as $obj)
        {
            foreach($wh_list as $wobj)
            {
                //$tmp_obj = $this->get_dao()->get_list(array("po_number"=>$po_number,"line_number"=>$obj->get_line_number(),"to_location"=>$wobj->get_id()),array("order by"=>" sid ASC"));
                $tmp_obj = $this->get_dao()->get_item_list($po_number,$obj->get_line_number(),$wobj->get_id());
                $shipment_record[$obj->get_line_number()][$wobj->get_id()] = $tmp_obj;
            }
        }
        return $shipment_record;
    }

    public function get_shipment_count($po_number)
    {
        return $this->get_dao()->get_shipment_count($po_number);
    }

}

?>