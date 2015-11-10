<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Cps_sourcing_list_dao extends Base_dao
{
    private $table_name = "cps_sourcing_list";
    private $vo_class_name = "Cps_sourcing_list_vo";
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

    public function inactive_cpssl_status()
    {
        $sql =
            "
            update `cps_sourcing_list` cpssl
            set status = 0
        ";
        $query = $this->db->query($sql);

        $sql = "commit";
        $query = $this->db->query($sql);
        return $query;
    }
}


