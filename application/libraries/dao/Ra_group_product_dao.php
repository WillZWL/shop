<?php

include_once 'Base_dao.php';

class Ra_group_product_dao extends Base_dao
{
    private $table_name = "ra_group_product";
    private $vo_classname = "Ra_group_product_vo";
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

    public function get_item_w_name($group_id, $classname = 'Ra_group_product_w_prodname_dto')
    {
        $this->include_dto($classname);

        $this->db->from('ra_group_product AS ragp');
        $this->db->join('product AS p', 'p.sku = ragp.sku', 'INNER');
        $this->db->where('ragp.ra_group_id', $group_id);

        $this->db->select('ragp.ra_group_id, ragp.sku, p.name, ragp.priority, ragp.create_on, ragp.create_at, ragp.create_by, ragp.modify_on, ragp.modify_at, ragp.modify_by');
        $this->db->order_by('priority asc, sku asc');
        $this->db->limit('', 0);

        $rs = array();
        if ($query = $this->db->get()) {
            foreach ($query->result('object', $classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }

        return FALSE;
    }
}

/* End of file ra_group_product_dao.php */
/* Location: ./app/libraries/dao/Ra_group_product_dao.php */