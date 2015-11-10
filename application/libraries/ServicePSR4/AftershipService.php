<?php
namespace ESG\Panther\Service;

DEFINE("END_POINTS", "https://api.aftership.com/v4");

class AftershipService extends BaseService
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
        set_time_limit(0);
    }

    public function getDynamicShipmentStatus($so_no = "")
    {
        $where["so.so_no"] = $so_no;
        $where["so.refund_status"] = 0;
        if ($obj = $this->getDao('So')->getOrderInfoForDynamicShipmentStatus($where)) {
            $order_status = $obj->getOrderStatus();
            $exist_aftership_status = $obj->getAftershipStatus();
            $shipment_status = "";

            if ($exist_aftership_status) {
                if ($exist_aftership_status == 1 || $exist_aftership_status == 2) {
                    return $this->getShippedPhaseStatus($obj->getDispatchDate());
                } else {
                    $shipment_status = $this->ap_status_mapping[$exist_aftership_status];
                    return array("status" => $shipment_status, "retry" => 0);
                }
            } elseif ($order_status == 6) {
                return $this->getShippedPhaseStatus($obj->getDispatchDate());
            } elseif ($order_status == 5) {
                return array("status" => "allocated", "retry" => 0);
            } elseif ($order_status < 5 && $order_status >= 2) {
                $now = time();
                $pay_date = strtotime($obj->getPayDate());
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

    public function getShippedPhaseStatus($dispatch_date = "")
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

    public function getAftershipStatusMapping()
    {
        return $this->ap_status_mapping;
    }
}
