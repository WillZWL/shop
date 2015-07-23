<?php

class Email_referral_list_management extends MY_Controller
{
    private $_app_id = "ORD0024";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/email_referral_list_model');
        $this->load->helper(array('url', 'notice', 'object', 'image', 'operator'));
        $this->load->library('service/email_referral_list_service');
        $this->load->library('service/pagination_service');

    }

    public function index($id = '')
    {

        $_SESSION["LISTPAGE"] = base_url() . "order/email_referral_list_management/index" . "?" . $_SERVER['QUERY_STRING'];
        $sub_app_id = $this->_get_app_id() . "00";


        if ($id) {
            $action = $_POST["post"];
            if ($action == 'update') {
                $email_obj = $this->email_referral_list_service->get_dao()->get(array('id' => $id));
                $edited_email = trim($_POST["email"]);
                $email_obj->set_email($edited_email);
                //var_dump($edited_email);
                $this->email_referral_list_service->get_dao()->update($email_obj);
                $_SESSION["DISPLAY"] = array($email_obj->get_email() . " Update Success", "success");
                //var_dump($this->email_referral_list_service->get_dao()->db->last_query());
            } elseif ($action == 'delete') {
                $email_obj = $this->email_referral_list_service->get_dao()->get(array('id' => $id));
                $email_obj->set_status(0);
                $this->email_referral_list_service->get_dao()->update($email_obj);
                $_SESSION["DISPLAY"] = array($email_obj->get_email() . " Delete Success", "success");
                //var_dump($this->email_referral_list_service->get_dao()->db->last_query());
            }
            redirect($_SESSION["LISTPAGE"]);
        } elseif ($_POST['add']) {
            $email = trim($_POST['new_email']);
            if ($email_obj = $this->email_referral_list_service->get_dao()->get(array('email' => $email, 'status' => 1))) {
                $msg = " Already exists";
            } elseif ($email_obj = $this->email_referral_list_service->get_dao()->get(array('email' => $email, 'status' => 0))) {
                $email_obj->set_status('1');
                $this->email_referral_list_service->get_dao()->update($email_obj);
                $msg = ' Create Success';
            } else {
                $email_obj = $this->email_referral_list_service->get_dao()->get();
                $email_obj->set_email($email);
                $email_obj->set_status(1);
                $msg = ' Create Success';
                $this->email_referral_list_service->get_dao()->insert($email_obj);
            }
            $_SESSION["DISPLAY"] = array($email . $msg, "success");
            redirect($_SESSION["LISTPAGE"]);
            //var_dump($this->email_referral_list_service->get_dao()->db->last_query());
        }
        $where = array();
        $option = array();


        $option['limit'] = 1000;
        $pconfig['per_page'] = $option['limit'];

        $pconfig['total_rows'] = $data['total_rows'] = $this->email_referral_list_model->get_all_email_referral_list($where, array('num_rows' => 1));

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);

        $data['email_referral_list'] = $this->email_referral_list_model->get_all_email_referral_list($hwere, $option);
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);
        $this->load->view('order/email_referral_list_management/index', $data);
    }

    public function _get_app_id()
    {
        return $this->_app_id;
    }

    public function export_csv()
    {
        $where = array();
        $option = array();
        $option['limit'] = -1;
        $data['output'] = $this->email_referral_list_model->get_csv($where, $option);
        $data['filename'] = 'Referral_Email_List.csv';
        $this->load->view('output_csv.php', $data);
    }
}
