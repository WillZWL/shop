<?php
namespace ESG\Panther\Dao;

class EmailReferralListDao extends BaseDao
{
    private $tableName = "email_referral_list";
    private $voClassname = "EmailReferralListVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}