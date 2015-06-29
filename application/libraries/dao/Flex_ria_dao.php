<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Flex_ria_dao extends Base_dao
{
    private $table_name = "flex_ria";
    private $vo_class_name = "Flex_ria_vo";
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

    public function get_pending_order_report_list($where = array(), $option = array())
    {
        if (isset($option["orderby"])) {
            if ($this->db->_has_operator($option["orderby"])) {
                $this->db->_protect_identifiers = FALSE;
            }
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

        $vo_classname = $this->get_vo_classname();
        $vo_file = APPPATH . "libraries/vo/" . strtolower($vo_classname) . ".php";
        if (file_exists($vo_file)) {
            include_once($vo_file);

            $this->db->select('ria.*', FALSE);
            $this->db->from('flex_ria ria');
            $this->db->join('so', 'so.so_no = ria.so_no', 'INNER');
            $this->db->where($where);

            if ($query = $this->db->get()) {
                $rs = array();
                if ($classname == "") {
                    $classname = $vo_classname;
                }
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                if ($option["limit"] == 1) {
                    return $rs[0];
                } else {
                    if (empty($option["result_type"]) && empty($option["array_list"])) {
                        return (object)$rs;
                    } else {
                        return $rs;
                    }
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_flex_ria_w_gateway_mapping($where = array(), $option = array(), $classname = "Ria_gate_mapping_dto")
    {
        $option['limit'] = 1;
        $this->db->from("flex_ria fr");
        $this->db->join("flex_gateway_mapping fgm", "fr.gateway_id = fgm.gateway_id AND fr.currency_id = fgm.currency_id", "LEFT");

        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, "fr.currency_id, fr.flex_batch_id, fr.txn_id, date_format(fr.txn_time, '%Y-%m-%d') txn_time, fr.amount, fr.status, CONCAT(fgm.gateway_code, 'I') tran_type, fgm.gateway_code AS report_pmgw");
    }
}

/* End of file flex_ria_dao.php */
/* Location: ./system/application/libraries/dao/Flex_ria_dao.php */
