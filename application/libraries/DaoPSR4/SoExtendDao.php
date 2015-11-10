<?php
namespace ESG\Panther\Dao;

class SoExtendDao extends BaseDao
{
    private $tableName = "so_extend";
    private $voClassName = "SoExtendVo";

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

    public function getSoExtWithReason($where = [], $option = [], $classname = 'SoExtWithReasonDto')
    {
        $this->db->from("so_extend soex");
        $this->db->join('order_reason ore', 'ore.reason_id = soex.order_reason', 'LEFT');
        $this->db->where($where);
        if (isset($option["limit"])) {
            $this->db->limit($option["limit"]);
            if ($query = $this->db->get()) {
                $result = $query->result($classname);
                if (sizeof($result) == 1)
                    return $result[0];
                else
                    return [];
            }
        } else {
            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        }
    }
}


