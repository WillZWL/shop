<?php

include_once "Base_service.php";

class Website_bank_transfer_service extends Base_service
{
    private $bank_acc_dao;
    private $now_time;
    private $notification_email = "itsupport@eservicesgroup.net";

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Bank_account_dao.php");
        $this->set_dao(new Bank_account_dao());
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/dao/So_payment_status_dao.php");
        $this->set_so_payment_status_dao(new So_payment_status_dao());
        include_once(APPPATH . "libraries/dao/Order_notes_dao.php");
        $this->set_order_notes_dao(new Order_notes_dao());
        include_once(APPPATH . "libraries/dao/So_hold_reason_dao.php");
        $this->set_so_hold_reason_dao(new So_hold_reason_dao());

        include_once(APPPATH . "libraries/dao/So_bank_transfer_dao.php");
        $this->set_so_bank_transfer_dao(new So_bank_transfer_dao());
    }

    public function set_so_dao(Base_dao $dao)
    {
        $this->so_dao = $dao;
    }

    public function set_so_payment_status_dao(Base_dao $dao)
    {
        $this->so_payment_status_dao = $dao;
    }

    public function set_order_notes_dao(Base_dao $dao)
    {
        $this->order_notes_dao = $dao;
    }

    public function set_so_hold_reason_dao(Base_dao $dao)
    {
        $this->so_hold_reason_dao = $dao;
    }

    /* ============================================================================================
        This function will give a list of active orders with payment gateway = "w_bank_transfer".
        It will filter out time difference (btw now and order_create_date) and the
        net diff status = unpaid or under paid > 1%
    ============================================================================================ */

    public function set_so_bank_transfer_dao(Base_dao $dao)
    {
        $this->so_bank_transfer_dao = $dao;
    }

    /* ============================================================================================
        This function will give a list of ON HOLD orders with payment gateway = "w_bank_transfer".
        It will filter out time difference (btw now and order_create_date) and the
        net diff status = unpaid
    ============================================================================================ */

    public function payment_reminder($platform_id)
    {
        $type = "reminder";
        $this->now_time = time();
        if (!($this->days = $this->get_days($platform_id, $type))) {
            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\nWebsite bank transfer [platform_id $platform_id] - Fail to run payment_reminder. Missing number of days.";
            $this->send_notification_email($platform_id, "MD", $error_msg);
            exit;
        }

        include_once(APPPATH . "libraries/service/Pmgw.php");
        $pmgw = new Pmgw();

        if (($bank_transfer_order_list = $this->get_order_list_for_email($platform_id, $type)) !== FALSE) {
            $i = 0;
            foreach ($bank_transfer_order_list as $bt_order_obj) {
                $so_no = $bt_order_obj->get_so_no();
                $i++;

                // if ($so_no !== '379353') continue;

                if ($so_obj = $this->get_so_dao()->get(array("so_no" => $so_no))) {
                    $pmgw->so = $so_obj;
                }

                if ($bt_order_obj->get_received_amt_localcurr()) {
                    # customer has previous payment, hence template incl bank transfer transaction details
                    # \app\data\template\wbanktransfer_reminder_partial\wbanktransfer_reminder_partial.html

                    $pmgw->so_bank_transfer_obj = $bt_order_obj;
                    $pmgw->fire_collect_payment_event("reminder_partial_payment");
                    echo "<br>$i. REMINDER SUCCESS || PARTIAL_PAYMENT. so_no=$so_no; client_id={$bt_order_obj->get_client_id()}; client_email={$bt_order_obj->get_email()} ";
                } else {
                    # no previous payments, use template without transaction details
                    # \app\data\template\wbanktransfer_reminder\wbanktransfer_reminder.html

                    $pmgw->fire_collect_payment_event("reminder_no_payment");
                    echo "<br>$i. REMINDER SUCCESS || NO_PAYMENT. so_no=$so_no; client_id={$bt_order_obj->get_client_id()}; client_email={$bt_order_obj->get_email()} ";

                }
            }
        }
    }

    /***************
     * This function sets the number of days you need between now_time and so.order_create_date to send
     * out emails and set necessary changes to db
     ***************/
    private function get_days($platform_id = "WEBES", $type)
    {
        $days = "";

        if ($type) {
            switch ($platform_id) {
                case "WEBES":
                    if ($type == "reminder") {
                        $days = 4;
                    } else if ($type == "hold_unpaid") {
                        $days = 7;
                    } else if ($type == "hold_unpaid_aft_grace") {
                        $days = 14;
                    }

                    break;

                default:  #SBF #3595, 3315 follows WEBES
                    if ($type == "reminder")
                        $days = 4;

                    else if ($type == "cancel")
                        $days = 7;

                    else if ($type == "hold_unpaid_aft_grace")
                        $days = 14;

                    break;
            }
        }

        return $days;
    }

    private function send_notification_email($platform_id = "WEBES", $error_type, $error_msg = "")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";
        $it_email = $this->notification_email;

        $title = "";

        switch ($platform_id) {
            case 'WEBES':
                $phpmail->AddAddress("gonzalo@eservicesgroup.com");
                break;

            default:
                # code...
                break;
        }

        if ($platform_id) {
            switch ($error_type) {

                case "UF":
                    $message = $error_msg;
                    $title = "WARNING - Web Bank Transfer [$platform_id] Update Database Failed";
                    break;

            }


            $phpmail->AddAddress($it_email);

            $phpmail->Subject = "$title";
            $phpmail->IsHTML(false);
            $phpmail->Body = $message;

            $result = $phpmail->Send();
        }
    }

    private function get_order_list_for_email($platform_id = "WEBES", $type)
    {
        $so_bank_transfer_dao = $this->get_so_bank_transfer_dao();
        $option["limit"] = -1;
        $order_list = array();
        $days = $this->days;

        if ($this->now_time)
            $now_time = $this->now_time;
        else
            $now_time = time();

        if ($type == "reminder") {
            # unpaid or underpaid net_diff is > 1%
            $where["(sbt.net_diff_status = 3 OR sbt.net_diff_status IS NULL)"] = NULL;
            $where["so.platform_id"] = $platform_id;
        }

        if ($type == "hold_unpaid") {
            # totally unpaid
            $where["sbt.net_diff_status IS NULL"] = NULL;
            $where["so.platform_id"] = $platform_id;
            $where["sops.payment_status"] = 'N'; # payment_status = New
        }

        if ($bank_transfer_order_list = $so_bank_transfer_dao->get_so_bank_transfer_list($where, $option, $type)) {
            // var_dump($so_bank_transfer_dao->db->last_query());die();
            foreach ($bank_transfer_order_list as $bt_order_obj) {
                if ($type == "reminder") {
                    $order_create_date = strtotime($bt_order_obj->get_order_create_date());

                    # send email on 4th day; don't email on subsequent days
                    if (($now_time - $order_create_date) >= ($days * 24 * 60 * 60)
                        && ($now_time - $order_create_date) < (($days + 1) * 24 * 60 * 60)
                    ) {
                        $order_list[] = $bt_order_obj;
                    }
                }

                if ($type == "hold_unpaid") {
                    $order_create_date = strtotime($bt_order_obj->get_order_create_date());

                    # more than 7 days; make sure don't email subsequently
                    if (($now_time - $order_create_date) > ($days * 24 * 60 * 60)
                        && ($now_time - $order_create_date) < (($days + 1) * 24 * 60 * 60)
                    ) {
                        $order_list[] = $bt_order_obj;
                    }
                }
            }

            return $order_list;
        }

        return FALSE;
    }

    public function get_so_bank_transfer_dao()
    {
        return $this->so_bank_transfer_dao;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function cancel_unpaid($platform_id)
    {
        /* ==========================================
            sbf #3676
            Put orders on hold (for customers who paid $0 after 7 days), allow grace period for finance processing
            in case of unknown payment verification or bank hols.
            However, we send email to tell customers their order has been cancelled because didn't pay for 7 days.
            After grace period, we keep order on hold but update so_hold_reason.
         =========================================== */

        $this->now_time = time();
        $this->set_hold($platform_id);
        $this->set_hold_aft_grace($platform_id);

    }

    private function set_hold($platform_id)
    {
        $type = "hold_unpaid";
        if (!($this->days = $this->get_days($platform_id, $type))) {
            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\nWebsite bank transfer [platform_id $platform_id] - Fail to run cancel_unpaid. Missing number of days.";
            $this->send_notification_email($platform_id, "MD", $error_msg);
            echo $error_msg;
            exit;
        }

        $debug = FALSE;

        include_once(APPPATH . "libraries/service/Pmgw.php");
        $pmgw = new Pmgw();
        $so_dao = $this->get_so_dao();
        $sops_dao = $this->get_so_payment_status_dao();
        $so_bank_transfer_dao = $this->get_so_bank_transfer_dao();
        $order_notes_dao = $this->get_order_notes_dao();
        $so_hold_reason_dao = $this->get_so_hold_reason_dao();

        if (($bank_transfer_order_list = $this->get_order_list_for_email($platform_id, $type)) !== FALSE) {
            $i = 0;
            foreach ($bank_transfer_order_list as $bt_order_obj) {
                $status = TRUE;
                $so_no = $bt_order_obj->get_so_no();
                $i++;

                if ($so_obj = $so_dao->get(array("so_no" => $so_no))) {
                    $pmgw->so = $so_obj;

                    # update so.status
                    if ($debug === FALSE)
                        $so_obj->set_hold_status(2); # put on hold

                    if ($so_dao->update($so_obj) === FALSE) {
                        $status = FALSE;
                        $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid] Fail to update db so table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                        $this->send_notification_email($platform_id, "UF", $error_msg);
                        echo $error_msg;
                        continue;
                    }

                    # insert order_notes tb
                    if ($order_notes_obj = $order_notes_dao->get()) {
                        $note = "w_bank_transfer: unpaid and order_create_date > {$this->days} - sent cancellation email (put on-hold for backend processing).";
                        $order_notes_obj->set_so_no($so_no);
                        $order_notes_obj->set_type('O');
                        $order_notes_obj->set_note($note);
                        if ($order_notes_dao->insert($order_notes_obj) === FALSE) {
                            $status = FALSE;
                            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid] Fail to update db order_notes table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email($platform_id, "UF", $error_msg);
                            echo $error_msg;
                            continue;
                        }
                    }

                    # insert so_hold_reason tb
                    if ($so_hold_reason_obj = $so_hold_reason_dao->get()) {
                        $so_hold_reason_obj->set_so_no($so_no);
                        $so_hold_reason_obj->set_reason("unpaid_web_bank_transfer");
                        if ($so_hold_reason_dao->insert($so_hold_reason_obj) === FALSE) {
                            $status = FALSE;
                            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid] Fail to update db so_hold_reason table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email($platform_id, "UF", $error_msg);
                            echo $error_msg;
                            continue;
                        }
                    }

                    echo "<br>$i. HOLD UNPAID SUCCESS. so_no=$so_no; client_id={$bt_order_obj->get_client_id()}; client_email={$bt_order_obj->get_email()} ";
                    $pmgw->fire_cancel_order_event("unpaid"); # tell customers their order has been cancelled.
                } else {
                    $status = FALSE;
                    $error_msg = "\nFile " . __FILE__ . "\nLine " . __LINE__ . "\nFail to hold unpaid order for so_no: {$so_no}. so_no does not exist. \nDB error: " . $this->db->_error_message();
                    $this->send_notification_email($platform_id, "UF", $error_msg);
                    echo $error_msg;
                    continue;
                }
            }
        }
    }

    public function get_so_payment_status_dao()
    {
        return $this->so_payment_status_dao;
    }

    public function get_order_notes_dao()
    {
        return $this->order_notes_dao;
    }

    public function get_so_hold_reason_dao()
    {
        return $this->so_hold_reason_dao;
    }

    private function set_hold_aft_grace($platform_id)
    {
        $type = "hold_unpaid_aft_grace";
        if (!($this->days = $this->get_days($platform_id, $type))) {
            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\nWebsite bank transfer [platform_id $platform_id] - Fail to run set_hold_aft_grace. Missing number of days.";
            $this->send_notification_email($platform_id, "MD", $error_msg);
            echo $error_msg;
            exit;
        }

        $debug = FALSE;

        include_once(APPPATH . "libraries/service/Pmgw.php");
        $pmgw = new Pmgw();
        $so_dao = $this->get_so_dao();
        $sops_dao = $this->get_so_payment_status_dao();
        $so_bank_transfer_dao = $this->get_so_bank_transfer_dao();
        $order_notes_dao = $this->get_order_notes_dao();
        $so_hold_reason_dao = $this->get_so_hold_reason_dao();

        # get list of on-hold orders
        if (($bank_transfer_order_list = $this->get_on_hold_order_list($platform_id, $type)) !== FALSE) {
            echo "<hr></hr>";
            $i = 0;
            foreach ($bank_transfer_order_list as $bt_order_obj) {
                $status = TRUE;
                $so_no = $bt_order_obj->get_so_no();
                $i++;

                if ($so_obj = $so_dao->get(array("so_no" => $so_no))) {
                    $pmgw->so = $so_obj;

                    # update so.status
                    if ($debug === FALSE) {
                        $so_obj->set_hold_status(2); # on hold
                    }

                    if ($so_dao->update($so_obj) === FALSE) {
                        $status = FALSE;
                        $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid_aft_grace] Fail to update db so table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                        $this->send_notification_email($platform_id, "UF", $error_msg);
                        echo $error_msg;
                        continue;
                    }

                    # update so_bank_transfer.sbt_status
                    if ($sbt_obj = $so_bank_transfer_dao->get(array("so_no" => $so_no))) {
                        if ($debug === FALSE)
                            $sbt_obj->set_sbt_status(0); # Inactive

                        if ($so_bank_transfer_dao->update($sbt_obj) === FALSE) {
                            $status = FALSE;
                            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid_aft_grace] Fail to update db so_bank_transfer table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email($platform_id, "UF", $error_msg);
                            echo $error_msg;
                            continue;
                        }
                    }

                    # insert so_hold_reason tb
                    if ($so_hold_reason_obj = $so_hold_reason_dao->get()) {
                        $so_hold_reason_obj->set_so_no($so_no);
                        $so_hold_reason_obj->set_reason("unpaid_web_bank_transfer_aft_grace_period");
                        if ($so_hold_reason_dao->insert($so_hold_reason_obj) === FALSE) {
                            $status = FALSE;
                            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid_aft_grace] Fail to update db so_hold_reason table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email($platform_id, "UF", $error_msg);
                            echo $error_msg;
                            continue;
                        }
                    }

                    # update order_notes tb
                    if ($order_notes_obj = $order_notes_dao->get()) {
                        $note = "w_bank_transfer: unpaid past grace period of {$this->days} - any changes needed pls inform BD.";
                        $order_notes_obj->set_so_no($so_no);
                        $order_notes_obj->set_type('O');
                        $order_notes_obj->set_note($note);
                        if ($order_notes_dao->insert($order_notes_obj) === FALSE) {
                            $status = FALSE;
                            $error_msg = "File " . __FILE__ . "\nLine " . __LINE__ . "\n[hold_unpaid_aft_grace] Fail to update db order_notes table. so_no: {$so_no}. \nDB error: " . $this->db->_error_message();
                            $this->send_notification_email($platform_id, "UF", $error_msg);
                            echo $error_msg;
                            continue;
                        }
                    }

                    echo "<br>$i. HOLD (AFT GRACE PERIOD) ORDER SUCCESS. so_no=$so_no; client_id={$bt_order_obj->get_client_id()}; client_email={$bt_order_obj->get_email()} ";
                } else {
                    $status = FALSE;
                    $error_msg = "\nFile " . __FILE__ . "\nLine " . __LINE__ . "\nFail to cancel payment for so_no: {$so_no}. so_no does not exist. \nDB error: " . $this->db->_error_message();
                    $this->send_notification_email($platform_id, "UF", $error_msg);
                    echo $error_msg;
                    continue;
                }
            }
        }
    }

    private function get_on_hold_order_list($platform_id = "WEBES", $type)
    {
        $so_bank_transfer_dao = $this->get_so_bank_transfer_dao();
        $option["limit"] = -1;
        $order_list = array();
        $days = $this->days;

        if ($this->now_time)
            $now_time = $this->now_time;
        else
            $now_time = time();

        $where["so.platform_id"] = $platform_id;

        if ($bank_transfer_order_list = $so_bank_transfer_dao->get_so_bank_transfer_list($where, $option, "unpaid_on_hold")) {
            foreach ($bank_transfer_order_list as $bt_order_obj) {
                if ($type == 'hold_unpaid_aft_grace') {
                    $order_create_date = strtotime($bt_order_obj->get_order_create_date());

                    # more than 14 days; make order on permanent hold
                    if (($now_time - $order_create_date) > ($days * 24 * 60 * 60)) {
                        $order_list[] = $bt_order_obj;
                    }
                }
            }

            return $order_list;
        }

        return FALSE;
    }

    public function get_pmgw()
    {
        return $this->pmgw;
    }

    public function set_pmgw()
    {
        $this->pmgw = $dao;
    }

    public function get($where = array())
    {
        return $this->get_dao()->get($where);
    }

    public function insert($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->get_dao()->update($obj);
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_list($where, $option);
    }
}
