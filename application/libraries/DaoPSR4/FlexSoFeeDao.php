<?php
namespace ESG\Panther\Dao;
class FlexSoFeeDao extends BaseDao
{
    private $table_name = "flex_so_fee";
    private $vo_class_name = "FlexSoFeeVo";
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

    public function getSoFeeInvoice($where, $classname = "SoFeeInvoiceDto")
    {
        $option['limit'] = -1;
        $this->db->from("flex_so_fee fsf");
        $this->db->join("flex_gateway_mapping fgm", "fgm.gateway_id = fsf.gateway_id AND fgm.currency_id = fsf.currency_id", "INNER");
        $this->db->join("so", "so.so_no = fsf.so_no", "INNER");
        $this->db->join("(SELECT so_no, count(1) AS qty
                            FROM so_item_detail soid
                            GROUP BY so_no) a", "a.so_no = fsf.so_no", "INNER");
        $this->db->order_by("fsf.flex_batch_id, fsf.txn_time");
        $select_str = 'fsf.so_no AS so_no, fsf.status AS type, fsf.txn_time, fsf.currency_id AS currency, fgm.gateway_code AS gateway_id,
        fsf.flex_batch_id AS batch_id, a.qty, so.amount as order_amount, fsf.amount AS fee, fsf.txn_id AS txn_ref';
        return $this->commonGetList($classname, $where, $option, $select_str);
    }
}



