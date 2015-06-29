<?php

include_once 'Base_dao.php';

class So_compensation_history_dao extends Base_dao
{
    private $table_name = "so_compensation_history";
    private $vo_classname = "So_compensation_history_vo";
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

    public function get_notification_email($compensation_id)
    {
        $this->db->from("so_compensation_history cph");
        $this->db->join("user AS usr", "usr.id = cph.create_by");
        $this->db->where(array("cph.compensation_id" => $compensation_id, "cph.status" => 1));
        $this->db->select("usr.email");
        if ($query = $this->db->get()) {
            return $query->row()->email;
        }

        return false;
    }
}

/* End of file so_compensation_history_dao.php */
/* Location: ./app/libraries/dao/So_compensation_history_dao.php */