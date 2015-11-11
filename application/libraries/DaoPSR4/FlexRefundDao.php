<?php
namespace ESG\Panther\Dao;

class FlexRefundDao extends BaseDao
{
    private $table_name = "flex_refund";
    private $vo_class_name = "FlexRefundVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getSeqName()
    {
        return $this->seq_name;
    }

    public function getSeqMappingField()
    {
        return $this->seq_mapping_field;
    }

    public function getNoOfRefundStatus($so_no)
    {
        $sql = "SELECT count(*) total
                FROM
                (
                    SELECT * FROM flex_refund WHERE so_no = '" . $so_no . "'
                    GROUP BY status
                )a";

        if ($query = $this->db->query($sql)) {
            return $query->row()->total;
        }
    }

    public function getRefunds($where, $option = [], $classname = 'FlexRefundVo')
    {
        $option["limit"] = -1;
        $this->db->from("flex_refund fr");
        $this->db->order_by("flex_batch_id");
        $this->db->group_by("fr.so_no");
        $select_str = "so_no, flex_batch_id, gateway_id, internal_txn_id, txn_id, txn_time, currency_id, sum(amount) as amount, status, create_on, create_at, create_by, modify_on, modify_at, modify_by";
        return $this->commonGetList($classname, $where, $option, $select_str);
    }
}



