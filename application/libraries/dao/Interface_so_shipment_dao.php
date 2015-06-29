<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_so_shipment_dao extends Base_dao
{

    private $table_name = "interface_so_shipment";
    private $vo_classname = "Interface_so_shipment_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function Interface_so_shipment_dao()
    {
        parent::__construct();
    }

    public function Banner_dao()
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

    public function update_dpd_trackno($batch_id)
    {
        $sql = "CALL sp_dpd_update_trackno(?)";
        $ret = $this->db->query($sql, $batch_id);
        return $ret;
    }
}

