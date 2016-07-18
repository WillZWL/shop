<?php
namespace ESG\Panther\Dao;

class OrderNotesDao extends BaseDao implements HooksInsert
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

    public function triggerAfterInsert($obj)
    {
        $this->tableFieldsHooksInsert($obj);
    }

    public function tableFieldsHooksInsert($obj)
    {
        $table1 = [
                    'table' => 'so',
                    'where' => ['so_no'=>$obj->getSoNo(),],
                    'keyValue'=>['order_note' => $obj->getNote(),]
                  ];

        $this->updateTables([$table1,]);
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
