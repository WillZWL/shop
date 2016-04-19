<?php
namespace ESG\Panther\Dao;

class ProductHistorySyncDao extends BaseDao
{
    private $table_name="product_history_sync";
    private $vo_class_name="ProductHistorySyncVo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function getAlertSkuList($where, $option, $classname = 'WebsiteStatusAlertDto')
    {
        $this->db->from('product_history_sync AS phs');
        $this->db->join('product as p', 'phs.sku = p.sku', 'LEFT');
        $this->db->join('sku_mapping as sm', 'sm.sku = p.sku and sm.status = 1', 'LEFT');
        $this->db->where($where);
        return $this->commonGetList($classname, $where, $option, "p.sku, sm.ext_sku as master_sku, p.name, p.surplus_quantity as surplus_qty, p.sourcing_status as sourcing_status, phs.website_status as origin_website_status, p.website_status as website_status");
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