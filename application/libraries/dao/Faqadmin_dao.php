<?php

class Faqadmin_dao extends Base_dao
{
    private $table_name = "faqadmin";
    private $vo_class_name = "Faqadmin_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
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

    public function get_list_cnt($where = array(), $option = array())
    {
        $this->db->from('language l');
        $this->db->join('faqadmin f', 'f.lang_id = l.id', 'LEFT');
        if (!isset($option["cnt"])) {
            $this->db->select('l.id AS lang_id, f.faq_ver, f.create_at, f.create_on, f.create_by, f.modify_at, f.modify_on, f.modify_by', FALSE);
            $this->db->order_by($option["orderby"]);

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

            if ($query = $this->db->get()) {
                $this->include_vo();
                $ret = array();
                foreach ($query->result($$this->get_vo_classname()) as $obj) {
                    $ret[] = $obj;
                }

                return (object)$ret;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
            echo $this->db->last_query() . " " . $this->db->_error_message();
        }

        return FALSE;
    }
}


