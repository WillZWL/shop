<?php
namespace AtomV2\Dao;

class SubjectDomainDetailDao extends BaseDao
{
    private $tableName = "subject_domain_detail";
    private $voClassName = "SubjectDomainDetailVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

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

    public function getListWSubject($where = [], $option = [])
    {
        $this->db->from('subject_domain AS sd');
        $this->db->join('subject_domain_detail AS sdd', "sd.subject = sdd.subject", 'INNER');
        $this->include_vo($classname = $this->getVoClassname());
        return $this->common_get_list($where, $option, $classname, 'sdd.*');
    }
}


