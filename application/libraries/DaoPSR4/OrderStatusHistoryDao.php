<?php
namespace ESG\Panther\Dao;

class OrderStatusHistoryDao extends BaseDao
{
    private $tableName = "order_status_history";
    private $voClassName = "OrderStatusHistoryVo";

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

    public function getListWithUsername($where, $classname = "OrderHistoryUsernameDto")
    {
        $this->db->from('order_status_history osh');
        $this->db->join('user u', 'u.id = osh.create_by', 'LEFT');
        $this->db->where($where);
        $this->db->select('osh.status,osh.create_on,u.username');
        $this->db->order_by('osh.create_on ASC');

        $rs = [];

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }
}
