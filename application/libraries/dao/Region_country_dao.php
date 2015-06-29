<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Region_country_dao extends base_dao
{
    private $table_name="region_country";
    private $vo_classname="Region_country_vo";
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

    public function get_regionid_countryname($regionid, $classname)
    {
        $this->include_dto($classname);
        $sql = 'SELECT r.region_id, r.country_id, c.name
                FROM region_country r
                JOIN country c
                    ON c.id = r.country_id
                WHERE r.region_id= ?
                ORDER BY c.name ASC';

        $rs = array();
        if ($query = $this->db->query($sql, $regionid))
        {
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            return (object) $rs;
        }
        else
        {
            echo mysql_error();
            return FALSE;
        }
    }
}

?>