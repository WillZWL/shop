<?php
namespace ESG\Panther\Dao;

class EventDao extends BaseDao
{
    private $tableName = "event";
    private $voClassName = "EventVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getEventAction($where = [], $option = [], $className = 'ActionVo')
    {
        $where['e.status'] = 1;
        $where['a.status'] = 1;

        $this->db->from('action a');
        $this->db->join('event e', 'a.event_id = e.event_id', 'inner');

        return $this->db->commonGetList($className, $where, $option, 'a.event_id, a.action');
    }
}
