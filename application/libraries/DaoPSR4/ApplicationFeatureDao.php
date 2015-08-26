<?php
namespace AtomV2\Dao;

class ApplicationFeatureDao extends BaseDao
{
    private $tableName = "application_feature";
    private $voClassName = "ApplicationFeatureVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassName()
    {
        return $this->voClassName;
    }

    public function getApplicationFeatureAccessRight($where = [], $option = [], $className = "ApplicationFeatureRightDto")
    {
        $role = "'no'";
        if (isset($where['role_id'])) {
            $role = "";
            $role_id_arr = $where['role_id'];
            foreach ($role_id_arr as $single_role) {
                $role .= "'" . $single_role . "',";
            }
            $role = substr($role, 0, (strlen($role) - 1));
        }

        $this->db->from('application_feature_right afr');
        $this->db->join('application_feature af', 'af.app_feature_id = afr.app_feature_id and af.status = 1 and afr.status = 1', 'inner');

        $this->db->where(["afr.role_id in ({$role})" => null, "afr.app_id='{$where['app_id']}'" => null]);

        return $this->commonGetList($className, [], $option, 'af.*');
    }
}


