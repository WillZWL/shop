<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Fulfillment_centre_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Fulfillment_centre_dao.php");
        $this->set_dao(new Fulfillment_centre_dao());
    }
}

/* End of file fulfillment_centre_service.php */
/* Location: ./system/application/libraries/service/Fulfillment_centre_service.php */