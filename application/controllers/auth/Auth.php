<?php
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth/auth_model');
		$this->load->helper('url');
	}

	public function set_session($last_session_id)
	{
		session_id($last_session_id);
	}

	public function external_logout($last_session_id)
	{
		$this->set_session($last_session_id);
		session_destroy();
	}

	public function index()
	{
		$this->auth_model->auth($this->input->post("user_id"), $this->input->post("password"));

		if ($this->auth_model->check_authed()) {
			if ($back=$this->input->get("back")) {
				redirect(urldecode($back));
			} else {
				redirect(base_url());
			}
		} else {
			$data["err_msg"] = "Wrong username / password!";
			$this->deny($data);
		}
	}

	public function check_authed()
	{
		var_dump($this->auth_model->check_authed());
	}

	public function deauth()
	{
		$this->auth_model->deauth_user();
		$data["err_msg"] = "You have logout successfully!";
		$this->deny($data);
	}

	public function deny($data = array())
	{
		$this->load->view("login.php", $data);
	}

    public function getRandomString($length=40, $char_set=0)
    {
        if( ! is_int($length) || $length < 1) {
            trigger_error('Invalid length for random string');
            exit();
        }
        if ($char_set == 0) {
            $chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        } else {
            $chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-!:";
        }
        $randstring = '';
        $maxvalue = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randstring .= substr($chars,rand(0,$maxvalue),1);
        }

        return $randstring;
    }

}
