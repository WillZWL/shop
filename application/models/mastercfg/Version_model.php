<?php

class Version_model extends CI_Model
{

    public function version_model()
    {
        parent::__construct();
        $this->load->library("service/version_service");
    }

    public function get($where = "")
    {
        if(!is_array($where))
        {
            return $this->version_service->get_new();
        }
        else
        {
            return $this->version_service->get($where);
        }
    }

    public function get_list($where=array(), $option = array())
    {
        return $this->version_service->get_list($where, $option);
    }

    public function get_list_w_cnt($where = array(), $option = array())
    {
        return array("vlist"=>$this->get_list($where,$option), "total"=>$this->version_service->get_list_cnt($where));
    }

    public function insert($obj)
    {
        return $this->version_service->insert($obj);
    }

    public function update($obj)
    {
        return $this->version_service->update($obj);
    }

}

?>