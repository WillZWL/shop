<?php defined('BASEPATH') OR exit('No direct script access allowed');

include "base_dao.php";

Class Adbanner_dao extends Base_dao
{

    private $table_name="adbanner";
    private $vo_classname="Adbanner_vo";
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

    public function get_list_with_name($where, $classname)
    {
        $result = $this->include_dto($classname);
        $sql = 'SELECT a.name, a.level, b.*
                FROM category a
                LEFT JOIN adbanner b
                    ON a.id = b.cat_id
                WHERE a.id = \''.$where["cat_id"].'\'
                ORDER BY a.id
                LIMIT 1';

        $rs = array();
        if ($query = $this->db->query($sql))
        {
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            return $obj;
        }
        else
        {
            echo mysql_error();
            return FALSE;
        }

    }

}

?>