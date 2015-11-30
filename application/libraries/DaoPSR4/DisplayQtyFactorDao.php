<?php
namespace ESG\Panther\Dao;

class DisplayQtyFactorDao extends BaseDao
{
    private $tableName = "display_qty_factor";
    private $voClassname = "DisplayQtyFactorVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

}
