<?php
namespace AtomV2\Service;

use AtomV2\Dao\ApplicationFeatureDao;

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
        return $this->getDao()->getApplicationFeatureAccessRight($where);
    }
}
