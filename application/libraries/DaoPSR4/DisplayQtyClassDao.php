<?php
namespace ESG\Panther\Dao;

class DisplayQtyClassDao extends BaseDao
{
    private $tableName = "display_qty_class";
    private $voClassname = "DisplayQtyClassVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}


