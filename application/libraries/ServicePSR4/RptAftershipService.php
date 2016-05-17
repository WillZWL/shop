<?php
namespace ESG\Panther\Service;

class RptAftershipService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAftershipReportForFtp($where = array(), $option = array())
    {
        $arr = $this->getDao('So')->getAftershipReportForFtp($where, $option);
        $header = "tracking_number,courier,order_id,customer_name,email,destination_country\n";
        $data = "";
        foreach ($arr as $obj) {
            $courier = strtolower(trim($obj->getCourier()));
            if (preg_match('/deutsch-post/', $courier) || preg_match('/deutsche-post/', $courier)) {
                $courier = "deutsch-post";
                $dispatch_date = $obj->getDispatchDate();
                $date_str = date("m:d:Y", strtotime($dispatch_date));
                $tracking_number = trim($obj->getTrackingno()) . ":" . $date_str;
            } else {
                $tracking_number = trim($obj->getTrackingno());
            }
            $order_id = trim($obj->getSoNo());
            $customer_name = trim($obj->getBillName());
            $email = trim($obj->getClientemail());
            $destination_country = trim($obj->getCountryCode());
            $data .= $tracking_number . "," . $courier . "," . $order_id . "," . $customer_name . "," . $email . "," . $destination_country . "\n";
        }
        return $header . $data;
    }
}


