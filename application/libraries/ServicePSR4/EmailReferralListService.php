<?php
namespace ESG\Panther\Service;
use ESG\Panther\Dao\EmailReferralListDao;

class EmailReferralListService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new EmailReferralListDao);
    }
}


