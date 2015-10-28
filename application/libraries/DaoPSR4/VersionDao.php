<?php
namespace ESG\Panther\Dao;

class VersionDao extends BaseDao
{
    private $table_name = 'version';
    private $vo_class_name = 'VersionVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function __construct()
    {
        parent::__construct();
    }

}