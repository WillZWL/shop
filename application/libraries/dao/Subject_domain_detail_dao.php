<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Subject_domain_detail_dao extends Base_dao
{
    private $table_name = "subject_domain_detail";
    private $vo_class_name = "Subject_domain_detail_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_list_w_subject($where = array(), $option = array())
    {
        $this->db->from('subject_domain AS sd');
        $this->db->join('subject_domain_detail AS sdd', "sd.subject = sdd.subject", 'INNER');
        $this->include_vo($classname = $this->get_vo_classname());
        return $this->common_get_list($where, $option, $classname, 'sdd.*');
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }


    /*
    public function get_value_w_subject_subkey($subject, $subkey)
    {
        $sql = "
                SELECT sdd.value
                FROM subject_domain sd
                JOIN subject_domain_detail sdd
                    on sd.subject = sdd.subject
                WHERE sdd.subject = ? AND sdd.subkey = ?
                ";

        if($query = $this->db->query($sql, array($subject, $subkey)))
        {
            return $query->row()->value;
        }
        return FALSE;
    }
    */
}


