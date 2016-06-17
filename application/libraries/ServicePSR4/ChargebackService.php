<?php

namespace ESG\Panther\Service;

class ChargebackService extends BaseService
{
    public $dex_service;
    public $delivery_option_service;
    public $encrypt;
    private $so_dao;

    public function __construct()
    {
        parent::__construct();

        $CI =& get_instance();
        $CI->load->library('encryption');
        $this->encryption = $CI->encryption;
    }

    public function getChargebackData($filter = array())
    {
        return $this->getDao('Chargeback')->getChargebackData($filter);
    }

    public function processData($data = array(), $format = 'csv')
    {
        if (empty($data)) {
            return;
        }

        $delivery_data = end($this->getService('DeliveryOption')->getListWithKey(["lang_id" => "en"]));

        $i = 0;
        foreach ($data as $obj) {
            $password = $this->encryption->decrypt($obj->getPassword());
            $obj->setPassword($password);

            $del_mode = $obj->getDeliveryMode();
            $obj->setShipServiceLevel($delivery_data[$del_mode]->getDisplayName());

            // functions set in Chargeback_orders_dto
            $obj->setBillAddress($obj->getBillAddress());
            $obj->setDeliveryAddress($obj->getDeliveryAddress());
            $obj->setPaymentStatus($obj->getPaymentStatus());
            $obj->setOrderCreateDateTime($obj->getOrderCreateDateTime());
            $obj->setHoldDateTime($obj->getHoldDateTime());
        }

        if ($format == "csv") {
            $result = $this->convertToCsv($data);
        }

        return $result;
    }

    private function convertToCsv($data = array())
    {
        if (empty($data)) {
            return;
        }

        $i = 0;
        $data_str = "";
        $data_csv = array();
        $ignore = array(
            "getHoldDateTime", "getPaymentStatus", "getBillName", "getBillAddress", "getDeliveryForename",
            "getDeliverySurname", "getDeliveryAddress", "getTel1", "getTel2", "getTel3", "getDeliveryMode", "getPayToAccount"
        );

        foreach ($data as $obj) {
            // all methods in chargeback_orders_dto gets constructed automatically with headers
            $classname = get_class($obj);
            if ($methods = get_class_methods($classname)) {
                foreach ($methods as $method) {
                    if (in_array($method, $ignore))
                        continue;

                    if (strpos($method, "get") !== FALSE) {
                        if ($i == 0) {
                            // create header
                            $header .= str_replace("get", "", $method) . ",";
                        }

                        // actual data
                        if (method_exists($obj, $method)) {
                            $data_csv[$i] .= str_replace(',', ' ', $obj->$method()) . ",";
                        } else {
                            $data_csv[$i] .= " ,";
                        }
                    }
                }
            }
            $i++;
        }
        if ($data_csv) {
            array_unshift($data_csv, $header);
            foreach ($data_csv as $v) {
                $data_str .= trim($v, ',') . "\r\n";
            }
        }

        return $data_str;
    }

}

