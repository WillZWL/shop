<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ApplicationFeatureDao;

class ApplicationFeatureService extends BaseService
{
    public function __construct()
    {
        $this->setDao(new ApplicationFeatureDao);
    }

    public function getApplicationFeatureAccessRight($appId)
    {
        $where = array();
        $where['role_id'] = $_SESSION['user']['role_id'];
        $where['app_id'] = $appId;
        return $this->getDao('ApplicationFeature')->getApplicationFeatureAccessRight($where);
    }
}
