<?php
namespace ESG\Panther\Dao;

class FtpInfoDao extends BaseDao
{
    private $tableName = "ftp_info";
    private $voClassName = "FtpInfoVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}


