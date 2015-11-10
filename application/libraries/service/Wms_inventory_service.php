<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Wms_inventory_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Wms_inventory_dao.php");
        $this->set_dao(new Wms_inventory_dao());
    }

    public function renew_inventory($inv)
    {
        return $this->get_dao()->renew_inventory($inv);
    }

    public function empty_table()
    {
        return $this->get_dao()->empty_table();
    }

    public function get_inventory_list($where = array())
    {
        return $this->get_dao()->get_inventory_list($where);
    }

    public function get_wms_so_no_list()
    {
        $link = 'http://remote.eservicesgroup.com:8080/WMS.Server.Web/Service.asmx/getAllocationbyRetailerDateRange';
        $clLogin = 'clwms';
        $clPwd = 'CLUUWMS56';
        $retailers = 'VB';
        $from = date('Y-m-d', strtotime('-1 day'));
        $to = date('Y-m-d');
        $url = $link . '?clLogin=' . $clLogin . '&clPwd=' . $clPwd . '&retailers=' . $retailers . '&datefrom=' . $from . '&dateto=' . $to;
        $use_curl = true;
        if ($use_curl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            $result = curl_exec($ch);
            curl_close($ch);
        } else
            $result = file_get_contents($url);
        if ($result) {
            $so_no = array();
            $xml = simplexml_load_string($result);
            foreach ($xml->order AS $order) {
                $so_no[] = (string)$order->retailer_order_reference;

            }
            return array($so_no);
        }
        return FALSE;
    }
}


