<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Event_dao extends Base_dao
{
    private $table_name = "event";
    private $vo_class_name = "Event_vo";
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

    public function get_event_action($event_id = "", $classname = "")
    {
        $sql = "
                SELECT a.*
                FROM action a
                INNER JOIN event e
                    ON (a.event_id = e.id)
                WHERE e.id = ?
                AND e.status = 1
                AND a.status = 1
                ";

        $rs = array();
        if ($query = $this->db->query($sql, $event_id)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else
            return FALSE;
    }
}


