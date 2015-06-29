<?php

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/User_service');
        $this->load->library('service/Role_service');
        $this->load->library('service/User_role_service');
    }

    public function get_list_w_roles($where=array(), $option=array())
    {
        return $this->user_service->get_list_w_roles($where, $option);
    }

    public function get_user($where=array())
    {
        return $this->user_service->get($where);
    }

    public function get_user_role($where=array())
    {
        return $this->user_role_service->get($where);
    }

    public function get_user_role_list($where=array())
    {
        return $this->user_role_service->get_list($where);
    }

    public function inactive_user(Base_vo $user_vo)
    {
        return $this->user_service->inactive_user($user_vo);
    }

    public function del_user_role($where)
    {
        return $this->user_role_service->q_delete($where);
    }

    public function include_user_vo()
    {
        return $this->user_service->include_vo();
    }

    public function include_user_role_vo()
    {
        return $this->user_role_service->include_vo();
    }

    public function get_role_list($where=array())
    {
        return $this->role_service->get_list();
    }

    public function add_user($obj)
    {
        return $this->user_service->insert($obj);
    }

    public function add_user_role(Base_vo $obj){
        return $this->user_role_service->insert($obj);
    }

    public function update_user($obj)
    {
        return $this->user_service->update($obj);
    }

}

/* End of file user_model.php */
/* Location: ./system/application/models/user_model.php */