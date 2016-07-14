<?php
namespace ESG\Panther\Models\Order;

use ESG\Panther\Service\CourierService;
use ESG\Panther\Service\ProductService;
use ESG\Panther\Service\WmsInventoryService;
use ESG\Panther\Service\RefundService;
use ESG\Panther\Service\SoService;

class SoModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->soService = new SoService;
        $this->courierService = new CourierService;
        $this->productService = new ProductService;
        $this->wmsInventoryService = new WmsInventoryService;
        $this->refundService = new RefundService;
    }

    public function getValidWebsiteStatusList()
    {
        return array("I" => "In-stock", "P" => "Pre-order");
    }

    public function getList($dao, $where = [], $option = [])
    {
        return $this->getDao($dao)->getList($where, $option);
    }

    public function getNumRows($dao, $where = [])
    {
        return $this->getDao($dao)->getNumRows($where);
    }

    public function get($dao, $where = [])
    {
        return $this->getDao($dao)->get($where);
    }

    public function update($dao, $obj)
    {
        return $this->getDao($dao)->update($obj);
    }

    public function add($dao, $obj)
    {
        return $this->getDao($dao)->insert($obj);
    }

    public function includeVo($dao)
    {
        return $this->getDao($dao)->get();
    }

    public function generateAllocateFile()
    {
        return $this->soService->generateAllocateFile();
    }

    public function errorInAllocateFile()
    {
        return $this->soService->errorInAllocateFile();
    }

    public function getRmaVo()
    {
        return $this->soService->getRmaVo();
    }

    public function insert_rma($obj)
    {
        return $this->soService->insert_rma($obj);
    }

    public function getOrderStatus($so_obj)
    {
        ### Here need add 'status' & 'desc' to template_for_order_status.php, will use todo translate to local language ###
        $status_details_arr = [
            "payment_confirmation" => [
                "id" => "payment_confirmation",
                "status" => "Payment Confirmation",
                "desc" => "Payment received!"
            ],
            "order_review" => [
                "id" => "order_review",
                "status" => "Order Review",
                "desc" => "Order details being confirmed"
            ],
            "cs_order_hold" => [
                "id" => "cs_order_hold",
                "status" => "Held for Verification",
                "desc" => "Your order is being verified and may require further details. Kindly refer to our latest email for further details or contact us directly."
            ],
            "order_hold" => [
                "id" => "order_hold",
                "status" => "Order Held pending Feedback",
                "desc" => "Your order was held by request or due to stock constraint. Kindly refer to our latest email for further details or contact us directly."
            ],
            "refund_in_process" => [
                "id" => "refund_in_process",
                "status" => "Order Refund Pending",
                "desc" => "Your refund is in progress"
            ],
            "refunded" => [
                "id" => "refunded",
                "status" => "Order Refunded",
                "desc" => "Your order has been refunded"
            ],
            "order_confirmed" => [
                "id" => "order_confirmed",
                "status" => "Order Confirmed",
                "desc" => "Order validated! Fulfillment stage next"
            ],
            "stock_review" => [
                "id" => "stock_review",
                "status" => "Stock Review",
                "desc" => "Stock review and assignment"
            ],
            "stock_confirmed" => [
                "id" => "stock_confirmed",
                "status" => "Stock Confirmed",
                "desc" => "Stock inspected and verified to ship!"
            ],
            "order_in_queue" => [
                "id" => "order_in_queue",
                "status" => "Order in Queue",
                "desc" => "Order in fulfillment queue"
            ],
            "delay_in_fulfillment" => [
                "id" => "delay_in_fulfillment",
                "status" => "Order Delay",
                "desc" => "Delay in fulfillment line. Please be patient"
            ],
            "order_delay" => [
                "id" => "order_delay",
                "status" => "Order Delay",
                "desc" => "Thanks for being so patient. Please allow an extra 2-3 working days"
            ],
            "order_packed" => [
                "id" => "order_packed",
                "status" => "Order Packed!",
                "desc" => "Order packed and ready to ship within 1-2 working days!"
            ],
            "in_transit_warehouse" => [
                "id" => "in_transit_warehouse",
                "status" => "In Transit To Warehouse",
                "desc" => "Order packed and ready to ship within 1-2 working days!"
            ],
            "shipped_w_tracking_1" => [
                "id" => "shipped_w_tracking_1",
                "status" => "Shipped",
                "desc" => "Shipment picked up by delivery service"
            ],
            "shipped_w_tracking_2" => [
                "id" => "shipped_w_tracking_2",
                "status" => "Shipped",
                "desc" => "In-transit to warehouse for final delivery to your address"
            ],
            "shipped_w_tracking_3" => [
                "id" => "shipped_w_tracking_3",
                "status" => "Shipped",
                "desc" => "Shipment at final destination processing with delivery service."
            ],
            "shipped_w_tracking_4" => [
                "id" => "shipped_w_tracking_4",
                "status" => "In Transit",
                "desc" => "Parcel in transit to your address"
            ],
            "shipped_w_tracking_5" => [
                "id" => "shipped_w_tracking_5",
                "status" => "Out for Delivery",
                "desc" => "Delivery man has your parcel"
            ],
            "shipped_w_tracking_6" => [
                "id" => "shipped_w_tracking_6",
                "status" => "Delivered",
                "desc" => "The shipment was delivered successfully."
            ],
            "shipped_w_tracking_7" => [
                "id" => "shipped_w_tracking_7",
                "status" => "Failed Delivery Attempt",
                "desc" => "Delivery attempt failed, contact delivery service using info on calling card to arrange collection at nearest pick-up point"
            ],
            "shipped_w_tracking_8" => [
                "id" => "shipped_w_tracking_8",
                "status" => "Exception",
                "desc" => "Custom hold, undelivered, returned shipment to sender or any shipping exceptions."
            ],
            "shipped_w_tracking_9" => [
                "id" => "shipped_w_tracking_9",
                "status" => "Expired",
                "desc" => "Contact us to know regarding latest update of parcel"
            ],
        ];

        $now_time = mktime();
        $status = $working_days = "";
        $time_diff = $now_time - strtotime($so_obj->getOrderCreateDate());
        $order_status = $so_obj->getStatus();
        $hold_status = $so_obj->getHoldStatus();
        $refund_status = $so_obj->getRefundStatus();

        # hold_status = 15 refers to parent of split orders. It will have refund status but no refund history
        if ($refund_status > 0 && $hold_status != 15) {
            switch ($refund_status) {
                case 1:
                case 2:
                case 3:
                    $status = 'refund_in_process';
                    break;
                case 4:
                    $status = 'refunded';
                    break;
            }
        } elseif ($hold_status > 0) {
            $hold_reason = $so_obj->getHoldReason();

            switch ($hold_reason) {
                case 'cscc':
                case 'csvv':
                    $status = 'cs_order_hold';
                    break;

                default:
                    $status = 'order_hold';
                    break;
            }
        } else {
            switch ($order_status) {
                case 2:
                    if ($time_diff < 43200) {
                        $status = 'payment_confirmation';
                    } elseif ($time_diff < 86400) {
                        $status = 'order_review';
                    } else {
                        $status = 'order_review';
                    }
                    break;
                case 3:
                    $working_days = $this->soService->getWorkingDays(strtotime($so_obj->getOrderCreateDate()), $now_time);
                    if ($working_days < 3) {
                        $status = 'order_confirmed';
                    } elseif ($working_days < 5) {
                        $status = 'stock_review';
                    } elseif ($working_days < 7) {
                        $status = 'stock_confirmed';
                    } elseif ($working_days < 9) {
                        $status = 'order_in_queue';
                    } elseif ($working_days < 11) {
                        $status = 'delay_in_fulfillment';
                    } else {
                        $status = 'order_delay';
                    }
                    break;
                case 4:
                case 5:
                    if ($soal_obj = $this->soService->getDao('SoAllocate')->get(['so_no'=>$so_obj->getSoNo()])) {
                        $working_days = $this->soService->getWorkingDays(strtotime($soal_obj->getCreateOn()), $now_time);
                        if ($working_days < 3) {
                            $status = 'order_packed';
                        } elseif ($working_days < 5) {
                            $status = 'in_transit_warehouse';
                        } else {
                            $status = 'in_transit_warehouse';
                        }
                    } else {
                        $status = 'order_delay';
                    }
                    break;
                case 6:
                    if ($so_obj->getDispatchDate() != '0000-00-00 00:00:00') {
                        $working_days = $this->soService->getWorkingDays(strtotime($so_obj->getDispatchDate()), $now_time);

                        //SBF #5275 dynamic status based on aftership status
                        $shipment_obj = $this->soService->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));

                        $aftership = $shipment_obj->getAftershipStatus();

                        if ($aftership == 0) {
                            if ($working_days < 1) {
                                $status = 'shipped_w_tracking_1';
                            } elseif ($working_days < 3) {
                                $status = 'shipped_w_tracking_2';
                            } elseif ($working_days < 5) {
                                $status = 'shipped_w_tracking_3';
                            } else {
                                $status = 'shipped_w_tracking_3';
                            }
                        } else {
                            if ((($aftership == 1) || ($aftership == 2)) && ($working_days < 1)) {
                                $status = 'shipped_w_tracking_1';
                            }
                            if ((($aftership == 1) || ($aftership == 2)) && ($working_days < 3)) {
                                $status = 'shipped_w_tracking_2';
                            }
                            if ((($aftership == 1) || ($aftership == 2)) && ($working_days < 5)) {
                                $status = 'shipped_w_tracking_3';
                            } elseif (($aftership == 3) && ($working_days < 9)) {
                                $status = 'shipped_w_tracking_4';
                            } elseif ($aftership == 4) {
                                $status = 'shipped_w_tracking_5';
                            } elseif ($aftership == 5) {
                                $status = 'shipped_w_tracking_7';
                            } elseif ($aftership == 6) {
                                $status = 'shipped_w_tracking_6';
                            } elseif ($aftership == 7) {
                                $status = 'shipped_w_tracking_8';
                            } elseif ($aftership == 8) {
                                $status = 'shipped_w_tracking_9';
                            } else {
                                $status = 'shipped_w_tracking_3';
                            }
                        }

                        $status_details_arr[$status]['id'] = $status;

                        // this if statement NEEDS the courier_id to have an entry in courier table.
                        if ($shipment_obj2 = $this->soService->getShippingInfo(array("soal.so_no" => $so_obj->getSoNo(), "soal.status" => 3))) {
                            if ($courier_obj = $this->courierService->get(["id" => $shipment_obj2->getCourierId()])) {
                                $status_details_arr[$status]['courier_name'] = $shipment_obj2->getCourierId();

                                if ($shipment_obj2->getTrackingNo() && $courier_obj->getTrackingLink()) {
                                    $status_details_arr[$status]['courier_name'] = $courier_obj->getCourierName();
                                    $status_details_arr[$status]['tracking_url'] = $courier_obj->getTrackingLink();
                                    $status_details_arr[$status]['tracking_number'] = $shipment_obj2->getTrackingNo();
                                }
                            }

                        }
                    } else {
                        $status = 'shipped_w_tracking_1';
                    }
                    break;
            }
        }
        return $status_details_arr[$status];
    }

    public function getSoPriorityScoreInfo($so_no_array)
    {
        return $this->soService->getSoPriorityScoreInfo($so_no_array);
    }

    public function getPriorityScore($so_no)
    {
        return $this->soService->getPriorityScore($so_no);
    }

    public function getProductClearance($prod_sku)
    {
        return $this->productService->isClearance($prod_sku);
    }
}
