<?php
namespace ESG\Panther\Dao;

class SoPaymentStatusDao extends BaseDao
{
    private $tableName = "so_payment_status";
    private $voClassName = "SoPaymentStatusVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function getRecordWithGatewayName($where, $option = [], $classname = 'SoPaymentStatusWithGatewayNameDto') {
        $this->db->from("so_payment_status sops");
        $this->db->join("payment_gateway pmgw", "pmgw.payment_gateway_id = sops.payment_gateway_id", 'INNER');
        $this->db->where($where);

        return $this->commonGetList($classname, $where, $option, "sops.*, pmgw.name as payment_gateway_name");
    }
}

