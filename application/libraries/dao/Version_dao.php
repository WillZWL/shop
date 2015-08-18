<?php

include_once 'Base_dao.php';

class Version_dao extends Base_dao
{
    private $table_name = "version";
    private $vo_class_name = "Version_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_list_cnt($where = array())
    {
        $this->db->from($this->get_table_name());
        $this->db->where($where);
        $this->db->select('COUNT(*) AS total');

        if ($query = $this->db->get()) {
            return $query->row()->total;
        }
        return FALSE;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_remain_version_list($prod_grp_cd)
    {
        $sql = "
                SELECT v.*
                FROM version AS v
                LEFT JOIN product AS p
                ON v.id = p.version_id AND p.prod_grp_cd = ?
                WHERE p.sku IS NULL AND v.status = 'A'
                ";

        $this->include_vo();

        if ($query = $this->db->query($sql, $prod_grp_cd)) {
            $rs = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result($this->get_vo_classname()) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            } else {
                return $rs;
            }
        } else {
            return FALSE;
        }
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }
}

?>