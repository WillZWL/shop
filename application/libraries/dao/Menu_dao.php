<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Menu_dao extends Base_dao
{
    private $table_name = "menu";
    private $vo_class_name = "menu_vo";
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

    public function get_fm_list_w_name($lang_id, $where = array(), $option = array())
    {
        // if current language's name missing use default 'en'

        $this->db->from('menu AS m');
        $this->db->join('func_option AS fo_def', "m.menu_item_id = fo_def.func_id AND fo_def.lang_id= 'en'", 'INNER');
        $this->db->join('func_option AS fo', "'m.menu_item_id = fo.func_id AND fo.lang_id ='$lang_id'", "LEFT");

        $this->db->where($where);

        if (empty($option["num_rows"])) {
            $this->include_vo();

            $this->db->select('m.menu_id, m.menu_type, m.parent_id, m.level, m.menu_item_id, m.code, IFNULL(fo.text,fo_def.text) name, m.link_type, m.link, m.priority, m.status, fo.create_on, fo.create_at, fo.create_by, fo.modify_on, fo.modify_at, fo.modify_by');

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
                foreach ($query->result($this->get_vo_classname()) as $obj) {
                    $rs[] = $obj;
                }

                if ($rs) {
                    return (object)$rs;
                }
                return FALSE;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_list_w_name($where = array(), $option = array())
    {
        $this->db->from('menu AS m');
        $this->db->join('func_option AS fo', 'm.menu_item_id = fo.func_id', 'INNER');
        $this->db->where($where);

        if (empty($option["num_rows"])) {
            $this->include_vo();

            $this->db->select('m.menu_id, m.menu_type, m.parent_id, m.level, m.menu_item_id, m.code, fo.text name, m.link_type, m.link, m.priority, m.status, fo.create_on, fo.create_at, fo.create_by, fo.modify_on, fo.modify_at, fo.modify_by');

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
                foreach ($query->result($this->get_vo_classname()) as $obj) {
                    $rs[] = $obj;
                }

                if ($rs) {
                    return (object)$rs;
                }
                return FALSE;
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

