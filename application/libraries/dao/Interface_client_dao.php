<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_client_dao extends Base_dao
{
    private $table_name = "interface_client";
    private $vo_class_name = "Interface_client_vo";
    private $seq_name = "ic_no";
    private $seq_mapping_field = "id";

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

    public function update_client_id_to_interface($batch_id, $email, $client_id)
    {
        $sql = "
            update interface_client c set
                id = ?
            where
            c.batch_id = ?
            and c.email = ?;
        ";

        $data = array($client_id, $batch_id, $email);
        $this->db->query($sql, $data);

        $sql = "commit";
        return $this->db->query($sql, $data);
    }
}

