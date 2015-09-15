<?php
use ESG\Panther\Models\Auth\AuthModel;

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authModel = new AuthModel;
    }

    public function external_logout($last_session_id)
    {
        $this->setSession($last_session_id);
        session_destroy();
    }

    public function setSession($last_session_id)
    {
        session_id($last_session_id);
    }

    public function index()
    {
        $this->authModel->auth($this->input->post("user_id"), $this->input->post("password"));

        if ($this->authModel->checkAuthed()) {
            if ($back = $this->input->get("back")) {
                redirect(urldecode($back));
            } else {
                redirect(base_url());
            }
        } else {
            $data["err_msg"] = "Wrong username / password!";
            $this->deny($data);
        }
    }

    public function deny($data = [])
    {
        $this->load->view("login.php", $data);
    }

    public function check_authed()
    {
        var_dump($this->authModel->checkAuthed());
    }

    public function deauth()
    {
        $this->authModel->deauthUser();
        $data["err_msg"] = "You have logout successfully!";
        $this->deny($data);
    }

    public function getRandomString($length = 40, $char_set = 0)
    {
        if (!is_int($length) || $length < 1) {
            trigger_error('Invalid length for random string');
            exit();
        }
        if ($char_set == 0) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        } else {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-!:";
        }
        $randstring = '';
        $maxvalue = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randstring .= substr($chars, rand(0, $maxvalue), 1);
        }

        return $randstring;
    }

}
