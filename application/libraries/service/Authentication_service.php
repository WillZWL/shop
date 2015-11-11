<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once "Base_service.php";

class Authentication_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        $CI = &get_instance();
        $this->load = $CI->load;
    }

    public function auth_user($user_id = "", $password = "", $salt = "")
    {
        if (!($user_id == "" && $password == "")) {
            include_once(APPPATH . "libraries/dao/User_dao.php");
            $user_dao = new User_dao();
            include_once(APPPATH . "libraries/dao/Audit_log_dao.php");
            $audit_log_dao = new Audit_log_dao();
            $user_obj = $user_dao->get(array("id" => $user_id, "status" => 1));
            $_SESSION["vo"] = $user_obj;
            $audit_log_obj = $audit_log_dao->get();
            $audit_log_obj->set_user_id($user_id);
            $audit_log_obj->set_ip_address($_SERVER["REMOTE_ADDR"]);
            if (!empty($user_obj)) {
                include_once(APPPATH . "libraries/service/Context_config_service.php");
                $cconfig = new Context_config_service();

                if ($user_obj->get_failed_attempt() >= $cconfig->value_of("max_failed_attempt")) {
                    if ($last_failed_obj = $audit_log_dao->get_list(array("user_id" => $user_id, "status" => 0), array("orderby" => "create_on DESC", "limit" => 1))) {
                        if (mktime() < strtotime($last_failed_obj->get_create_on()) + $cconfig->value_of("failed_wait_time") * 60) {
                            redirect($cconfig->value_of("failed_redirect_page"));
                            return FALSE;
                        }
                    }
                }

                if ((($salt != "") && (md5($user_obj->get_password() . $salt) == $password))
                    ||
                    (($salt == "") && ($user_obj->get_password() == md5($password)))
                ) {
                    $class_methods = get_class_methods($user_obj);
                    foreach ($class_methods as $fct_name) {
                        if (substr($fct_name, 0, 4) == "get_") {
                            $rskey = substr($fct_name, 4);
                            if ($rskey == "password")
                                continue;
                            $rsvalue = call_user_func(array($user_obj, $fct_name));
                            $userdata[$rskey] = $rsvalue;
                        }
                    }
                    include_once(APPPATH . "libraries/dao/User_role_dao.php");
                    $user_role_dao = new User_role_dao();
                    $user_role_obj = $user_role_dao->get_list(array("user_id" => $userdata["id"]));
                    $userdata["role_id"] = array();
                    foreach ($user_role_obj as $user_role) {
                        $userdata["role_id"][] = $user_role->get_role_id();
                    }
                    $userdata["authed"] = md5($userdata["id"] . ":" . $userdata["username"]);
                    $user_obj->set_failed_attempt(0);
                    $user_dao->update($user_obj);
                    $audit_log_obj->set_status(1);
                    $audit_log_dao->insert($audit_log_obj);

                    $_SESSION["user"] = $userdata;
                    var_dump(isset($_SESSION["user"]["id"]));
                    return TRUE;
                } else {
                    $user_obj->set_failed_attempt($user_obj->get_failed_attempt() + 1);
                    $user_dao->update($user_obj);
                }
            }
            $audit_log_obj->set_status(0);
            $audit_log_dao->insert($audit_log_obj);
            $this->deauth_user();
        }
        return FALSE;
    }

    public function deauth_user()
    {
        session_destroy();
    }

    public function check_authed()
    {
        if (isset($_SESSION["user"]["id"])) {
            if (md5($_SESSION["user"]["id"] . ":" . $_SESSION["user"]["username"]) == $_SESSION["user"]["authed"]) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
