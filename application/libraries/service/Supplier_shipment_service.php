<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Supplier_shipment_service extends Base_service
{
    private $wh_dao;
    private $poi_dao;

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH . "libraries/dao/Supplier_shipment_dao.php";
        $this->set_dao(new Supplier_shipment_dao());
    }

    public function gen_shipment_csv($shipment_id)
    {
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $dex = new Data_exchange_service();
        if ($obj_list = $this->get_dao()->get_shipment_csv_info($shipment_id)) {
            $obj_vo = new Vo_to_xml($obj_list, APPPATH . 'data/shipment_vo2xml.txt');
            $out_csv = new Xml_to_csv();
            echo $dex->convert($obj_vo, $out_csv);
        }
    }

    public function get_csv($data)
    {
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $dex = new Data_exchange_service();
        $out_xml = new Vo_to_xml($data);
        $out_csv = new Xml_to_csv('', APPPATH . 'data/supplier_order_list.txt', ',');
        $file_content = $dex->convert($out_xml, $out_csv);
        if ($file_content != "") {
            echo $file_content;
        }
    }

    public function get_wh_dao()
    {
        return $this->wh_dao;
    }

    public function set_wh_dao($obj)
    {
        $this->wh_dao = $obj;
    }

    public function get_poi_dao()
    {
        return $this->poi_dao;
    }

    public function set_poi_dao($obj)
    {
        $this->poi_dao = $obj;
    }

}

?>