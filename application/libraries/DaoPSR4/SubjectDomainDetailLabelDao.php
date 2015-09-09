<?php
namespace ESG\Panther\Dao;

class SubjectDomainDetailLabelDao extends BaseDao
{
    private $tableName = "subject_domain_detail_label";
    private $voClassName = "SubjectDomainDetailLabelVo";

    public function __construct()
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

    public function valueOf($subject, $subkey, $lang_id)
    {
        if ($subject != "") {
            $this->db->from('subject_domain AS sd');
            $this->db->where('sd.subject', $subject);
            $this->db->select('sd.value AS sd_value');

            if ($subkey != "") {
                $this->db->join('subject_domain_detail AS sdd', 'sd.subject = sdd.subject', 'INNER');
                $this->db->where('sdd.subkey', $subkey);
                $this->db->select('sdd.value AS sdd_value');

                if ($lang_id != "") {
                    $this->db->join('subject_domain_detail_label AS sddl', 'sdd.subject = sddl.subject AND sdd.subkey = sddl.subkey', 'INNER');
                    $this->db->where('sddl.lang_id', $lang_id);
                    $this->db->select('sddl.value AS sddl_value');
                }
            } else {
                if ($lang_id != "") {
                    $this->db->join('subject_domain_detail_label AS sddl', 'sd.subject = sddl.subject', 'INNER');
                    $this->db->where('sddl.lang_id', $lang_id);
                    $this->db->select('sddl.value AS sddl_value');
                }
            }
        }

        if ($query = $this->db->get()) {
            if ($query->row()->sddl_value) {
                return $query->row()->sddl_value;
            } elseif ($query->row()->sdd_value) {
                return $query->row()->sdd_value;
            } elseif ($query->row()->sd_value) {
                return $query->row()->sd_value;
            }
        }
        return FALSE;
    }

    public function getSubjListWSubjLang($subject, $lang_id, $classname = "SubjListWSubjLangDto")
    {
        $this->db->select('sd.subject AS subject, sd.description AS subject_description, sd.value AS subject_value, sdd.subkey AS subkey, sdd.description AS subkey_description, sdd.value AS subkey_value, sddl.lang_id AS lang_id, sddl.value AS subkey_value_w_lang');
        $this->db->from('subject_domain AS sd');
        $this->db->join('subject_domain_detail AS sdd', 'sd.subject = sdd.subject', 'INNER');
        $this->db->join('subject_domain_detail_label AS sddl', 'sdd.subkey = sddl.subkey', 'INNER');
        $this->db->where("sd.subject = '$subject' and sddl.lang_id = '$lang_id'");

        $this->include_dto($classname);

        $rs = [];

        if ($query = $this->db->get()) {

            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return (array)$rs;
        }
        return FALSE;
    }
}


