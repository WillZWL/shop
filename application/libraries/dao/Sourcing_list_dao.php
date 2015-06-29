<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Sourcing_list_dao extends Base_dao {
    private $table_name="sourcing_list";
    private $vo_class_name="Sourcing_list_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct(){
        parent::__construct();
    }

    public function get_vo_classname(){
        return $this->vo_class_name;
    }

    public function get_table_name(){
        return $this->table_name;
    }

    public function get_seq_name(){
        return $this->seq_name;
    }

    public function get_seq_mapping_field(){
        return $this->seq_mapping_field;
    }

    public function get_date_list()
    {
        $sql  = "
                SELECT DISTINCT list_date
                FROM sourcing_list
                ORDER BY list_date DESC
                ";

        $rs = array();
        if ($query = $this->db->query($sql))
        {
            foreach ($query->result() as $row)
            {
                $rs[] = $row->list_date;
            }
            return $rs;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_min_date()
    {
        $sql  = "
                SELECT MIN(list_date) AS min_date
                FROM sourcing_list
                ";

        $rs = array();
        if ($query = $this->db->query($sql))
        {
            return $query->row()->min_date;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_max_date()
    {
        $sql  = "
                SELECT MAX(list_date) AS max_date
                FROM sourcing_list
                ";

        $rs = array();
        if ($query = $this->db->query($sql))
        {
            return $query->row()->max_date;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_sorucing_list($where=array(), $option=array(), $classname="Sourcing_list_dto")
    {
        $this->db->from('sourcing_list AS sl');
        $this->db->join('product AS p','sl.item_sku = p.sku','INNER');
        $this->db->join('(
                            SELECT prod_sku, currency_id, cost
                            FROM supplier_prod
                            WHERE order_default = 1
                            GROUP BY supplier_id,prod_sku,moq
                        )sp', 'sp.prod_sku = sl.item_sku AND sp.prod_sku = p.sku', 'LEFT');
        $this->db->join('category sc', 'sc.id = p.sub_cat_id AND sc.status = 1', 'INNER');
        $this->db->join('category_var cv', 'cv.cat_id = p.sub_cat_id AND cv.cat_id = sc.id AND cv.status = 1', 'LEFT');
        $this->db->join('sku_mapping AS map',"map.sku = sl.item_sku AND map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1",'LEFT');

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]))
        {

            if (empty($option["orderby"]))
            {
                $option["orderby"] = "prod_name ASC";
            }

            if (empty($option["limit"]))
            {
                $option["limit"] = "";
            }

            if (!isset($option["offset"]))
            {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "")
            {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            $this->db->select('sl.*, p.name AS prod_name, p.sourcing_status, round(sl.sourced_qty/sl.required_qty*100, 2) AS sourced_pcent, map.ext_sku master_sku, sp.currency_id supplier_curr_id, sp.cost supplier_cost, cv.budget_pcent, ROUND(sp.cost*(100+cv.budget_pcent)/100 ,2) AS budget, p.clearance', FALSE);
            $this->db->order_by($option["orderby"]);

            if ($query = $this->db->get())
            {
                $this->include_dto($classname);
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
            }
        }
        else
        {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }
        return FALSE;
    }


    public function sp_copy_prev_sl_data($list_date)
    {
        $sql  = "CALL sp_copy_prev_sl_data(?)";
        return $this->db->query($sql, $list_date);
    }

}

/* End of file sourcing_list_dao.php */
/* Location: ./system/application/libraries/dao/Sourcing_list_dao.php */