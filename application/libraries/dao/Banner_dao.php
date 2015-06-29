<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Banner_dao extends Base_dao
{
    private $table_name="banner";
    private $vo_classname="Banner_vo";
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

    public function update_status($cat_id,$status)
    {
        if($cat_id == "" || !in_array($status,array("I","A")))
        {
            return FALSE;
        }
        else
        {
            $sql = "UPDATE banner
                    SET status = '$status',
                    modify_on = NOW(),
                    modify_at = '".$_SESSION["user"]["modify_at"]."',
                    modify_by = '".$_SESSION["user"]["id"]."'
                    WHERE cat_id = '$cat_id'";
            if($query = $this->db->query($sql))
            {
                return $this->db->affected_rows();
            }
            return FALSE;
        }
    }

    public function get_list_with_name($level="1",$parent="0",$classname="Banner_cat_list_dto")
    {
        $sql = "SELECT c.id, c.name, c.level, IFNULL(pv.pv_cnt,0) AS pv_cnt, IFNULL(pb.pb_cnt,0) AS pb_cnt, IFNULL(stat.status,0) AS status, IFNULL(s.ttl,0) as count_row
                FROM category c
                LEFT JOIN (SELECT cat_id, count(type) AS pv_cnt
                           FROM banner
                           WHERE `usage`='PV'
                           GROUP BY cat_id
                          ) AS pv
                    ON c.id = pv.cat_id
                LEFT JOIN (SELECT cat_id, count(type) AS pb_cnt
                           FROM banner
                           WHERE `usage`='PB'
                           GROUP BY cat_id
                          ) AS pb
                    ON c.id = pb.cat_id
                LEFT JOIN (SELECT cat_id, count(status) AS status
                           FROM banner
                           WHERE `status` = 'A'
                           GROUP BY cat_id
                          ) as stat
                    ON c.id = stat.cat_id
                LEFT JOIN (SELECT cc.parent_cat_id, count(cc.id) as ttl
                      FROM category cc
                      GROUP BY cc.parent_cat_id) AS s
                    ON s.parent_cat_id = c.id
                WHERE c.level = $level
                AND c.parent_cat_id = $parent
                AND id <> '0'
                ORDER BY c.name ASC";



        $this->include_dto($classname);

        $rs = array();

        if($query = $this->db->query($sql))
        {
            foreach($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }

            return $rs;
        }

        return FALSE;
    }
}

?>