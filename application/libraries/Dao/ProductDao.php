<?php
namespace AtomV2\Dao;

class ProductDao extends BaseDao
{
    private $tableName = 'product';
    private $voClassName = 'ProductVo';

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getLandpageSku($where = [], $option = [], $className = 'SimpleProductDto')
    {
        $where['pd.status'] = 2;
        $where['pr.listing_status'] = 1;
        $where = ['ll.platform' => PLATFORM];

        $this->db->from('landpage_listing ll');
        $this->db->join('product pd', 'pd.sku = ll.sku', 'inner');
        $this->db->join('price pr', 'pr.platform = ll.platform', 'inner');
        $this->db->where($where);
        $this->db->where(['pr.sku = ll.sku' => null]);
        $this->db->group_by('ll.sku');
        $this->db->order_by("ll.rank");

        return $this->commonGetList($className, [], $option, 'pd.sku');
    }

    public function getProductInfo($where = [], $option = [], $className = "ProductOverviewDto")
    {
        $this->db->from('selling_platform sp');
        $this->db->join('price pr', 'sp.platform = pr.platform', 'inner');
        $this->db->join('product pd', "pd.sku = pr.sku", 'inner');

        // var_dump($this->db->get_compiled_select());
        return $this->commonGetList($className, $where, $option, '*');
        // var_dump($this->db->last_query());
    }

    public function getHomeProduct($where = [], $option = [], $className = 'SimpleProductDto')
    {
        $where['pd.status'] = 2;
        $where['pr.listing_status'] = 'L';
        $where['pd.website_status <>'] = 'O';

        $this->db->from('landpage_listing ll');
        $this->db->join('product pd', 'pd.sku = ll.selection', 'inner');
        $this->db->join('price pr', 'pr.platform_id = ll.platform_id', 'inner');
        $this->db->where($where);
        $this->db->where(['pr.sku = ll.selection' => null]);
        $this->db->group_by('ll.selection');
        $this->db->order_by("ll.mode = 'M' DESC, ll.rank");

        return $this->commonGetList($className, [], $option, 'pd.sku');
    }

    public function getProductOverview($where = [], $option = [], $className = "ProductOverviewDto")
    {
        $option = ['limit' => 1];
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('product AS p', 'vpi.prod_sku = p.sku', 'INNER');
        $this->db->join('v_prod_overview_wo_cost AS vpo', 'vpi.item_sku = vpo.sku', 'INNER');

        return $this->commonGetList($className, $where, $option, 'vpo.*, p.expected_delivery_date, p.warranty_in_month');
    }
}
