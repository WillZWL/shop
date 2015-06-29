<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Http_info_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Http_info_dao.php");
        $this->set_dao(new Http_info_dao());
    }
}

/* End of file http_info_service.php */
/* Location: ./system/application/libraries/service/Http_info_service.php */