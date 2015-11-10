<?php
namespace ESG\Panther\Dao;

class RegionDao extends BaseDao
{
    private $tableName = "region";
    private $voClassName = "RegionVo";

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

    public function getRegionByNameAndType($region_name = "", $type, $id, $option = [])
    {
        //$this->include_vo();
        $showwhere = 1;
        $sql = 'SELECT *
                FROM region r';

        $where = 0;
        if ($id != "") {
            $sql .= " WHERE r.id = '" . $id . "' ";
            $where++;
        }

        if ($region_name != "") {
            $sql .= ($where ? " AND" : " WHERE") . ' r.region_name LIKE \'%' . addslashes($region_name) . '%\'';
            $where++;
        }

        if ($type != "") {
            $sql .= ($where ? " AND" : " WHERE") . " r.type = '$type'";
        }

        if (!empty($option["orderby"])) {
            $sql .= ' ORDER BY ' . $option["orderby"];
        }

        if (!empty($option["limit"])) {
            $sql .= ' LIMIT ' . ($option["offset"] != "" ? $option["offset"] . ", " : "") . $option["limit"];
        }

        $rs = [];
        $cnt = 0;
        if ($query = $this->db->query($sql)) {
            foreach ($query->result($this->getVoClassname()) as $obj) {
                $cnt++;
                $rs[] = $obj;
            }
            return ["regionlist" => (object)$rs, "total" => $cnt];
        } else {
            //echo mysql_error();
            return ["regionlist" => FALSE, "count" => $cnt];
        }

    }

    public function get_dregion($courier_id = "", $country = "")
    {
        if ($courier_id == "" || $country == "") {
            return FALSE;
        }

        $sql = "SELECT rc.region_id
                FROM region_country rc
                INNER JOIN(
                    SELECT id
                    FROM region
                    WHERE region_name LIKE '%$courier_id%'
                    AND type='C') AS r
                    ON r.id = rc.region_id
                    AND rc.country_id = ?
                LIMIT 1
                ";

        if ($query = $this->db->query($sql, $country)) {
            return $query->row()->region_id;
        }
        return FALSE;
    }
}
