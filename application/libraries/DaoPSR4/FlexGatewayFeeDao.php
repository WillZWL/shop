<?php
namespace ESG\Panther\Dao;
class FlexGatewayFeeDao extends BaseDao
{
    private $table_name = "flex_gateway_fee";
    private $vo_class_name = "FlexGatewayFeeVo";
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
}



