<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";
DEFINE("END_POINTS", "https://api.aftership.com/v4");

class aftership_service extends Base_service
{

    private $API_KEY_ES = "db50e623-0369-4eff-8b4a-9860f88ab155";
    private $API_KEY_FR = "7fba2871-78b2-430c-b791-0e6bb2e6ae3e";
    private $API_KEY_IT = "46c62003-f322-4f4e-aca9-8e60d29fe57b";
    private $API_KEY_REST = "687ee839-08d4-4767-8d38-24921e18914b";

    private $ap_status_mapping = array(
        0 => "",
        1 => "Pending",
        // 1=>"New shipment added /and or in-transit pending carrier scan.",
        2 => "InfoReceived",
        // 2=>"New shipment added /and or in-transit pending carrier scan.",
        3 => "InTransit",
        4 => "OutForDelivery",
        5 => "AttemptFail",
        6 => "Delivered",
        7 => "Exception",
        8 => "Expired"
    );


    public function aftership_service($tool_path = 'marketing/pricing_tool')
    {
        parent::__construct();

        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());

        include_once(APPPATH . "libraries/dao/So_extend_dao.php");
        $this->so_extend_dao = new So_extend_dao();

        set_time_limit(0);
    }

    public function set_so_dao($value)
    {
        $this->so_dao = $value;
    }

    /*

    public function get_support_courier_list($tracking_no="",$slug="", $country_id="")
    {
        $data = array();
        $info_type = "couriers";
        $method_type = "GET";
        $result = $this->send($info_type, $method_type, $data, $country_id,$server_result,$server_info,$server_error);
        var_dump($result);die();
    }

    public function detect_courier($tracking_no="", $slug="", $country_id="")
    {
        if(empty($tracking_no))
        {
            return false;
        }
        else
        {
            $data = array();
            $info_type = "couriers/detect/{$tracking_no}";
            $method_type = "GET";
            $result = $this->send($info_type, $method_type, $data, $country_id, $server_result,$server_info,$server_error);
            var_dump($result);die();
        }
    }

    public function tracking_order($tracking_no="", $slug="", $country_id="")
    {
        if(empty($tracking_no))
        {
            return false;
        }
        else
        {
            //interlink-express
            //dpd-uk
            //dhl
            $data = array();
            $info_type = "trackings/{$slug}/{$tracking_no}";
            $method_type = "GET";
            $result = $this->send($info_type, $method_type, $data, $country_id);
            var_dump($result);die();
        }
    }

    public function last_check_point($tracking_no="", $slug="", $country_id="", $obj="")
    {
        //using webhook;
        return false;


        if(empty($tracking_no) || empty($obj))
        {
            return false;
        }
        else
        {
            $data = array();
            $info_type = "last_checkpoint/{$slug}/{$tracking_no}";
            //$info_type = "last_checkpoint/{$slug}/{$tracking_no}?tracking_ship_date=20140811";

            if($slug == "deutsch-post")
            {
                $tracking_ship_date = date('Ymd', strtotime($obj->get_dispatch_date()));
                $info_type .= "?tracking_ship_date=20140811";
            }

            $method_type = "GET";
            $trycount = 0;
            $server_result = '';
            $server_info = '';
            $server_error = '';

            do
            {
                $result = $this->send($info_type, $method_type, $data, $country_id, $server_result,$server_info,$server_error);
                $trycount++;
            }while (($trycount < 2) && ((!$server_result) || (empty($server_result))));

            if($server_error)
            {
                return $server_error;
            }
            return $server_result;
        }
    }

    public function send($info_type="", $method_type="", $data = array(), $country_id="", &$server_result,&$server_info,&$server_error)
    {
        $country_id = strtoupper($country_id);
        $country_list = array("ES","IT","FR");

        if(in_array($country_id, $country_list))
        {
            $api_key = $this->{"API_KEY_".$country_id};
        }
        else
        {
            $api_key = $this->API_KEY_REST;
        }
        $data_str = http_build_query($data);
        $ch = curl_init();
        if($method_type=="GET")
        {
            curl_setopt($ch, CURLOPT_POST, 0);
            $url = END_POINTS."/".$info_type;
            //$url = END_POINTS."/".$info_type."/?{$data_str}";
        }
        else
        {
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
            $url = END_POINTS."/".$info_type;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //curl_setopt($ch, CURLOPT_USERAGENT, "ESG");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "aftership-api-key: ".$api_key,
                "Content-Type: application/json"
            ));

        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $server_result = curl_exec($ch);
        $server_error = curl_error($ch);
        $server_info = curl_getinfo($ch);
        curl_close($ch);

        return $server_result;

    }
    */

    public function get_dynamic_shipment_status($so_no = "")
    {
        $where["so.so_no"] = $so_no;
        $where["so.refund_status"] = 0;
        if ($obj = $this->get_so_dao()->get_order_info_for_dynamic_shipment_status($where)) {
            $order_status = $obj->get_order_status();
            $exist_aftership_status = $obj->get_aftership_status();
            $shipment_status = "";

            if ($exist_aftership_status) {
                if ($exist_aftership_status == 1 || $exist_aftership_status == 2) {
                    return $this->get_shipped_phase_status($obj->get_dispatch_date());
                } else {
                    $shipment_status = $this->ap_status_mapping[$exist_aftership_status];
                    return array("status" => $shipment_status, "retry" => 0);
                }
            } elseif ($order_status == 6) {
                return $this->get_shipped_phase_status($obj->get_dispatch_date());
            } elseif ($order_status == 5) {
                return array("status" => "allocated", "retry" => 0);
            } elseif ($order_status < 5 && $order_status >= 2) {
                $now = time();
                $pay_date = strtotime($obj->get_pay_date());
                $time_diff = $now - $pay_date;
                $diff_in_hours = intval($time_diff / 3600);
                $diff_in_days = intval($time_diff / 86400);

                if ($diff_in_hours <= 12) {
                    $shipment_status = "payment_details_check";
                } elseif ($diff_in_hours <= 24) {
                    $shipment_status = "payment_details_validated";
                } elseif ($diff_in_days <= 2) {
                    $shipment_status = "order_details_check";
                } elseif ($diff_in_days <= 4) {
                    $shipment_status = "order_details_approved";
                } elseif ($diff_in_days <= 6) {
                    $shipment_status = "order_handling";
                } elseif ($diff_in_days <= 8) {
                    $shipment_status = "order_picking";
                } elseif ($diff_in_days <= 10) {
                    $shipment_status = "order_in_queue";
                } else {
                    $shipment_status = "order_dealy";
                }

                return array("status" => $shipment_status, "retry" => 0);
            } else {
                $shipment_status = "payment_unconfirmed";
                return array("status" => $shipment_status, "retry" => 0);
            }
        } else {
            return array("status" => "", "retry" => 0);
        }
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function get_shipped_phase_status($dispatch_date = "")
    {

        $now = time();
        $dispatch_date = strtotime($dispatch_date);
        $time_diff = $now - $dispatch_date;
        $diff_in_days = intval($time_diff / 86400);

        if ($diff_in_days < 3) {
            $shipment_status = "shipped_phase_1";
        } elseif ($diff_in_days < 5) {
            $shipment_status = "shipped_phase_2";
        } else {
            $shipment_status = "shipped_phase_3";
        }

        return array("status" => $shipment_status, "retry" => 0);
    }

    public function set_gp_dao($value)
    {
        $this->gp_dao = $value;
    }

    public function get_gp_dao()
    {
        return $this->gp_dao;
    }

    public function get_aftership_status_mapping()
    {
        return $this->ap_status_mapping;
    }
}
