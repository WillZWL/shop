<?php
class Auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/Authentication_service');
    }

    public function external_auth($user_id, $password, $salt)
    {
        return $this->authentication_service->auth_user($user_id, $password, $salt);
    }

    public function auth($user_id, $password)
    {
        return $this->authentication_service->auth_user($user_id, $password);
    }

    public function check_authed()
    {
        return $this->authentication_service->check_authed();
    }

    public function deauth_user()
    {
        return $this->authentication_service->deauth_user();
    }

}
