<?php
namespace ESG\Panther\Dao;

class RefundItemDao extends BaseDao
{
    private $tableName = "refund_item";
    private $voClassName = "RefundItemVo";

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

    public function getListWithName($where = [], $option = [], $classname = "RefundItemProdDto")
    {
        $this->db->from("refund_item ri");

        $this->db->join("product p", "p.sku = ri.item_sku", "LEFT");

        $this->db->join("user u", "u.id = ri.create_by", "INNER");

        $this->db->where($where);

        $this->db->select("ri.*, p.name, u.username");

        $this->db->order_by($option["sortby"]);

        $this->include_dto($classname);

        $rs = [];

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;

    }

    public function getOrderRefundType($so_no = "")
    {
        if ($so_no == "") {
            return FALSE;
        }

        $sql = "SELECT rfi.refund_type
                FROM refund rf
                JOIN refund_item rfi
                ON rf.id = rfi.refund_id
                WHERE rf.so_no = ?
                ";

        if ($query = $this->db->query($sql, [$so_no])) {
            return $query->row()->refund_type;
        }
        return FALSE;
    }
}