<?php

class Email_referral_list_management extends MY_Controller
{
    private $appId = "ORD0024";
    private $lang_id = "en";

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
        $sub_app_id = $this->getAppId() . "00";


        if ($id) {
            $action = $_POST["post"];
            if ($action == 'update') {
                $email_obj = $this->sc['EmailReferralList']->getDao('EmailReferralList')->get(['id' => $id]);
                $edited_email = trim($_POST["email"]);
                $email_obj->setEmail($edited_email);
                $this->sc['EmailReferralList']->getDao('EmailReferralList')->update($email_obj);
                $_SESSION["DISPLAY"] = [$email_obj->getEmail() . " Update Success", "success"];
            } elseif ($action == 'delete') {
                $email_obj = $this->sc['EmailReferralList']->getDao('EmailReferralList')->get(['id' => $id]);
                $email_obj->setStatus(0);
                $this->sc['EmailReferralList']->getDao('EmailReferralList')->update($email_obj);
                $_SESSION["DISPLAY"] = [$email_obj->getEmail() . " Delete Success", "success"];
            }
            redirect($_SESSION["LISTPAGE"]);
        } elseif ($_POST['add']) {
            $email = trim($_POST['new_email']);
            if ($email_obj = $this->sc['EmailReferralList']->getDao('EmailReferralList')->get(['email' => $email, 'status' => 1])) {
                $msg = " Already exists";
            } elseif ($email_obj = $this->sc['EmailReferralList']->getDao('EmailReferralList')->get(['email' => $email, 'status' => 0])) {
                $email_obj->setStatus('1');
                $this->sc['EmailReferralList']->getDao('EmailReferralList')->update($email_obj);
                $msg = ' Create Success';
            } else {
                $email_obj = $this->sc['EmailReferralList']->getDao('EmailReferralList')->get();
                $email_obj->setEmail($email);
                $email_obj->setStatus(1);
                $msg = ' Create Success';
                $this->sc['EmailReferralList']->getDao('EmailReferralList')->insert($email_obj);
            }
            $_SESSION["DISPLAY"] = array($email . $msg, "success");
            redirect($_SESSION["LISTPAGE"]);
        }
        $where = [];
        $option = [];


        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        $data['total_rows'] = $this->sc['EmailReferralList']->getAllEmailReferralList($where, ['num_rows' => 1]);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);

        $data['email_referral_list'] = $this->sc['EmailReferralList']->getAllEmailReferralList($hwere, $option);


        $config['base_url'] = base_url('order/email_referral_list_management');
        $config['total_rows'] = $data["total_rows"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $this->load->view('order/email_referral_list_management/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function export_csv()
    {
        $where = [];
        $option = [];
        $option['limit'] = -1;
        $data['output'] = $this->sc['EmailReferralList']->getCsv($where, $option);
        $data['filename'] = 'Referral_Email_List.csv';
        $this->load->view('output_csv.php', $data);
    }
}
