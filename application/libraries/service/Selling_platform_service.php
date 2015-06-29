<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Selling_platform_service extends Base_service
{

    public function __construct(){
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH."libraries/dao/Selling_platform_dao.php");
        $this->set_dao(new Selling_platform_dao());
    }

    public function get_platform_by_lang($where = array(), $option = array())
    {
        return $this->get_dao()->get_platform_by_lang($where, $option);
    }

    public function get_platform_list_w_country_id($country_id = "")
    {
        return $this->get_dao()->get_platform_list_w_country_id($country_id);
    }

    public function get_platform_list_w_lang_id($lang_id = "")
    {
        return $this->get_dao()->get_platform_list_w_lang_id($lang_id);
    }

    public function get_platform_type_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_platform_type_list($where, $option);
    }

    public function get_selling_platform_w_lang_id($where = array(), $option = array())
    {
        return $this->get_dao()->get_selling_platform_w_lang_id($where, $option);
    }

    public function get_platform_list_w_allow_sell_country($type="")
    {
        return $this->get_dao()->get_platform_list_w_allow_sell_country($type);
    }

}

?>