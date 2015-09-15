<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\BaseDao;
use ESG\Panther\Service\UserService;
use ESG\Panther\Service\ApplicationFeatureService;

class AuthorizationService extends BaseService
{
    private $userService;
    private $user_srv;
    private $_appFeatureService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService;
        $this->_appFeatureService = new ApplicationFeatureService;
    }

    public function userMenuItem($userId = "")
    {
        return $this->userService->menuItem($userId, "Role_app_dto");
    }

    public function userAppRights($appId = "")
    {
        return $this->userService->appRights($_SESSION["user"]["id"], $appId, "Role_access_dto");
    }

    public function checkAccessRights($service = "", $rights = "", $show_error = 1)
    {
        if (!$this->userService->checkAccess($_SESSION["user"]["id"], $service, $rights)) {
            if ($show_error) {
                show_error("Access Denied!");
            } else {
                return FALSE;
            }
        }

        return TRUE;
    }

    public function setApplicationFeatureRight($appId)
    {
        $feature_right_obj = $this->_appFeatureService->getApplicationFeatureAccessRight($appId);
        $enabledFeature = [];
        foreach ($feature_right_obj as $feature) {
            array_push($enabledFeature, $feature->get_feature_name());
        }
        unset($_SESSION['user']['app_feature']);
        return $_SESSION['user']['app_feature'][$appId] = $enabledFeature;
    }
}
