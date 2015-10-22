<?php
namespace ESG\Panther\Service;

use EventEmailDto;
use MailEventDto;

class RefundService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->eventService = new EventService;
        $this->soService = new SoService;
    }

    public function getEventDto()
    {
        return new MailEventDto;
    }

    public function maybeRequireToDoAutoRefund($so_obj)
    {
        if ($so_ps_obj = $this->getDao('SoPaymentStatus')->get(["so_no" => $so_obj->getSoNo()])) {
            if ($so_ps_obj->getPaymentGatewayId() == "yandex") {
                return array("payment_gateway_id" => $so_ps_obj->getPaymentGatewayId());
            }
        }
        return false;
    }

    public function quick_refund($so_no, $amount, $notes)
    {
        $rollback = false;
        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);

        $refund_obj = $this->getDao('Refund')->get();
        $refund_item = $this->getDao('RefundItem')->get();
        $refund_history = $this->getDao('RefundHistory')->get();

        $refund_obj->setSoNo($so_no);
        $refund_obj->setStatus('C');
        $refund_obj->setTotalRefundAmount($amount);
        $refund_obj->setReason(56);

        $this->getDao('Refund')->db->trans_start();
        $result = $this->getDao('Refund')->insert($refund_obj);
        error_log(__METHOD__ . __LINE__ . $this->getDao('Refund')->db->display_error());
        if ($result !== FALSE) {
            $refund_id = $result->getId();
            $refund_history->setRefundId($refund_id);
            $refund_history->setStatus('N');
            $refund_history->setAppStatus('A');
            $refund_history->setNotes($notes);

            $history_result = $this->getDao('RefundHistory')->insert($refund_history);
            error_log(__METHOD__ . __LINE__ . $this->getDao('RefundHistory')->db->display_error());
            if ($history_result != FALSE) {
                $refund_item->setRefundId($refund_id);
                $refund_item->setLineNo(1);
                $refund_item->setQty(1);
                $refund_item->setRefundAmount($amount);
                $refund_item->setStatus('C');
                $refund_item->setRefundType('C');

                $refund_item_result = $this->getDao('RefundItem')->insert($refund_item);
                error_log(__METHOD__ . __LINE__ . $this->getDao('RefundItem')->db->display_error());
                if ($refund_item_result !== FALSE) {
                    $refund_history_cs = $this->getDao('RefundHistory')->get();
                    $refund_history_cs->setRefundId($refund_id);
                    $refund_history_cs->setStatus('CS');
                    $refund_history_cs->setAppStatus('A');
                    $refund_history_cs->setNotes($notes);

                    $history_result = $this->getDao('RefundHistory')->insert($refund_history_cs);
                    error_log(__METHOD__ . __LINE__ . $this->getDao('RefundHistory')->db->display_error());

                    if ($history_result !== FALSE) {
                        $refund_history_acc = $this->getDao('RefundHistory')->get();
                        $refund_history_acc->setRefundId($refund_id);
                        $refund_history_acc->setStatus('C');
                        $refund_history_acc->setAppStatus('A');
                        $refund_history_acc->setNotes('Completed:' . $notes);

                        $history_result = $this->getDao('RefundHistory')->insert($refund_history_acc);
                        error_log(__METHOD__ . __LINE__ . $this->getDao('RefundHistory')->db->display_error());
                        if ($history_result !== FALSE) {
                            $update_status = false;
                            if ($so_obj->getStatus() <> 3) {
                                $update_status = true;
                                $status = 3;
                            }

                            $update_refund_status = false;
                            if ($so_obj->getRefundStatus() <> 4) {
                                $update_refund_status = true;
                                $refundStatus = 4;
                            }

                            $so_obj->setStatus(3);
                            $so_obj->setRefundStatus(4);
                            $so_result = $this->getDao('So')->update($so_obj);
                            error_log(__METHOD__ . __LINE__ . $this->getDao('So')->db->display_error());

                            if ($so_result !== FALSE) {
                                if ($update_status) {
                                    $this->sc['So']->updateIofStatusBySo($so_no, $status);
                                }

                                if ($update_refund_status) {
                                    $this->soService->updateIofRefundStatusBySo($so_no, $refundStatus);
                                }

                                $this->getDao('Refund')->db->trans_complete();
                                $this->checkAction($refund_id, 'A');
                                return true;
                            } else {
                                $rollback = true;
                            }
                        } else {
                            $rollback = true;
                        }
                    } else {
                        $rollback = true;
                    }
                } else {
                    $rollback = true;
                }
            } else {
                $rollback = true;
            }
        } else {
            $rollback = true;
        }

        if ($rollback) {
            $this->getDao('Refund')->db->trans_rollback();
        }
        return false;
    }

    public function checkAction($refundid = "", $action = "", $auto_refund = false)
    {

        if ($refundid == "" || $action == "") {
            return FALSE;
        }

        $result = $this->getDao('Refund')->checkComplete($refundid);
        if ($result === FALSE) {
            return $result;
        } else if ($result["total"] == $result["completed"]) {

            $refund_obj = $this->getDao('Refund')->get(["id" => $refundid]);
            if ($refund_obj === FALSE) {
                return $refund_obj;
            }

            if ($action == "A") {
                $so_obj = $this->getDao('So')->get(["so_no" => $refund_obj->getSoNo()]);
                if ($so_obj === FALSE) {
                    return $so_obj;
                }

                $update_refund_status = false;
                if ($so_obj->getRefundStatus() <> 4) {
                    $update_refund_status = true;
                    $refundStatus = 4;
                }

                $so_obj->setRefundStatus('4');
                $ret = $this->getDao('So')->update($so_obj);
                if ($update_refund_status) {
                    $this->soService->updateIofRefundStatusBySo($refund_obj->getSoNo(), $refundStatus);
                }

                $m1 = $this->getDao('So')->db->display_error();
            }

            $refund_obj->setStatus('C');
            $ret2 = $this->getDao('Refund')->update($refund_obj);
            if ($ret === FALSE || $ret2 === FALSE) {
                $m2 = $this->getDao('Refund')->db->display_error();
                $_SESSION["NOTICE"] = $m1 . " " . $m2;
                $this->getDao('Refund')->db->trans_rollback();
                return FALSE;
            } else {
                if ($action == "A" && $refund_obj->getStatus() == "C") {
                    $this->isRequireAutoRefund($refundid, $so_obj, $refund_obj, $auto_refund);
                    $platform = $so_obj->getPlatformId();
                    $pbv_obj = $this->getDao('PlatformBizVar')->get(["selling_platform_id" => $platform]);

                    $template = "refund_done";

                    $client_obj = $this->getDao('Client')->get(["id" => $so_obj->getClientId()]);
                    $mail_to = $client_obj->getEmail();
                    $replace = ["forename" => $client_obj->getForename(), "order_number" => $so_obj->getSoNo(), "order_create_date" => $so_obj->getOrderCreateDate()];
                    $replace["so_no"] = $so_obj->getSoNo();
                    $replace["client_id"] = $so_obj->getClientId();
                    $replace["image_url"] = $this->getDao('Config')->valueOf("default_url");
                    $replace["logo_file_name"] = $this->getDao('Config')->valueOf("logo_file_name");
                    $replace["refund_amount"] = $so_obj->getCurrencyId() . " " . $refund_obj->getTotalRefundAmount();
                    if (!$replace["payment_gateway"] = $this->soService->getSoPaymentGateway($so_obj->getSoNo())) {
                        $replace["payment_gateway"] = "N/A";
                    }

                    $mail_from = "no-reply@digitaldiscount.com";
                    $replace["mail_from"] = $mail_from;
                    //fire event for email
                    $dto = new EventEmailDto;
                    $dto->setEventId("notification");
                    $dto->setMailTo($mail_to);
                    $dto->setMailFrom($mail_from);
                    $dto->setTplId($template);
                    $dto->setLangId($pbv_obj->getLanguageId());
                    $dto->setReplace($replace);
                    $this->eventService->fireEvent($dto);

                    //fire event for payment gateway function
                }
                return TRUE;
            }
        }
        return TRUE;
    }

    public function isRequireAutoRefund($refundid, $so_obj, $refund_obj, $auto_refund)
    {
        $so_ps_obj = $this->getDao('SoPaymentStatus')->get(["so_no" => $so_obj->getSoNo()]);
        if (($auto_refund) && ($so_ps_obj)) {
            $auto_refund = $this->getDao('AutoRefund')->get();
            $auto_refund_obj = clone $auto_refund;
            $auto_refund_obj->setRefundId($refundid);
            $auto_refund_obj->setSoNo($so_obj->getSoNo());
            $auto_refund_obj->setPaymentGatewayId($so_ps_obj->getPaymentGatewayId());
            $auto_refund_obj->setAction("R");
            if ($refund_obj->getTotalRefundAmount() > 0) {
                $auto_refund_obj->setAmount($refund_obj->getTotalRefundAmount());
                $result = $this->getDao('AutoRefund')->insert($auto_refund_obj);
                if ($result === FALSE) {
                    $message = $this->getDao('AutoRefund')->db->display_error() . ", " . $this->getDao('AutoRefund')->db->last_query();
                    mail("oswald-alert@eservicesgroup.com", "[VB]" . $so_ps_obj->getPaymentGatewayId() . " setup auto refund error, so_no:" . $so_obj->getSoNo(), $message, "From: website@digitaldiscount.com");
                    return false;
                }
                return true;
            } else {
                $message = "Auto refund amount:" . $refund_obj->getTotalRefundAmount();
                mail("oswald-alert@eservicesgroup.com", "[VB]" . $so_ps_obj->getPaymentGatewayId() . " setup auto refund error, amount<=0, so_no:" . $so_obj->getSoNo(), $message, "From: website@digitaldiscount.com");
                return false;
            }
        }

        return false;
    }

    public function createRefund($so_no)
    {
        $so_obj = $this->getDao('So')->get(array("so_no" => $so_no));

        if ($so_obj) {
            $ret = TRUE;
            $refund_obj = $this->getDao('Refund')->get();
            $refund_item = $this->getDao('RefundItem')->get();
            $refund_history = $this->getDao('RefundHistory')->get();
            $reason = $this->getDao('RefundReason')->get(["description LIKE" => "Fraudulent Orders"]);
            $refund_item_arr = [];

            if (!$reason) {
                $reason = $this->getDao('RefundReason')->get(["reason_cat" => "O"]);

                $reason_code = $reason->getId();
            } else {
                $reason_code = $reason->getId();
            }

            $status = ($so_obj->getStatus() > 5) ? 1 : 2;
            $update_refund_status = false;
            if ($so_obj->getRefundStatus() <> $status) {
                $update_refund_status = true;
                $refundStatus = $status;
            }

            $so_obj->setRefundStatus($status);

            $refund_obj->setSoNo($so_obj->getSoNo());
            $refund_obj->setStatus('I');
            $refund_obj->setTotalRefundAmount($so_obj->getAmount());
            $refund_obj->setReason($reason_code);

            $refund_history->setStatus('N');
            $refund_history->setNotes('Refund fraudulent order from Order Reassessment');

            $so_item_list = $this->getDao('SoItemDetail')->getList(["so_no" => $so_obj->getSoNo()], ["orderby" => " line_no, item_sku"]);
            $item_cnt = count((array)$so_item_list);
            $deliery_added = $so_obj->getDeliveryCharge() > 0 ? 0 : 1;
            $pos = 1;
            foreach ($so_item_list as $obj) {
                $tmp = clone $refund_item;
                $tmp->setItemSku($obj->getItemSku());
                $tmp->setStatus('N');
                $tmp->setRefundType('R');
                $tmp->setQty($obj->getQty());
                if (!$delivery_added && $tmp->getQty() == 1) {
                    $tmp->setRefundAmount($obj->getAmount() + $so_obj->getDeliveryCharge());
                    $delivery_added = 1;
                } else {
                    if ($pos == $item_cnt && !$delivery_added) {
                        $tmp->setRefundAmount(($obj->getAmount() / $obj->getQty()) + $so_obj->getDeliveryCharge());
                    } else {
                        $tmp->setRefundAmount(($obj->getAmount() / $obj->getQty()));
                    }
                }
                $refund_item_arr[] = $tmp;
                $pos++;
            }

            $this->getDao('Refund')->db->trans_start();

            $r = $this->getDao('So')->update($so_obj);
            if ($r !== FALSE) {
                if ($update_refund_status) {
                    $this->soService->updateIofRefundStatusBySo($so_obj->getSoNo(), $refundStatus);
                }

                $r = $this->getDao('Refund')->insert($refund_obj);
                if ($r !== FALSE) {
                    $refund_id = $r->getId();

                    $refund_history->setRefundId($refund_id);
                    $r = $this->getDao('RefundHistory')->insert($refund_history);
                    if ($r !== FALSE) {
                        $i = 1;
                        foreach ($refund_item_arr as $obj) {
                            $obj->setRefundId($refund_id);
                            $obj->setLineNo($i);
                            $r = $this->getDao('RefundItem')->insert($obj);
                            if ($r === FALSE) {
                                break;
                            } else {
                                $i++;
                            }
                        }
                        if ($r === FALSE) {
                            $ret = FALSE;
                        }
                    } else {
                        $ret = FALSE;
                    }
                } else {
                    $ret = FALSE;
                }
            } else {
                $ret = FALSE;
            }
            $this->getDao('Refund')->db->trans_complete();
            return $ret;
        }
        return FALSE;
    }

    public function create_refund_from_communication_center($so_no, $refund_parameter)
    {
        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);

        if ($so_obj) {
            $ret = TRUE;
            $refund_obj = $this->getDao('Refund')->get();
            $refund_item = $this->getDao('RefundItem')->get();
            $refund_history = $this->getDao('RefundHistory')->get();
            $reason = $this->getDao('RefundReason')->get(["description LIKE" => $refund_parameter["refund_reason_description"]]);
            $refund_item_arr = [];

            if (!$reason) {
                $reason = $this->getDao('RefundReason')->get(["reason_cat" => "O"]);
                $reason_code = $reason->getId();
            } else {
                $reason_code = $reason->getId();
            }

            $status = $so_obj->getStatus() > 5 ? 1 : 2;
            $update_refund_status = false;
            if ($so_obj->getRefundStatus() <> $status) {
                $update_refund_status = true;
                $refundStatus = $status;
            }

            $so_obj->setRefundStatus($status);

            $refund_obj->setSoNo($so_obj->getSoNo());
            $refund_obj->setStatus('I');
            $refund_obj->setTotalRefundAmount($so_obj->getAmount());
            $refund_obj->setReason($reason_code);

            $refund_history->setStatus('CP');
            $refund_history->setNotes('Refund fraudulent order from Communication Center');

            $so_item_list = $this->getDao('SoItemDetail')->getList(["so_no" => $so_obj->getSoNo()], ["orderby" => " line_no, item_sku"]);
            $item_cnt = count((array)$so_item_list);
            $deliery_added = $so_obj->getDeliveryCharge() > 0 ? 0 : 1;
            $pos = 1;
            foreach ($so_item_list as $obj) {
                $tmp = clone $refund_item;
                $tmp->setItemSku($obj->getItemSku());
                $tmp->setStatus('CP');
                $tmp->setRefundType('R');
                $tmp->setQty($obj->getQty());
                if (!$delivery_added && $tmp->getQty() == 1) {
                    $tmp->setRefundAmount($obj->getAmount() + $so_obj->getDeliveryCharge());
                    $delivery_added = 1;
                } else {
                    if ($pos == $item_cnt && !$delivery_added) {
                        $tmp->setRefundAmount(($obj->getAmount() / $obj->getQty()) + $so_obj->getDeliveryCharge());
                    } else {
                        $tmp->setRefundAmount(($obj->getAmount() / $obj->getQty()));
                    }
                }
                $refund_item_arr[] = $tmp;
                $pos++;
            }

            $this->getDao('Refund')->db->trans_start();
            $r = $this->getDao('So')->update($so_obj);
            if ($r !== FALSE) {
                if ($update_refund_status) {
                    $this->soService->updateIofRefundStatusBySo($so_obj->getSoNo(), $refundStatus);
                }

                $r = $this->getDao('Refund')->insert($refund_obj);
                if ($r !== FALSE) {
                    $refund_id = $r->getId();

                    $refund_history->setRefundId($refund_id);
                    $r = $this->getDao('RefundHistory')->insert($refund_history);
                    if ($r !== FALSE) {
                        $i = 1;
                        foreach ($refund_item_arr as $obj) {
                            $obj->setRefundId($refund_id);
                            $obj->setLineNo($i);
                            $r = $this->getDao('RefundItem')->insert($obj);
                            if ($r === FALSE) {
                                break;
                            } else {
                                $i++;
                            }
                        }
                        if ($r === FALSE) {
                            $ret = FALSE;
                        }
                    } else {
                        $ret = FALSE;
                    }
                } else {
                    $ret = FALSE;
                }
            } else {
                $ret = FALSE;
            }
            $this->getDao('Refund')->db->trans_complete();
            return $ret;
        }
        return FALSE;
    }

    public function getRefundForOrderDetail($so_no)
    {
        $ret = [];
        $err = 0;
        $list = $this->getDao('Refund')->getList(["so_no" => $so_no]);
        if ($list !== FALSE) {
            foreach ($list as $obj) {
                $tmp = $this->getDao('RefundItem')->getList(["refund_id" => $obj->getId()]);
                $history = $this->getDao('RefundHistory')->getHistoryList(["so_no" => $so_no, "refund_id" => $obj->getId()]);
                if ($tmp !== FALSE) {
                    $ret[$obj->getId()]["item"] = $tmp;
                    $ret[$obj->getId()]["content"] = $obj;
                    $ret[$obj->getId()]["history"] = $history;
                    $ret[$obj->getId()]["reason"] = $this->getDao('RefundReason')->get(["id" => $obj->getReason()]);
                } else {
                    $err++;
                    break;
                }
            }
        } else {
            $err++;
        }

        return $err ? FALSE : $ret;
    }

    public function fireEmail($rid, $status, $type, $from = "")
    {
        if ($status == "" || $type == "" || $rid == "") {
            return;
        } else {
            $dto = new EventEmailDto;

            $robj = $this->getDao('Refund')->get(["id" => $rid]);
            $user_obj = $this->getDao('User')->get(["id" => $robj->getCreateBy()]);
            $so_obj = $this->getDao('So')->get(["so_no" => $robj->getSoNo()]);

            if ($status == "1") {
                $template = "cs_refund_req";
                $mail_from = $user_obj->getEmail();
                $mail_to = "logistics@digitaldiscount.com";
            } else if ($status == "2" && $type == "deny" && $from == "log") {
                $template = "log_refund_deny";
                $mail_from = "logistics@digitaldiscount.com";
                $mail_to = $user_obj->getEmail();
            } else if ($status == "2" && $type == "approve" && $from == "log") {

                $template = "log_refund_approve";
                $mail_from = "logistics@digitaldiscount.com";
                $mail_to = array("logistics@digitaldiscount.com", $user_obj->getEmail());

            } else {
                $template = "";
            }

            if ($template != "") {
                $replace["username"] = $user_obj->getUsername();
                $replace["so_no"] = $robj->getSoNo();
                $replace["client_id"] = $so_obj->getClientId();

                $dto->setEventId("notification");
                $dto->setMailTo($mail_to);
                $dto->setMailFrom($mail_from);
                $dto->setTplId($template);
                $dto->setReplace($replace);
                $this->eventService->fireEvent($dto);
            }
        }
    }

    public function getRefundInfoByPeriod($where = [], $classname = '')
    {
        return $this->getDao('Refund')->getRefundInfoByPeriod($where, $classname);
    }

    public function getRefundReportContent($where = [], $option = [])
    {
        return $this->getDao('Refund')->getRefundReportContent($where, $option);
    }

    public function getRefundHistory($where = [])
    {
        return $this->getDao('RefundHistory')->get($where);
    }

    public function getRefundItem($where = [])
    {
        return $this->getDao('RefundItem')->get($where);
    }

    public function getOrderList($where = [], $option = [])
    {
        return ["list" => $this->soService->getRefundableList($where, $option),
                "total" => $this->soService->getRefundableList($where, ["num_row" => 1, "create" => $option["create"]])];
    }

    public function getReasonList($where, $option)
    {
        return ["reason_list" => $this->getDao('RefundReason')->getList($where, $option),
            "total" => $this->getDao('RefundReason')->getNumRows($where)];
    }

    public function getRefundSoList($where = [], $option = [])
    {
        return ["list" => $this->getDao('Refund')->getRefundList($where, $option),
            "total" => $this->getDao('Refund')->getRefundList($where, ["num_row" => 1])];
    }
}

