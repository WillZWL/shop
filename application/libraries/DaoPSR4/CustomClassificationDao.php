<?php
namespace ESG\Panther\Dao;

class CustomClassificationDao extends BaseDao
{
    private $tableName = "custom_classification";
    private $voClassName = "CustomClassificationVo";

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

    public function getCustomClassListWithPlatformId($platform_id = "WEBHK")
    {
        $sql = "SELECT *
                FROM custom_classification cc
                JOIN platform_biz_var pbv
                    ON cc.country_id = pbv.platform_country_id
                WHERE pbv.selling_platform_id = ?
                ";

        if ($result = $this->db->query($sql, array($platform_id))) {
            $this->include_vo();

            $result_arr = [];
            $classname = $this->getVoClassname();

            foreach ($result->result($classname) as $obj) {
                array_push($result_arr, $obj);
            }
            return $result_arr;
        }
        return FALSE;

    }

    public function getOption($where)
    {
        $sql = "SELECT distinct country_id, code, duty_pcent, description
                FROM custom_classification cc";

        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        $array = $query->result_array();
        return $array;

    }

}


