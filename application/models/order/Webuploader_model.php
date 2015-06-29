<?php

class Webuploader_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/webuploader_service');
    }

    public function check_input($input=array(), $type="")
    {
        if(!is_array($input) || $type == "")
        {
            return array("status"=>0,"reason"=>"unknown_reason");
        }
        else
        {
            $func = $type."_checker";
            return $this->webuploader_service->$func($input);
        }
    }

    public function process_input($input=array(), $type="")
    {
        if(!is_array($input) || $type == "")
        {
            return FALSE;
        }
        else
        {
            $func = $type."_processor";
            return $this->webuploader_service->$func($input);
        }
    }
}

?>