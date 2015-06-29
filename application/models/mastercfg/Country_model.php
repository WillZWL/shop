<?php

class Country_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("service/country_service");
    }

    public function get_sell_to_list($lang_id = 'en')
    {
        return $this->country_service->get_dao()->get_all_available_country_w_correct_lang($lang_id);
    }

    public function get($dao,$where="")
    {
        $method = "get_".$dao."_dao";
        if(is_array($where))
        {
            return $this->country_service->$method()->get($where);
        }
        else
        {
            return $this->country_service->$method()->get();
        }
    }

    public function get_list($dao,$where=array(),$option=array())
    {
        $method = "get_".$dao."_dao";
        if($option["num_rows"] == 1)
        {
            return $this->country_service->$method()->get_num_rows($where);
        }
        else
        {
            return $this->country_service->$method()->get_list($where,$option);
        }
    }

    public function get_list_w_rma_fc($where=array(), $option=array())
    {
        return $this->country_service->get_dao()->get_list_w_rma_fc($where,$option);
    }

    public function update($dao,$obj)
    {
        $method = "get_".$dao."_dao";
        return $this->country_service->$method()->update($obj);
    }

    public function insert($dao,$obj)
    {
        $method = "get_".$dao."_dao";
        return $this->country_service->$method()->insert($obj);
    }

    public function get_country_name_in_lang($lang_id = "", $front_end = "", $platform_restricted = "")
    {
        $where["l.id"] = $lang_id;

        if ($front_end)
        {
            $where["c.status"] = 1;
            $where["c.allow_sell"] = 1;
        }

        switch ($platform_restricted)
        {
            case "WSUS":
            case "WEBUS":
                $where["c.id"] = PLATFORMCOUNTRYID;
                break;
        }

        $option["orderby"] = "ce.name, c.name";
        $option["limit"] = -1;

        return $this->country_service->get_country_ext_dao()->get_country_name_in_lang($where, $option);
    }

    public function get_rma_fc_list($lang="en")
    {
        return $this->country_service->get_dao()->get_rma_country_list($lang);
    }

}
?>