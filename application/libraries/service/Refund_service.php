<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Refund_service extends base_service
{

    private $reason_dao;
    private $history_dao;

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH."libraries/dao/Refund_dao.php";
        $this->set_dao(new Refund_dao());
        include_once APPPATH."libraries/dao/Refund_history_dao.php";
        $this->set_history_dao(new Refund_history_dao());
        include_once APPPATH."libraries/dao/Refund_reason_dao.php";
        $this->set_reason_dao(new Refund_reason_dao());
        include_once APPPATH."libraries/dao/Refund_item_dao.php";
        $this->set_ritem_dao(new Refund_item_dao());
        include_once APPPATH."libraries/service/So_service.php";
        $this->so_svc = new So_service();
        include_once APPPATH."libraries/service/Inv_movement_service.php";
        $this->inv_svc = new Inv_movement_service();
        include_once APPPATH."libraries/dao/User_dao.php";
        $this->user_dao = new User_dao();
        include_once(APPPATH."libraries/service/Event_service.php");
        $this->event_srv = new Event_service();
        include_once(APPPATH."libraries/service/Client_service.php");
        $this->client_srv = new Client_service();
        include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
        $this->pbv_srv = new Platform_biz_var_service();
        include_once(APPPATH."libraries/service/Auto_refund_service.php");
        $this->auto_refund_srv = new Auto_refund_service();
        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
    }

    public function set_reason_dao(Base_dao $dao)
    {
        $this->reason_dao = $dao;
    }

    public function get_reason_dao()
    {
        return $this->reason_dao;
    }

    public function set_history_dao(Base_dao $dao)
    {
        $this->history_dao = $dao;
    }

    public function get_history_dao()
    {
        return $this->history_dao;
    }

    public function set_ritem_dao(Base_dao $dao)
    {
        $this->ritem_dao = $dao;
    }

    public function get_ritem_dao()
    {
        return $this->ritem_dao;
    }

    public function get_event_dto()
    {
        include_once APPPATH."libraries/dto/mail_event_dto.php";
        return new Mail_event_dto();
    }

    public function maybe_require_to_do_auto_refund($so_obj)
    {
        if($so_ps_obj = $this->so_svc->get_sops_dao()->get(array("so_no" => $so_obj->get_so_no())))
        {
            if ($so_ps_obj->get_payment_gateway_id() == "yandex")
            {
                return array("payment_gateway_id" => $so_ps_obj->get_payment_gateway_id());
            }
        }
        return false;
    }

    public function is_require_auto_refund($refundid, $so_obj, $refund_obj, $auto_refund)
    {
        $so_ps_obj = $this->so_svc->get_sops_dao()->get(array("so_no" => $so_obj->get_so_no()));
        if (($auto_refund) && ($so_ps_obj))
        {
            $auto_refund = $this->auto_refund_srv->get_dao()->get();
            $auto_refund_obj = clone $auto_refund;
            $auto_refund_obj->set_refund_id($refundid);
            $auto_refund_obj->set_so_no($so_obj->get_so_no());
            $auto_refund_obj->set_payment_gateway_id($so_ps_obj->get_payment_gateway_id());
            $auto_refund_obj->set_action("R");
            if ($refund_obj->get_total_refund_amount() > 0)
            {
                $auto_refund_obj->set_amount($refund_obj->get_total_refund_amount());
                $result = $this->auto_refund_srv->get_dao()->insert($auto_refund_obj);
                if ($result === FALSE)
                {
                    $message = $this->auto_refund_srv->get_dao()->db->_error_message() . ", " . $this->auto_refund_srv->get_dao()->db->last_query();
                    mail("oswald-alert@eservicesgroup.com", "[VB]" . $so_ps_obj->get_payment_gateway_id() . " setup auto refund error, so_no:" . $so_obj->get_so_no(), $message, "From: website@valuebasket.com");
                    return false;
                }
                return true;
            }
            else
            {
                $message = "Auto refund amount:" . $refund_obj->get_total_refund_amount();
                mail("oswald-alert@eservicesgroup.com", "[VB]" . $so_ps_obj->get_payment_gateway_id() . " setup auto refund error, amount<=0, so_no:" . $so_obj->get_so_no(), $message, "From: website@valuebasket.com");
                return false;
            }
        }

        return false;
    }

    public function check_action($refundid="", $action="", $auto_refund = false)
    {

        if($refundid == "" || $action == "")
        {
            return FALSE;
        }

        $result = $this->get_dao()->check_complete($refundid);
        if($result === FALSE)
        {
            return $result;
        }
        else if($result["total"] == $result["completed"])
        {

            $refund_obj = $this->get_dao()->get(array("id"=>$refundid));
            if($refund_obj === FALSE)
            {
                return $refund_obj;
            }

            if($action == "A")
            {
                $so_obj = $this->so_svc->get_dao()->get(array("so_no"=>$refund_obj->get_so_no()));
                if($so_obj === FALSE)
                {
                    return $so_obj;
                }
                $so_obj->set_refund_status('4');
                $ret = $this->so_svc->get_dao()->update($so_obj);

                $m1 = $this->so_svc->get_dao()->db->_error_message();
            }

            $refund_obj->set_status('C');
            $ret2 = $this->get_dao()->update($refund_obj);

            $m2 =  $this->get_dao()->db->_error_message();
            if($ret === FALSE || $ret2 === FALSE)
            {
                $_SESSION["NOTICE"] = $m1." ".$m2;
                $this->get_dao()->trans_rollback();
                return FALSE;
            }
            else
            {
                if($action == "A" && $refund_obj->get_status() == "C")
                {
                    $this->is_require_auto_refund($refundid, $so_obj, $refund_obj, $auto_refund);
                    $platform = $so_obj->get_platform_id();
                    $pbv_obj = $this->pbv_srv->get_dao()->get(array("selling_platform_id"=>$platform));

                    $template = "refund_done";

                    $client_obj = $this->client_srv->get_dao()->get(array("id"=>$so_obj->get_client_id()));
                    $mail_to = $client_obj->get_email();
                    $replace = array("forename"=>$client_obj->get_forename(), "order_number"=>$so_obj->get_so_no(), "order_create_date"=>$so_obj->get_order_create_date());
                    $replace["so_no"] = $so_obj->get_so_no();
                    $replace["client_id"] = $so_obj->get_client_id();
                    $replace["image_url"] = $this->get_config()->value_of("default_url");
                    $replace["logo_file_name"] = $this->get_config()->value_of("logo_file_name");
                    $replace["refund_amount"] = $so_obj->get_currency_id()." ".$refund_obj->get_total_refund_amount();
                    if(!$replace["payment_gateway"] = $this->so_svc->get_so_payment_gateway($so_obj->get_so_no()))
                    {
                        $replace["payment_gateway"] = "N/A";
                    }
                    include_once(APPPATH."hooks/country_selection.php");
                    $replace = array_merge($replace, Country_selection::get_template_require_text($lang_id, $country_id));
                    $mail_from = "no-reply@" . strtolower($replace["site_name"]);
                    $replace["mail_from"] = $mail_from;
                    //fire event for email
                    include_once APPPATH."libraries/dto/event_email_dto.php";
                    $dto = new Event_email_dto();
                    $dto->set_event_id("notification");
                    $dto->set_mail_to($mail_to);
                    $dto->set_mail_from($mail_from);
                    $dto->set_tpl_id($template);
                    $dto->set_lang_id($pbv_obj->get_language_id());
                    $dto->set_replace($replace);
                    $this->event_srv->fire_event($dto);

                    //fire event for payment gateway function
                }
                return TRUE;
            }
        }
        return TRUE;
    }

    public function quick_refund($so_no, $amount, $notes)
    {
        $rollback = false;
        $so_obj = $this->so_svc->get_dao()->get(array("so_no"=>$so_no));

        $refund_obj = $this->get_dao()->get();
        $refund_item = $this->get_ritem_dao()->get();
        $refund_history = $this->get_history_dao()->get();

        $refund_obj->set_so_no($so_no);
        $refund_obj->set_status('C');
        $refund_obj->set_total_refund_amount($amount);
        $refund_obj->set_reason(56);

        $this->get_dao()->trans_start();
        $result = $this->get_dao()->insert($refund_obj);
        error_log(__METHOD__ . __LINE__ . $this->get_dao()->db->_error_message());
        if($result !== FALSE)
        {
            $refund_id = $result->get_id();
            $refund_history->set_refund_id($refund_id);
            $refund_history->set_status('N');
            $refund_history->set_app_status('A');
            $refund_history->set_notes($notes);

            $history_result = $this->get_history_dao()->insert($refund_history);
            error_log(__METHOD__ . __LINE__ . $this->get_history_dao()->db->_error_message());
            if ($history_result != FALSE)
            {
                $refund_item->set_refund_id($refund_id);
                $refund_item->set_line_no(1);
                $refund_item->set_qty(1);
                $refund_item->set_refund_amount($amount);
                $refund_item->set_status('C');
                $refund_item->set_refund_type('C');

                $refund_item_result = $this->get_ritem_dao()->insert($refund_item);
                error_log(__METHOD__ . __LINE__ . $this->get_ritem_dao()->db->_error_message());
                if ($refund_item_result !== FALSE)
                {
                    $refund_history_cs = $this->get_history_dao()->get();
                    $refund_history_cs->set_refund_id($refund_id);
                    $refund_history_cs->set_status('CS');
                    $refund_history_cs->set_app_status('A');
                    $refund_history_cs->set_notes($notes);

                    $history_result = $this->get_history_dao()->insert($refund_history_cs);
                    error_log(__METHOD__ . __LINE__ . $this->get_history_dao()->db->_error_message());

                    if ($history_result !== FALSE)
                    {
                        $refund_history_acc = $this->get_history_dao()->get();
                        $refund_history_acc->set_refund_id($refund_id);
                        $refund_history_acc->set_status('C');
                        $refund_history_acc->set_app_status('A');
                        $refund_history_acc->set_notes('Completed:' . $notes);

                        $history_result = $this->get_history_dao()->insert($refund_history_acc);
                        error_log(__METHOD__ . __LINE__ . $this->get_history_dao()->db->_error_message());
                        if ($history_result !== FALSE)
                        {
                            $so_obj->set_status(3);
                            $so_obj->set_refund_status(4);
                            $so_result = $this->so_svc->get_dao()->update($so_obj);
                            error_log(__METHOD__ . __LINE__ . $this->so_svc->get_dao()->db->_error_message());

                            if ($so_result !== FALSE)
                            {
                                $this->get_dao()->trans_complete();
                                $this->check_action($refund_id, 'A');
                                return true;
                            }
                            else
                            {
                                $rollback = true;
                            }
                        }
                        else
                        {
                            $rollback = true;
                        }
                    }
                    else
                    {
                        $rollback = true;
                    }
                }
                else
                {
                    $rollback = true;
                }
            }
            else
            {
                $rollback = true;
            }
        }
        else
        {
            $rollback = true;
        }

        if ($rollback)
        {
            $this->get_dao()->trans_rollback();
        }
        return false;
    }

    public function create_refund($so_no)
    {
        $so_obj = $this->so_svc->get_dao()->get(array("so_no"=>$so_no));

        if($so_obj)
        {
            $ret = TRUE;
            $refund_obj = $this->get_dao()->get();
            $refund_item = $this->get_ritem_dao()->get();
            $refund_history = $this->get_history_dao()->get();
            $reason = $this->get_reason_dao()->get(array("description LIKE"=>"Fraudulent Orders"));
            $refund_item_arr = array();

            if(!$reason)
            {
                $reason = $this->get_reason_dao()->get(array("reason_cat"=>"O"));

                $reason_code = $reason->get_id();
            }
            else
            {
                $reason_code = $reason->get_id();
            }

            $status = $so_obj->get_status() > 5?1:2;
            $so_obj->set_refund_status($status);

            $refund_obj->set_so_no($so_obj->get_so_no());
            $refund_obj->set_status('I');
            $refund_obj->set_total_refund_amount($so_obj->get_amount());
            $refund_obj->set_reason($reason_code);

            $refund_history->set_status('N');
            $refund_history->set_notes('Refund fraudulent order from Order Reassessment');

            $so_item_list = $this->so_svc->get_soid_dao()->get_list(array("so_no"=>$so_obj->get_so_no()),array("orderby"=>" line_no, item_sku"));
            $item_cnt = count((array)$so_item_list);
            $deliery_added = $so_obj->get_delivery_charge() > 0?0:1;
            $pos = 1;
            foreach($so_item_list as $obj)
            {
                $tmp = clone $refund_item;
                $tmp->set_item_sku($obj->get_item_sku());
                $tmp->set_status('N');
                $tmp->set_refund_type('R');
                $tmp->set_qty($obj->get_qty());
                if(!$delivery_added && $tmp->get_qty() == 1)
                {
                    $tmp->set_refund_amount($obj->get_amount() + $so_obj->get_delivery_charge());
                    $delivery_added = 1;
                }
                else
                {
                    if($pos == $item_cnt && !$delivery_added)
                    {
                        $tmp->set_refund_amount(($obj->get_amount() / $obj->get_qty())+ $so_obj->get_delivery_charge());
                    }
                    else
                    {
                        $tmp->set_refund_amount(($obj->get_amount() / $obj->get_qty()));
                    }
                }
                $refund_item_arr[] = $tmp;
                $pos++;
            }

            $this->get_dao()->trans_start();
            $r = $this->so_svc->get_dao()->update($so_obj);
            if($r !== FALSE)
            {
                $r = $this->get_dao()->insert($refund_obj);
                if($r !== FALSE)
                {
                    $refund_id = $r->get_id();

                    $refund_history->set_refund_id($refund_id);
                    $r = $this->get_history_dao()->insert($refund_history);
                    if($r !== FALSE)
                    {
                        $i = 1;
                        foreach($refund_item_arr as $obj)
                        {
                            $obj->set_refund_id($refund_id);
                            $obj->set_line_no($i);
                            $r = $this->get_ritem_dao()->insert($obj);
                            if($r === FALSE)
                            {
                                break;
                            }
                            else
                            {
                                $i++;
                            }
                        }
                        if($r === FALSE)
                        {
                            $ret = FALSE;
                        }
                    }
                    else
                    {
                        $ret = FALSE;
                    }
                }
                else
                {
                    $ret = FALSE;
                }
            }
            else
            {
                $ret = FALSE;
            }
            $this->get_dao()->trans_complete();
            return $ret;
        }
        return FALSE;
    }

    public function create_refund_from_communication_center($so_no, $refund_parameter)
    {
        $so_obj = $this->so_svc->get_dao()->get(array("so_no"=>$so_no));

        if($so_obj)
        {
            $ret = TRUE;
            $refund_obj = $this->get_dao()->get();
            $refund_item = $this->get_ritem_dao()->get();
            $refund_history = $this->get_history_dao()->get();
            $reason = $this->get_reason_dao()->get(array("description LIKE"=>$refund_parameter["refund_reason_description"]));
            $refund_item_arr = array();

            if(!$reason)
            {
                $reason = $this->get_reason_dao()->get(array("reason_cat"=>"O"));
                $reason_code = $reason->get_id();
            }
            else
            {
                $reason_code = $reason->get_id();
            }

            $status = $so_obj->get_status() > 5?1:2;
            $so_obj->set_refund_status($status);

            $refund_obj->set_so_no($so_obj->get_so_no());
            $refund_obj->set_status('I');
            $refund_obj->set_total_refund_amount($so_obj->get_amount());
            $refund_obj->set_reason($reason_code);

            $refund_history->set_status('CP');
            $refund_history->set_notes('Refund fraudulent order from Communication Center');

            $so_item_list = $this->so_svc->get_soid_dao()->get_list(array("so_no"=>$so_obj->get_so_no()),array("orderby"=>" line_no, item_sku"));
            $item_cnt = count((array)$so_item_list);
            $deliery_added = $so_obj->get_delivery_charge() > 0?0:1;
            $pos = 1;
            foreach($so_item_list as $obj)
            {
                $tmp = clone $refund_item;
                $tmp->set_item_sku($obj->get_item_sku());
                $tmp->set_status('CP');
                $tmp->set_refund_type('R');
                $tmp->set_qty($obj->get_qty());
                if(!$delivery_added && $tmp->get_qty() == 1)
                {
                    $tmp->set_refund_amount($obj->get_amount() + $so_obj->get_delivery_charge());
                    $delivery_added = 1;
                }
                else
                {
                    if($pos == $item_cnt && !$delivery_added)
                    {
                        $tmp->set_refund_amount(($obj->get_amount() / $obj->get_qty()) + $so_obj->get_delivery_charge());
                    }
                    else
                    {
                        $tmp->set_refund_amount(($obj->get_amount() / $obj->get_qty()));
                    }
                }
                $refund_item_arr[] = $tmp;
                $pos++;
            }

            $this->get_dao()->trans_start();
            $r = $this->so_svc->get_dao()->update($so_obj);
            if($r !== FALSE)
            {
                $r = $this->get_dao()->insert($refund_obj);
                if($r !== FALSE)
                {
                    $refund_id = $r->get_id();

                    $refund_history->set_refund_id($refund_id);
                    $r = $this->get_history_dao()->insert($refund_history);
                    if($r !== FALSE)
                    {
                        $i = 1;
                        foreach($refund_item_arr as $obj)
                        {
                            $obj->set_refund_id($refund_id);
                            $obj->set_line_no($i);
                            $r = $this->get_ritem_dao()->insert($obj);
                            if($r === FALSE)
                            {
                                break;
                            }
                            else
                            {
                                $i++;
                            }
                        }
                        if($r === FALSE)
                        {
                            $ret = FALSE;
                        }
                    }
                    else
                    {
                        $ret = FALSE;
                    }
                }
                else
                {
                    $ret = FALSE;
                }
            }
            else
            {
                $ret = FALSE;
            }
            $this->get_dao()->trans_complete();
            return $ret;
        }
        return FALSE;
    }

    public function get_refund_for_order_detail($so_no)
    {
        $ret = array();
        $err = 0;
        $list = $this->get_dao()->get_list(array("so_no"=>$so_no));
        if($list !== FALSE)
        {
            foreach($list as $obj)
            {
                $tmp = $this->get_ritem_dao()->get_list(array("refund_id"=>$obj->get_id()));
                $history = $this->get_history_dao()->get_history_list(array("so_no"=>$so_no,"refund_id"=>$obj->get_id()));
                if($tmp !== FALSE)
                {
                    $ret[$obj->get_id()]["item"] = $tmp;
                    $ret[$obj->get_id()]["content"] = $obj;
                    $ret[$obj->get_id()]["history"] = $history;
                    $ret[$obj->get_id()]["reason"] = $this->get_reason_dao()->get(array("id"=>$obj->get_reason()));
                }
                else
                {
                    $err++;
                    break;
                }
            }
        }
        else
        {
            $err++;
        }

        return $err?FALSE:$ret;
    }

    public function fire_email($rid,$status,$type,$from="")
    {
        if($status == "" || $type == "" || $rid == "")
        {
            return;
        }
        else
        {
            include_once APPPATH."libraries/dto/event_email_dto.php";
            $dto = new Event_email_dto();

            $robj = $this->get_dao()->get(array("id"=>$rid));
            $user_obj = $this->user_dao->get(array("id"=>$robj->get_create_by()));
            $so_obj = $this->so_svc->get_dao()->get(array("so_no"=>$robj->get_so_no()));

            if($status == "1")
            {
                $template = "cs_refund_req";
                $mail_from = $user_obj->get_email();
                $mail_to = "logistics@valuebasket.com";
                //$mail_to = "tommy@eservicesgroup.net";
            }

            else if($status == "2" && $type=="deny" && $from == "log")
            {
                $template = "log_refund_deny";
                $mail_from = "logistics@valuebasket.com";
                $mail_to = $user_obj->get_email();
            }
            else if($status == "2" && $type=="approve" && $from == "log")
            {

                $template = "log_refund_approve";
                $mail_from = "logistics@valuebasket.com";
                $mail_to = array("logistics@valuebasket.com",$user_obj->get_email());

            }
            else
            {
                $template = "";
            }

            if($template != "")
            {
                $replace["username"] = $user_obj->get_username();
                $replace["so_no"] = $robj->get_so_no();
                $replace["client_id"] = $so_obj->get_client_id();

                $dto->set_event_id("notification");
                //$dto->set_mail_to('logistics@valuebasket.com');
                $dto->set_mail_to($mail_to);
                $dto->set_mail_from($mail_from);
                $dto->set_tpl_id($template);
                $dto->set_replace($replace);
                $this->event_srv->fire_event($dto);
            }
        }
    }

    public function get_refund_info_by_period($where = array(), $classname = '')
    {
        return $this->get_dao()->get_refund_info_by_period($where, $classname);
    }

    public function get_refund_report_content($where = array(), $option = array())
    {
        return $this->get_dao()->get_refund_report_content($where, $option);
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function get_refund_history($where = array())
    {
        return $this->get_history_dao()->get($where);
    }

    public function get_refund_item($where = array())
    {
        return $this->get_ritem_dao()->get($where);
    }
}