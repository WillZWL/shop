<?php

include_once "Base_service.php";

class Application_feature_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Application_feature_dao.php");
        $this->set_dao(new Application_feature_dao());
    }

    public function get_application_feature_access_right($app_id)
    {
        $where = array();
        $where['role_id'] = $_SESSION['user']['role_id'];
        $where['app_id'] = $app_id;
        return $this->get_dao()->get_application_feature_access_right($where);
    }
}
