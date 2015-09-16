<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\WmsInventoryDao;

class WmsInventoryService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new WmsInventoryDao);
    }

    public function renewInventory($inv)
    {
        return $this->getDao()->renewInventory($inv);
    }

    public function emptyTable()
    {
        return $this->getDao()->emptyTable();
    }

    public function getInventoryList($where = [])
    {
        return $this->getDao()->getInventoryList($where);
    }

    public function getWmsSoNoList()
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
            $so_no = [];
            $xml = simplexml_load_string($result);
            foreach ($xml->order AS $order) {
                $so_no[] = (string)$order->retailer_order_reference;

            }
            return array($so_no);
        }
        return FALSE;
    }
}


