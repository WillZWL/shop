<?php
namespace ESG\Panther\Dao;

class ProductContentDao extends BaseDao
{
    private $table_name = 'product_content';
    private $vo_class_name = 'ProductContentVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getProductWithUrl($sku, $lang_id, $select_str, $classname = 'ProductWithContentDto')
    {
        $where = ['p.sku' => $sku, 'pc.lang_id' => $lang_id];
        $this->db->from('product as p');
        $this->db->join('product_content as pc', 'p.sku = pc.prod_sku', 'inner');

        return $this->commonGetList($classname, $where, [], $select_str);
    }

    public function updateProductUrl($product_url, $sku, $lang_id)
    {
        $sql = "UPDATE product_content SET product_url = ? WHERE prod_sku = ? AND lang_id = ?";
        $this->db->query($sql, [$product_url, $sku, $lang_id]);
    }
}
