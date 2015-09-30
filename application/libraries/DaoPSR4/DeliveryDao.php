<?php
namespace ESG\Panther\Dao;

class DeliveryDao extends BaseDao
{
    private $tableName = "delivery";
    private $voClassName = "DeliveryVo";

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

    public function getLatency($delivery_type_id, $country_id)
    {
        $sql = "
                SELECT GREATEST(COALESCE(d.max_day,0),COALESCE(d.min_day,0)) AS latency
                FROM delivery AS d
                WHERE d.delivery_type_id = ?
                    AND d.country_id = ?
                LIMIT 1
                ";
        if ($query = $this->db->query($sql, [$delivery_type_id, $country_id])) {
            return $query->row()->latency;
        } else {
            return FALSE;
        }
    }
}


