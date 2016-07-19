<?php
namespace ESG\Panther\Dao;

class RefundHistoryDao extends BaseDao
{
    private $tableName = "refund_history";
    private $voClassName = "RefundHistoryVo";

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

    public function getHistoryList($where = [], $classname = "RefundHistUnameDto")
    {
        $sql = "SELECT h.*, s.reason, u.username, rr.description, rr.reason_cat
                FROM refund_history h
                JOIN (
                    SELECT id, reason
                    FROM refund
                    WHERE so_no = ?
                    ) as s
                ON s.id = h.refund_id
                JOIN user u
                    ON u.id = h.create_by
                JOIN refund_reason rr
                    ON rr.id = s.reason";
        if ($where["refund_id"] != NULL) {
            $sql .= " WHERE h.refund_id = '" . $where["refund_id"] . "'";
        }
        $sql .= " ORDER BY h.create_on ASC";

        $rs = [];

        if ($query = $this->db->query($sql, $where["so_no"])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }

        return FALSE;
    }

}