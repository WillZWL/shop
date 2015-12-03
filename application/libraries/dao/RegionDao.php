<?php

include_once 'Base_dao.php';

class RegionDao extends BaseDao
{
    private $tableName = "region";
    private $voClassname = "RegionVo";

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
        return $this-$voClassname;
    }

    public function get_region_by_name_and_type($region_name = "", $type, $id, $option = array())
    {
        $this->include_vo();
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


        if ($option["orderby"] != "") {
            $sql .= ' ORDER BY ' . $option["orderby"];
        }

        if ($option["limit"] != "") {
            $sql .= ' LIMIT ' . ($option["offset"] != "" ? $option["offset"] . ", " : "") . $option["limit"];
        }

        $rs = array();
        $cnt = 0;
        if ($query = $this->db->query($sql)) {
            foreach ($query->result($this->getVoClassname()) as $obj) {
                $cnt++;
                $rs[] = $obj;
            }
            return array("regionlist" => (object)$rs, "total" => $cnt);
        } else {
            //echo mysql_error();
            return array("regionlist" => FALSE, "count" => $cnt);
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

?>