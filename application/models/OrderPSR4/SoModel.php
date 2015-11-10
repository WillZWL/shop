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
        $status_details_arr = [
            "payment_check" => ["id" => "payment_check", "status" => "Checking Payment Details", "desc" => "Your payment details are being verified."],
            "payment_validated" => ["id" => "payment_validated", "status" => "Payment Details Validated", "desc" => "Your payment details has been validated"],
            "order_check" => ["id" => "order_check", "status" => "Checking Order Details", "desc" => "Your order details are being checked"],
            "order_approved" => ["id" => "order_approved", "status" => "Order Details Approved", "desc" => "Your order has been approved"],
            "order_handling" => ["id" => "order_handling", "status" => "Order Handling", "desc" => "Your order is being made ready"],
            "order_picking" => ["id" => "order_picking", "status" => "Order Picking", "desc" => "Your order is in queue for dispatch"],
            "order_in_queue" => ["id" => "order_in_queue", "status" => "Order in Queue", "desc" => "We are experiencing slight backlog at this time but anticipate dispatch in the coming days. Thank you for your patience."],
            "order_delay" => ["id" => "order_delay", "status" => "Order Delay", "desc" => "Apologies for the inconvenience caused. We have set your order as priority and look forward to dispatching it ASAP. Your continued patience is much appreciated."],
            "arranging_stock" => ["id" => "arranging_stock", "status" => "Arranging Stock", "desc" => "We are arranging stock for your order. Dispatch can be expected within a few days."],
            "allocated" => ["id" => "allocated", "status" => "Allocated", "desc" => "Stock has been allocated and will ship within 1-4 days time."],
            "shipped" => ["id" => "shipped", "status" => "Shipped", "desc" => "Your order was picked up by our couirier and will be with you soon"],
            "order_hold" => ["id" => "order_hold", "status" => "Order Held", "desc" => "You will need to contact us for more details. Kindly refer to our Contact us page."],
            "order_refund_pending" => ["id" => "order_refund_pending", "status" => "Order Refund Pending", "desc" => "Your refund is in progress"],
            "order_refunded" => ["id" => "order_refunded", "status" => "Order Refunded", "desc" => "Your order has been refunded"],
            "cancel_received" => ["id" => "cancel_received", "status" => "Cancellation Request Received", "desc" => "Your cancellation request has been received."],
            "cancel_to_delivery" => ["id" => "cancel_to_delivery", "status" => "Order Allocated - Cancellation Request Sent", "desc" => "Your order is in our dispatch line. We have contacted our warehouse to stop its dispatch."],
            "cancel_confirmed" => ["id" => "cancel_confirmed", "status" => "Cancellation Request Confirmed", "desc" => "Your cancellation request has been confirmed."],
            "refund_in_process" => ["id" => "refund_in_process", "status" => "Refund Request in Process", "desc" => "Your refund request was received and will be processed."],
            "refund_confirmed" => ["id" => "refund_confirmed", "status" => "Refund Request Initiated", "desc" => "Your refund has been processed on our side. It should be with you soon."],
            "refund_on_pmgw" => ["id" => "refund_on_pmgw", "status" => "Refund Submitted to Payment Gateway", "desc" => "Your refund has been processed on our side. Please allow a few working days for your bank to credit the funds back."],

            "refund_submitted" => ["id" => "refund_submitted", "status" => "Refund Request Submitted", "desc" => "Your order has been submitted for refund. An email notification will be sent to you when it is completed."],
            "refund_in_priority" => ["id" => "refund_in_priority", "status" => "Refund Request on Priority", "desc" => "Your refund request has been approved and is being processed by our payment partners. We are currently working with the payment gateway to make sure you get your refund sooner."],
            "refund_escalated" => ["id" => "refund_escalated", "status" => "Refund Request Escalated", "desc" => "Apologies for any inconvenience caused. Your refund has been escalated and is being treated with high priority. A refund confirmation email can be expected upon completion."],

            "refunded" => ["id" => "refunded", "status" => "Refunded", "desc" => "Refund Done."]
        ];
        $now_time = mktime();
        $status = $working_days = "";
        $time_diff = $now_time - strtotime($so_obj->getOrderCreateDate());
        $order_status = $so_obj->getStatus();
        $hold_status = $so_obj->getHoldStatus();
        $refund_status = $so_obj->getRefundStatus();

        # hold_status = 15 refers to parent of split orders. It will have refund status but no refund history
        if ($refund_status > 0 && $hold_status != 15) {
            $refund_obj = $this->refundService->getDao('Refund')->get(["so_no" => $so_obj->getSoNo()]);
            $refund_time_diff = $now_time - strtotime($refund_obj->getCreateOn());
            $refund_working_days = $this->soService->getWorkingDays(strtotime($refund_obj->getCreateOn()), $now_time);
            if ($refund_time_diff < 43200) {
                $status = 'cancel_received';
            } else {
                switch ($refund_status) {
                    case 1:
                    case 2:
                    case 3:
                        if ($refund_item_obj = $this->refundService->getRefundItem(["refund_id" => $refund_obj->getId(), "status" => "N"])) {
                            $status = 'cancel_to_delivery';
                        } else {
                            if ($refund_working_days < 3) {
                                $status = 'cancel_confirmed';
                            } elseif ($refund_working_days < 5) {
                                $status = 'refund_submitted';
                            } elseif ($refund_working_days < 7) {
                                $status = 'refund_in_process';
                            } elseif ($refund_working_days < 9) {
                                $status = 'refund_in_priority';
                            } else {
                                $status = 'refund_escalated';
                            }
                        }
                        break;
                    case 4:
                        if ($complete_refund_obj = $this->refundService->getDao('RefundHistory')->getRefundHistory(["refund_id" => $refund_obj->getId(), "status" => "C"])) {
                            $refunded_date = $complete_refund_obj->getCreateOn();
                            $refunded_working_days = $this->soService->getWorkingDays(strtotime($refunded_date), $now_time);
                            if ($refunded_working_days < 2) {
                                $status = 'refund_confirmed';
                            } elseif ($refunded_working_days < 3) {
                                $status = 'refund_on_pmgw';
                            } else {
                                $status = 'refunded';
                            }
                        } else {
                            $status = 'refunded';
                        }
                        break;
                }
            }
        } elseif ($hold_status > 0) {
            $status = 'order_hold';
        } else {
            switch ($order_status) {
                case 2:
                    if ($time_diff < 43200) {
                        $status = 'payment_check';
                    } elseif ($time_diff < 86400) {
                        $status = 'payment_validated';
                    } else {
                        $status = 'order_check';
                    }
                    break;
                case 3:
                    $working_days = $this->soService->getWorkingDays(strtotime($so_obj->getOrderCreateDate()), $now_time);
                    if ($working_days < 5) {
                        $status = 'order_approved';
                    } elseif ($working_days < 7) {
                        $status = 'order_handling';
                    } elseif ($working_days < 9) {
                        $status = 'order_picking';
                    } elseif ($working_days < 11) {
                        $status = 'order_in_queue';
                    } else {
                        $status = 'order_delay';
                    }
                    break;
                case 4:
                case 5:
                    $status = 'allocated';
                    break;
                case 6:
                    $status = 'shipped';
                    if ($so_obj->getDispatchDate()) {
                        $working_days = $this->soService->getWorkingDays(strtotime($so_obj->getDispatchDate()), $now_time);

                        //SBF #5275 dynamic status based on aftership status
                        $shipment_obj = $this->soService->getDao('SoExtend')->get(array("so_no" => $so_obj->getSoNo()));
                        $aftership = $shipment_obj->getAftershipStatus();

                        if ($aftership == '') {
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
                            } elseif (($aftership == 3) && ($working_days >= 9)) {
                                $status = 'shipped_w_tracking_4_postal';
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
