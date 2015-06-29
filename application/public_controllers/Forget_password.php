<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

Class Forget_password extends PUB_Controller
{
    public function Forget_password()
    {
        DEFINE("SKIPCUR", 1);
        parent::PUB_Controller();
        $this->load->helper('url');
        $this->load->model('website/client_model');
    }

    public function index()
    {
        $email = $this->input->get('email');
        $no_user = $this->input->get('no_user');
        $data["back"] = $this->input->get("back");

        if (empty($email)) {
            $data['no_user'] = 1;
        } else {
            $data['no_user'] = abs($this->client_model->forget_password($email) - 1);
        }

        $this->load_view('forget_password.php', $data);
    }
}


