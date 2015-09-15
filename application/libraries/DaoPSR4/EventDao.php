<?php
namespace ESG\Panther\Dao;

class EventDao extends BaseDao
{
    private $tableName = "event";
    private $voClassName = "EventVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getEventAction($event_id = "", $classname = "")
    {
        $sql = "
                SELECT a.*
                FROM action a
                INNER JOIN event e
                    ON (a.event_id = e.event_id)
                WHERE e.event_id = ?
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


