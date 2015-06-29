<?php

class Festive_deal_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("service/festive_deal_service");
    }

    public function get($dao="",$where=array())
    {
        $method = "get_".$dao;
        return $this->festive_deal_service->$method()->get($where);
    }

    public function get_list($dao="",$where=array(),$option=array())
    {
        $method = "get_".$dao;
        return $this->festive_deal_service->$method()->get_list($where,$option);
    }

    public function insert($dao="",$obj)
    {
        $method = "get_".$dao;
        return $this->festive_deal_service->$method()->insert($obj);
    }

    public function update($dao="",$obj, $where=array())
    {
        $method = "get_".$dao;
        return $this->festive_deal_service->$method()->update($obj,$where);
    }

    public function delete($dao="", $where=array())
    {
        $method = "get_".$dao;
        return $this->festive_deal_service->$method()->q_delete($where);
    }

    public function get_fd_detail($obj="",$imagepath)
    {
        return $this->festive_deal_service->get_fds_detail($obj,$imagepath);
    }

    public function verify_festive_link_id($festive="",$link_id="")
    {
        return $this->festive_deal_service->verify_festive_link_id($festive,$link_id);
    }

    public function get_fdsc_detail($list=array(),$platform_id='WSGB', $lang_id='en')
    {
        return $this->festive_deal_service->get_fdssc_detail($list, $platform_id,$lang_id);
    }
}

