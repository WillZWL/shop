<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class User_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/User_dao.php");
        $this->set_dao(new User_dao());
    }

    public function inactive_user(Base_vo $user_vo)
    {
        $user_vo->set_status(0);
        return $this->get_dao()->update($user_vo);
    }

    public function get_menu_by_group($app_group_id)
    {
        return $this->get_dao()->get_menu_by_user_id($_SESSION["user"]["id"], $app_group_id);
    }

    public function is_allowed_to_cancel_order()
    {
        return $this->get_dao()->is_allowed_to_cancel_order_by_role($_SESSION["user"]["id"]);
    }

    public function menu_item($user_id = "", $classname = "")
    {
        return $this->get_dao()->get_menu_item($user_id, $classname);
    }

    public function app_rights($user_id = "", $app_id = "", $classname = "")
    {
        return $this->get_dao()->get_app_rights($user_id, $app_id, $classname);
    }

    public function check_access($user_id = "", $app_id = "", $rights = "")
    {
        return $this->get_dao()->check_access($user_id, $app_id, $rights);
    }

    public function get_list_w_roles($where = array(), $option = array())
    {
        $data["userlist"] = $this->get_dao()->get_list_w_roles($where, $option, "User_w_roles_dto");
        $data["total"] = $this->get_dao()->get_list_w_roles($where, array("num_rows" => 1));
        return $data;
    }

}
