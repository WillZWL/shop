<?php

include_once "Base_service.php";

class Customer_service_info_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Customer_service_info_dao.php");
        $this->set_dao(new Customer_service_info_dao());
    }

    public function get_short_text($platform_id = "WEBHK")
    {
        return $this->get_dao()->get_short_text($platform_id);
    }

    public function get_cs_contact_list_by_country($where = array())
    {
        return $this->get_dao()->get_cs_contact_list_by_country($where);
    }
}

/* End of file customer_service_info_dao.php */
/* Location: ./app/libraries/dao/Customer_service_info_dao.php */