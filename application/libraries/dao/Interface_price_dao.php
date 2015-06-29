<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_price_dao extends Base_dao
{
    private $table_name="interface_price";
    private $vo_class_name="Interface_price_vo";
    private $seq_name="";
    private $seq_mapping_field="";

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

    public function sp_ixtens_reprice_pg($batch_id)
    {
        $sql  = "CALL sp_ixtens_reprice_pg(?)";
        return $this->db->query($sql, $batch_id);
    }
}

/* End of file interface_price_dao.php */
/* Location: ./system/application/libraries/dao/Interface_price_dao.php */