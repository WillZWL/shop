<?php
namespace ESG\Panther\Service;

use AtomV2\Dao\UserDao;
use AtomV2\Dao\UserRoleDao;
use AtomV2\Dao\AuditLogDao;
use AtomV2\Service\ContextConfigService;

class AuthenticationService extends BaseService
{
    public function __construct()
    {
        $this->setUserDao(New UserDao);
        $this->setUserRoleDao(New UserRoleDao);
        $this->setAuditLogDao(New AuditLogDao);
        $this->contextConfigService = new ContextConfigService;
    }

    public function authUser($user_id = "", $password = "", $salt = "")
    {
        if (!($user_id == "" && $password == "")) {
            $user_obj = $this->getUserDao()->get(["id" => $user_id, "status" => 1]);
            $_SESSION["vo"] = $user_obj;
            $audit_log_obj = $this->getAuditLogDao()->get();
            $audit_log_obj->setUserId($user_id);
            $audit_log_obj->setIpAddress($_SERVER["REMOTE_ADDR"]);
            if (!empty($user_obj)) {
                if ($user_obj->getFailedAttempt() >= $this->contextConfigService->valueOf("max_failed_attempt")) {
                    if ($last_failed_obj = $this->getAuditLogDao()->getList(["user_id" => $user_id, "status" => 0], ["orderby" => "create_on DESC", "limit" => 1])) {
                        if (mktime() < strtotime($last_failed_obj->get_create_on()) + $this->contextConfigService->valueOf("failed_wait_time") * 60) {
                            redirect($this->contextConfigService->valueOf("failed_redirect_page"));
                            return FALSE;
                        }
                    }
                }

                if ((($salt != "") && (md5($user_obj->getPassword() . $salt) == $password))
                    ||
                    (($salt == "") && ($user_obj->getPassword() == md5($password)))
                ) {
                    $class_methods = get_class_methods($user_obj);
                    foreach ($class_methods as $fct_name) {
                        if (substr($fct_name, 0, 3) == "get") {
                            $rskey = camelcase2underscore(substr($fct_name, 3));
                            if ($rskey == "password")
                                continue;
                            $rsvalue = call_user_func([$user_obj, $fct_name]);
                            $userdata[$rskey] = $rsvalue;
                        }
                    }

                    $user_role_obj = $this->getUserRoleDao()->getList(["user_id" => $userdata["id"]]);
                    $userdata["role_id"] = [];
                    foreach ($user_role_obj as $user_role) {
                        $userdata["role_id"][] = $user_role->getRoleId();
                    }
                    $userdata["authed"] = md5($userdata["id"] . ":" . $userdata["username"]);
                    $user_obj->setFailedAttempt(0);
                    $this->getUserDao()->update($user_obj);
                    $audit_log_obj->setStatus(1);
                    $this->getAuditLogDao()->insert($audit_log_obj);
                    $_SESSION["user"] = $userdata;
                    var_dump(isset($_SESSION["user"]["id"]));
                    return TRUE;
                } else {
                    $user_obj->setFailedAttempt($user_obj->getFailedAttempt() + 1);
                    $this->getUserDao()->update($user_obj);
                }
            }
            $audit_log_obj->setStatus(0);
            $this->getAuditLogDao()->insert($audit_log_obj);
            $this->deauthUser();
        }
        return FALSE;
    }

    public function deauthUser()
    {
        session_destroy();
    }

    public function checkAuthed()
    {
        if (isset($_SESSION["user"]["id"])) {
            if (md5($_SESSION["user"]["id"] . ":" . $_SESSION["user"]["username"]) == $_SESSION["user"]["authed"]) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getUserDao()
    {
        return $this->userDao;
    }

    public function setUserDao($value)
    {
        $this->userDao = $value;
    }

    public function getUserRoleDao()
    {
        return $this->UserRoleDao;
    }

    public function setUserRoleDao($value)
    {
        $this->UserRoleDao = $value;
    }

    public function getAuditLogDao()
    {
        return $this->AuditLogDao;
    }

    public function setAuditLogDao($value)
    {
        $this->AuditLogDao = $value;
    }
}
