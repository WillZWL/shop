<?php
class Client_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/client_service');
    }

    public function forget_password($email = '')
    {
        return $this->client_service->reset_password($email);
    }

    public function update_password($email = '', $new_password = '', $old_password = '')
    {
        return $this->client_service->update_password($email, $new_password, $old_password);
    }

    public function register_success_event($obj)
    {
        return $this->client_service->register_success_event($obj);
    }

    public function get_new_vip_customer_list()
    {
        return $this->client_service->get_new_vip_customer_list();
    }

    public function get_client($where = array())
    {
        return $this->client_service->get($where);
    }

    public function update_client($client_obj)
    {
        return $this->client_service->update($client_obj);
    }

    public function include_vo()
    {
        $this->client_service->include_vo();
    }
}
?>