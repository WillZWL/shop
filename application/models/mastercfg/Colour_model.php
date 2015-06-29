<?php

class Colour_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/colour_service');
    }

    public function update($obj)
    {
        return $this->colour_service->update($obj);
    }

    public function insert($obj)
    {
        return $this->colour_service->insert($obj);
    }

    public function get($colour_id="")
    {
        if($colour_id == "")
        {
            return $this->colour_service->get(array());
        }
        else
        {
            return $this->colour_service->get(array("id"=>$colour_id));
        }
    }

    public function get_list($where=array(), $option=array())
    {
        return $this->colour_service->get_list($where, $option);
    }
}


?>