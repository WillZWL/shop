<?php
namespace ESG\Panther\Dao;
class InterfaceFlexRiaDao extends BaseDao
{
    private $table_name = "interface_flex_ria";
    private $vo_classname = "InterfaceFlexRiaVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getVoClassname()
    {
        return $this->vo_classname;
    }

    public function getSeqName()
    {
        return $this->seq_name;
    }

    public function getSeqMappingField()
    {
        return $this->seq_mapping_field;
    }

    public function getFlexRiaByBatch($batchId)
    {
        $option['limit'] = -1;
        $this->db->from("interface_flex_ria AS ifr");
        $this->db->group_by("ifr.so_no, ifr.status, ifr.txn_time");
        $where["flex_batch_id"] = $batchId;
        return $this->commonGetList("InterfaceFlexRiaVo", $where, $option, 'trans_id,  so_no, flex_batch_id, gateway_id, txn_id, txn_time, currency_id, sum(amount) as amount, status, batch_status, create_on, create_at, create_by, modify_at, modify_by');

    }
}


