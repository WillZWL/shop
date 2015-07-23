<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Colour_dao extends Base_dao
{
    private $table_name = "colour";
    private $vo_class_name = "Colour_vo";
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

    public function get_list_index($where = array(), $option = array())
    {
        if (!isset($option["num_row"])) {
            return $this->get_list($where, $option);
        } else {
            $this->db->from('colour');

            $this->db->where($where);

            $this->db->select("COUNT(*) as total");

            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_remain_colour_list($prod_grp_cd)
    {
        $sql = "
                SELECT c.*
                FROM colour AS c
                LEFT JOIN product AS p
                ON c.id = p.colour_id AND p.prod_grp_cd = ?
                WHERE p.sku IS NULL AND c.status = 1
                ORDER BY id = 'NA' DESC
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


