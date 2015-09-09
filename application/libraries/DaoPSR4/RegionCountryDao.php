<?php
namespace ESG\Panther\Dao;

class RegionCountryDao extends baseDao
{
    private $tableName = "region_country";
    private $voClassname = "RegionCountryVo";

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
        return $this->voClassname;
    }

    public function getRegionidCountryname($regionid, $classname)
    {
        $sql = 'SELECT r.region_id, r.country_id, c.name
                FROM region_country r
                JOIN country c
                    ON c.id = r.country_id
                WHERE r.region_id= ?
                ORDER BY c.name ASC';

        $rs = array();
        if ($query = $this->db->query($sql, $regionid)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            echo mysql_error();
            return FALSE;
        }
    }
}

?>
