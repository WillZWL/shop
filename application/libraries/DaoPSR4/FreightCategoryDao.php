<?php
namespace AtomV2\Dao;

class FreightCategoryDao extends BaseDao
{
    private $tableName = "freight_category";
    private $voClassName = "FreightCategoryVo";

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
        return $this->voClassName;
    }

    public function getCatWithRegion($where = [], $option = [], $classname = "")
    {

        $this->include_dto($classname);

        $this->db->select("id AS cat_id, name AS cat_name, weight");

        if (isset($option["orderby"])) {
            $this->db->order_by($option["orderby"]);
        }

        if (empty($option["limit"])) {
            $option["limit"] = $this->rows_limit;
        } elseif ($option["limit"] == -1) {
            $option["limit"] = "";
        }

        if (!isset($option["offset"])) {
            $option["offset"] = 0;
        }

        if ($query = $this->db->get_where($this->getTableName(), $where, $option["limit"], $option["offset"])) {
            $rs = [];
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            if ($option["limit"] == 1) {
                return $rs[0];
            } else {
                return (object)$rs;
            }
        } else {
            return FALSE;
        }
    }
}
