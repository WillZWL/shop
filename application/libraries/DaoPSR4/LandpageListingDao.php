<?php
namespace ESG\Panther\Dao;

class LandpageListingDao extends BaseDao
{
    private $tableName = 'landpage_listing';
    private $voClassName = 'LandpageListingVo';

    public function getVoClassName()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getLandpageList($where = [], $option = [], $className = 'LandpageListingDto')
    {
        $this->db->from('landpage_listing ll');
        $this->db->join('product p', 'p.sku = ll.selection', 'LEFT');
        $this->db->join('sku_mapping sm', 'sm.sku = ll.selection', 'LEFT');
        $this->db->order_by("ll.rank ASC");
        $select = ' p.sku, sm.ext_sku as master_sku, p.name,ll.*';
        return $this->commonGetList($className, $where, $option, $select);
    }
}