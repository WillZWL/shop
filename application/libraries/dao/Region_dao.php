<?php

include_once 'Base_dao.php';

class Region_dao extends Base_dao
{
    private $table_name="region";
    private $vo_classname="Region_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_region_by_name_and_type($region_name="",$type,$id,$option=array())
    {
        $this->include_vo();
        $showwhere = 1;
        $sql = 'SELECT *
                FROM region r';

        $where = 0;
        if($id != "")
        {
            $sql .= " WHERE r.id = '".$id."' ";
            $where++;
        }

        if($region_name != "")
        {
            $sql .= ($where?" AND":" WHERE").' r.region_name LIKE \'%'.addslashes($region_name).'%\'';
            $where++;
        }

        if($type != "")
        {
            $sql .= ($where?" AND":" WHERE")." r.type = '$type'";
        }


        if($option["orderby"] != "")
        {
            $sql .= ' ORDER BY '.$option["orderby"];
        }

        if($option["limit"] != "")
        {
            $sql .= ' LIMIT '.($option["offset"] != ""?$option["offset"].", ":"").$option["limit"];
        }

        $rs = array();
        $cnt = 0;
        if($query = $this->db->query($sql))
        {
            foreach ($query->result($$this->get_vo_classname()) as $obj)
            {
                $cnt++;
                $rs[] = $obj;
            }
            return array("regionlist"=>(object) $rs,"total"=>$cnt);
        }
        else
        {
            //echo mysql_error();
            return array("regionlist"=>FALSE, "count"=>$cnt);
        }

    }

    public function get_dregion($courier_id="", $country="")
    {
        if($courier_id == "" || $country == "")
        {
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

        if($query = $this->db->query($sql,$country))
        {
            return $query->row()->region_id;
        }
        return FALSE;
    }
}
?>