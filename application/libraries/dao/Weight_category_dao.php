<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Weight_category_dao extends Base_dao
{
    private $table_name = "weight_category";
    private $vo_class_name = "Weight_category_vo";
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

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_cat_w_region($where = array(), $option = array(), $classname = "")
    {

        $this->include_dto($classname);

        $this->db->select("id AS cat_id, weight AS cat_name");

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

        if ($query = $this->db->get_where($this->get_table_name(), $where, $option["limit"], $option["offset"])) {
            $rs = array();
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            if ($option["limit"] == 1) {
                return $rs[0];
            } else {
                return (object)$rs;
            }
        } else {
            return FALSE;
        }
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_default_delivery_charge($platform_id, $shiptype, $weight)
    {
        $sql = "
                SELECT dwcc.amount AS charge
                FROM shiptype AS st
                LEFT JOIN platform_biz_var AS pbv
                    ON (st.platform_id = pbv.selling_platform_id)
                LEFT JOIN (weight_category AS dwc, weight_cat_charge AS dwcc)
                    ON (dwc.id = dwcc.wcat_id AND pbv.delivery_type = dwcc.delivery_type AND pbv.dest_country = dwcc.dest_country AND pbv.platform_currency_id = dwcc.currency_id)
                WHERE platform_id = ?
                AND st.id = ?
                ORDER BY (dwc.weight >= ?) DESC, ABS(?-dwc.weight) ASC
                LIMIT 1
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($platform_id, $shiptype, $weight, $weight))) {
            return $query->row()->charge;
        } else {
            return FALSE;
        }
    }

    public function get_from_fc($fc = "")
    {
        $sql = "SELECT wc.id
                FROM weight_category wc
                JOIN freight_category fc
                    ON fc.id = ?
                    AND fc.weight = wc.weight
                LIMIT 1";

        if ($query = $this->db->query($sql, $fc)) {
            return $query->row()->id;
        }

        return FALSE;
    }
}


