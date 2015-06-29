<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Category_product_spec_dao extends Base_dao {
    private $table_name="category_product_spec";
    private $vo_class_name="category_product_spec_vo";
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

    public function get_full_cps_list($cat_id, $classname="Full_cps_with_cat_id_dto")
    {
        $sql =
            '
                SELECT psg.id AS psg_id, psg.name AS psg_name, ps.id AS ps_func_id, ps.name AS ps_name, ps.unit_type_id, ut.name AS unit_type_name, cps.cat_id, cps.unit_id, u.unit_name, cps.priority, cps.status
                FROM product_spec ps
                LEFT JOIN category_product_spec cps
                    ON ps.id = cps.ps_id AND cps.cat_id = ?
                LEFT JOIN product_spec_group psg
                    ON ps.psg_id = psg.id
                LEFT JOIN unit_type ut
                    ON ps.unit_type_id = ut.id
                LEFT JOIN unit u
                    ON cps.unit_id = u.id
                WHERE ps.status = 1
                ORDER BY psg.priority DESC, cps.status DESC, cps.priority DESC
            ';

        $this->include_dto($classname);

        if ($query = $this->db->query($sql, $cat_id))
        {
            $rs = $query->result($classname);
            return $rs;
        }
    }
}

/* End of file category_product_spec_dao.php */
/* Location: ./system/application/libraries/dao/Category_product_spec_dao.php */