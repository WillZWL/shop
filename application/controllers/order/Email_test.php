<?php
class Email_test extends MY_Controller
{

    private $app_id="ORD0025";
    private $lang_id="en";

/* ===================================
    READ ME
    * remember to set role_rights for users to view
    * $var is extra parameters for flexible usage in future
    * $success: (payment) 1 = success, 0 = fail
    * $pagetype: file name where the ultimate fire email event is found, e.g. in pmgw.php -> fire_success_event(),

                To add in more case "$pagetype" for your testing, go to the page calling the function that triggers your email,
                and add in $get_email_html parameter,
                refer to pmgw.php: fire_success_event() and fire_fail_event()

=================================== */

    public function __construct()
    {
        parent::__construct();

        $this->load->library('service/pmgw');
        $this->load->library('service/so_service');
        $this->load->library('dao/so_allocate_dao');
        $this->load->library('dao/so_bank_transfer_dao');
        $this->load->library('encrypt');

    }

    public function index($success="", $pagetype="", $so_no = "", $var = "")
    {
        if($success != "" || $pagetype != "" || $so_no="")
        {
            switch ($pagetype)
            {
                case 'pmgw':
                    $this->get_pmgw_email($success, $so_no, $var);
                    break;

                case 'pmgw_wbtrans':
                    #website bank transfer emails
                    $this->get_pmgw_wbtrans_email($success, $so_no, $var);
                    break;

                case 'dispatch':
                    $this->get_dispatch_email($so_no, $var);
                    break;

                default:
                    echo 'Please enter valid $pagetype';
                    break;
            }
        }
        else
        {
            echo "INVALID URL. Please ensure your url has the following parameters:
                    <br>http://admindev.valuebasket.com/order/email_test/index/[payment_result]/[page_type]/[so_no]
                    <br><br><b>[payment_result]</b> - fail = 0, success = 1
                    <br><b>[page_type]</b> - page you call fire_event() from, e.g. pmgw
                    <br><b>[so_no]</b> - the so_no you want to check. In pagetype='pmw', platform_id of SO obj will result
                    different language template.";
        }

    }

    private function get_pmgw_email($success, $so_no, $var = 0)
    {
        if ($success == 1)
        {
            if ($so_obj = $this->so_service->get(array("so_no"=>$so_no)))
            {
                $this->pmgw->so = $so_obj;
            }

            $email_msg = $this->pmgw->fire_success_event($var, TRUE);
        }
        elseif ($success == 0)
        {
            if ($so_obj = $this->so_service->get(array("so_no"=>$so_no)))
            {
                $this->pmgw->so = $so_obj;
                $email_msg = $this->pmgw->fire_fail_event(TRUE);
            }
        }

        if($email_msg)
        {
            echo $email_msg;
        }
        else
        {
            echo "Problem getting email msg.";
        }
    }

    private function get_pmgw_wbtrans_email($success, $so_no, $var = "acknowledge_order")
    {
        switch ($success)
        {
            case 1:
                # $var -> "acknowledge_order", "reminder_no_payment", "reminder_partial_payment"

                if ($so_obj = $this->so_service->get(array("so_no"=>$so_no)))
                {
                    $this->pmgw->so = $so_obj;
                }

                if($so_bank_transfer_list = $this->so_bank_transfer_dao->get_so_bank_transfer_list(array("so.so_no"=>$so_no)))
                {
                    foreach ($so_bank_transfer_list as $so_bank_transfer_obj)
                    {
                        $this->pmgw->so_bank_transfer_obj = $so_bank_transfer_obj;
                    }
                }

                $email_msg = $this->pmgw->fire_collect_payment_event($var, TRUE);
                break;


            case 0:
                # cancel order email
                # $var -> "unpaid",
                if ($so_obj = $this->so_service->get(array("so_no"=>$so_no)))
                {
                    $this->pmgw->so = $so_obj;
                }

                $email_msg = $this->pmgw->fire_cancel_order_event($var, TRUE);

                break;

            default:
                # code...
                break;
        }


        if($email_msg)
        {
            echo $email_msg;
        }
        else
        {
            echo "Problem getting email msg.";
        }
    }

    private function get_dispatch_email($so_no, $var="")
    {
        if ($so_obj = $this->so_service->get(array("so_no"=>$so_no)))
        {
            $so_allocate_obj = $this->so_allocate_dao->get(array("so_no"=>$so_no));
            if($sh_no = $so_allocate_obj->get_sh_no())
            {
                $email_msg = $this->so_service->fire_dispatch($so_obj, $sh_no, TRUE);
            }
            // var_dump($sh_no);die();
        }
        if($email_msg)
        {
            echo $email_msg;
        }
        else
        {
            echo "Problem getting email msg.";
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }



}

