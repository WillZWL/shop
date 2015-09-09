<?php
namespace ESG\Panther\Dao;

class CustomClassificationMappingDao extends BaseDao
{
    private $tableName = "custom_classification_mapping";
    private $voClassName = "CustomClassificationMappingVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function SubCatVarDao()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getSccc($where = [])
    {
        return $this->get($where);
    }

    public function updatePcc($obj)
    {
        return $this->update($obj);
    }

    public function includePccVo()
    {
        return $this->get();
    }

    public function addPcc($obj)
    {
        return $this->insert($obj);
    }

    public function getCustomClassMappingList($where = [], $option = [], $classname = "SubCatCustomClassificationDto")
    {
        if (isset($where["ccm.country_id"])) {
            $ccm_clause = " AND ccm.country_id = '" . $where['ccm.country_id'] . "'";
            unset($where["ccm.country_id"]);
        }
        $this->db->from("category AS sc");
        $this->db->join("category AS cat", "cat.id = sc.parent_cat_id", "INNER");
        $this->db->join("custom_classification_mapping AS ccm", "sc.id = ccm.sub_cat_id $ccm_clause", "LEFT");
        $this->db->join("custom_classification AS cc", "cc.id = ccm.custom_class_id", "LEFT");
        $this->db->where(["sc.level" => 2, "cat.level" => 1]);

        return $this->commonGetList($classname, $where, $option, 'cat.id cat_id, cat.name cat_name, sc.id sub_cat_id, sc.name sub_cat_name, ccm.country_id, cc.code, cc.description, cc.duty_pcent, ccm.create_on, ccm.create_at, ccm.create_by, ccm.modify_on, ccm.modify_at, ccm.modify_by');
    }

    public function getAllCustomClassMappingList($sub_cat, $option = [], $classname = "SubCatCustomClassificationDto")
    {
        $sql = <<<SQL
                    SELECT
                        ccm.sub_cat_id, ccm.country_id, cc.code, cc.duty_pcent, cc.description
                    FROM custom_classification_mapping ccm
                    INNER JOIN custom_classification cc on ccm.custom_class_id = cc.id
                    WHERE
                         ccm.sub_cat_id = $sub_cat
                    order by ccm.country_id asc
SQL;
        $query = $this->db->query($sql);
        if (!$query) {
            return FALSE;
        }
        $array = $query->result_array();
        return $array;
    }

    public function getHsBySubcatAndCountry($where = [], $option = [], $classname = "ProductCustomClassDto")
    {
        $this->db->from("custom_classification cc");
        $this->db->join("custom_classification_mapping ccm", "ccm.custom_class_id = cc.id", "INNER");
        $this->db->where($where);
        $this->db->select("cc.code, cc.description", false);
        $rs = [];

        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $rs[] = $row;
                }
            }
            return $rs;
        } else {
            return false;
        }
    }

}

?>
