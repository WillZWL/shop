<?php

include_once "Base_service.php";

class Subject_domain_detail_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Subject_domain_detail_dao.php");
        $this->set_dao(new Subject_domain_detail_dao());
    }
}

/* End of file subject_domain_detail_service.php */
/* Location: ./app/libraries/service/Subject_domain_detail_service.php */