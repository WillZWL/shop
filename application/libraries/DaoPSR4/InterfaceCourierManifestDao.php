<?php
namespace ESG\Panther\Dao;

class InterfaceCourierManifestDao extends BaseDao
{
    private $tableName = "interface_courier_manifest";
    private $voClassName = "InterfaceCourierManifestVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getMaxIncrementNum()
    {
        $from=date("Y-m-d 00:00:00");
        $to=date("Y-m-d 23:59:59");
        $this->db->select('max(max_increment_num) as max_increment_num from '.$this->tableName.' where create_on >="'.$from.'" and  create_on <="'.$to.'"');
        if ($query = $this->db->get()){
            $result= $query->first_row();
            return $result->max_increment_num;
        }else{
            return null;
        }
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }


}
