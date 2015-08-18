<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Authorization_service extends Base_service
{
    private $user_srv;
    private $_app_feature_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/User_service.php");
        include_once(APPPATH . "libraries/service/Application_feature_service.php");
        $this->set_user_srv(new User_service());
        $this->_app_feature_service = new Application_feature_service();
    }

    public function user_menu_item($user_id = "")
    {
        $user_service = $this->get_user_srv();
        return $user_service->menu_item($user_id, "Role_app_dto");
    }

    public function get_user_srv()
    {
        return $this->user_srv;
    }

    public function set_user_srv(Base_service $srv)
    {
        $this->user_srv = $srv;
    }

    public function user_app_rights($app_id = "")
    {
        $user_service = $this->get_user_srv();
        return $user_service->app_rights($_SESSION["user"]["id"], $app_id, "Role_access_dto");
    }

    public function check_access_rights($service = "", $rights = "", $show_error = 1)
    {
        $user_service = $this->get_user_srv();
        if (!$user_service->check_access($_SESSION["user"]["id"], $service, $rights)) {
            if ($show_error) {
                show_error("Access Denied!");
            } else {
                return FALSE;
            }
        }

        return TRUE;
    }

    public function set_application_feature_right($app_id)
    {
        $feature_right_obj = $this->_app_feature_service->get_application_feature_access_right($app_id);
        $enabled_feature = array();
        foreach ($feature_right_obj as $feature) {
            array_push($enabled_feature, $feature->get_feature_name());
        }
        unset($_SESSION['user']['app_feature']);
        return $_SESSION['user']['app_feature'][$app_id] = $enabled_feature;
    }
}
