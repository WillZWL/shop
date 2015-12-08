<?php
namespace ESG\Panther\Dao;
class InterfaceFlexGatewayFeeDao extends BaseDao
{
    private $table_name = "interface_flex_gateway_fee";
    private $vo_classname = "InterfaceFlexGatewayFeeVo";
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

    public function getGatewayFeeByBatch($batchId)
    {
        $option['limit'] = -1;
        $this->db->from("interface_flex_gateway_fee AS fgf");
        $this->db->group_by("fgf.txn_id, fgf.status,fgf.txn_time");
        $where["flex_batch_id"] = $batchId;
        $this->includeVo();
        return $this->commonGetList("InterfaceFlexGatewayFeeVo", $where, $option, 'trans_id,  flex_batch_id, gateway_id, txn_id, txn_time, currency_id, sum(amount) as amount, status, batch_status, create_on, create_at, create_by, modify_at, modify_by');

    }
}


