<?php
namespace ESG\Panther\Dao;

class OrderNotesDao extends BaseDao
{
    private $tableName = "order_notes";
    private $voClassName = "OrderNotesVo";

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

    public function getListWithName($where, $classname = "OrderNoteUsernameDto")
    {
        $this->db->from('order_notes n');
        $this->db->join('user u', 'u.id = n.create_by', 'LEFT');
        $this->db->where($where);
        $this->db->select('n.note, n.create_on, COALESCE(u.username, n.create_by) AS username', FALSE);
        $this->db->order_by('n.create_on DESC');

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
