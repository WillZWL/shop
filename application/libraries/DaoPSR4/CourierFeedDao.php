<?php
namespace ESG\Panther\Dao;

class CourierFeedDao extends BaseDao
{
    private $tableName = "courier_feed";
    private $voClassName = "CourierFeedVo";

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

    public function getAutoIncrementId()
    {
        $this->db->select('AUTO_INCREMENT id');
        $this->db->where(["TABLE_NAME"=>$this->getTableName()]);
        $query = $this->db->get("information_schema.tables");


        if ($query)
        {
            foreach ($query->result() as $row)
            {
               return $row->id;
            }
        }

        return FALSE;
    }
}
