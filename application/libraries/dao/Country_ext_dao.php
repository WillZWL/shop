<?php defined('BASEPATH') OR exit('No direct script access allowed');


include_once 'Base_dao.php';

class Country_ext_dao extends Base_dao
{
    private $table_name = "country_ext";
    private $vo_classname = "Country_ext_vo";
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

    public function get_country_name_in_lang($where = array(), $option = array(), $classname = "Country_lang_name_dto")
    {
        $this->db->_protect_identifiers = FALSE;
        $this->db->from('country AS c');
        $this->db->join('language AS l', 'l.status = 1', 'LEFT');
        $this->db->join('country_ext AS ce', 'ce.cid = c.id AND l.id = ce.lang_id', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select("c.id, c.name, ce.name AS lang_name", FALSE);

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs ? (object)$rs : $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

}

/* End of file country_ext_dao.php */
/* Location: ./system/application/libraries/dao/Country_ext_dao.php */