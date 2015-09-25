<?php
namespace ESG\Panther\Dao;

class RaProductDao extends BaseDao
{
	private $tableName = "ra_product";
    private $voClassName = "RaProductVo";

	public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getRaProductWithGroupName($where = [], $option = [], $lang_id = "en")
    {
        $select_str = 'rap.sku, ragp.ra_group_id, rgc.group_display_name as group_name, ragp.sku as ra_sku, pbv.selling_platform_id as platform_id, pbv.platform_currency_id, pr.price, pr.default_shiptype as shiptype, pr.listing_status,';
        $select_str .= 'p.prod_grp_cd, p.name as prod_name, p.quantity, p.clearance, p.website_quantity, p.website_status, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, p.image, p.ean, p.mpn, p.upc, p.status as prod_status, p.display_quantity,';
        $select_str .= 'ifnull(nullif(pc.prod_name, ""), ifnull(nullif(pc_en.prod_name, ""), p.name)) as content_prod_name';

        $this->db->select($select_str, false);
        $this->db->from('ra_product rap');
        $this->db->join('ra_group_product ragp', 'ragp.ra_group_id = ragp.ra_group_id and ragp.ra_group_id in (rap.rcm_group_id_1, rap.rcm_group_id_2, rap.rcm_group_id_3, rap.rcm_group_id_4, rap.rcm_group_id_5, rap.rcm_group_id_6, rap.rcm_group_id_7, rap.rcm_group_id_8, rap.rcm_group_id_9, rap.rcm_group_id_10, rap.rcm_group_id_11, rap.rcm_group_id_12, rap.rcm_group_id_13, rap.rcm_group_id_14, rap.rcm_group_id_15, rap.rcm_group_id_16, rap.rcm_group_id_17, rap.rcm_group_id_18, rap.rcm_group_id_19, rap.rcm_group_id_20)', 'INNER');
        $this->db->join('ra_group rag', 'ragp.ra_group_id = rag.group_id', 'INNER');
        $this->db->join('product p', 'p.sku = ragp.sku', 'INNER');
        $this->db->join('price pr', 'p.sku = pr.sku', 'INNER');
        $this->db->join('platform_biz_var pbv', 'pr.platform_id = pbv.selling_platform_id', 'INNER');
        $this->db->join('product_content pc', 'p.sku = pc.prod_sku and pbv.language_id = pc.lang_id', 'LEFT');
        $this->db->join('product_content pc_en', 'p.sku = pc_en.prod_sku and pc_en.lang_id = "en"', 'LEFT');
        $this->db->join('ra_group_content AS rgc', 'rag.group_id = rgc.group_id and rgc.lang_id = "' . $lang_id . '"', 'LEFT');
        $this->db->order_by('price asc');
        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = [];
            foreach ($query->result('array') as $row) {
                $ret[] = $row;
            }

            return $ret;
        }

        return false;
    }

    public function getRaGroupListByProdSku($main_sku, $where = [], $option = [])
    {
        /* This function grabs a list of RAs mapped to the main product sku passed in */
        $where["rap.sku"] = $main_sku;
        $select_str = "ragp.sku AS ra_sku, ragp.ra_group_id AS ra_group_id";
        $this->db->select($select_str);

        $this->db->from('ra_product rap');
        $this->db->join('ra_group_product ragp', 'ragp.ra_group_id = ragp.ra_group_id and ragp.ra_group_id in (rap.rcm_group_id_1, rap.rcm_group_id_2, rap.rcm_group_id_3, rap.rcm_group_id_4, rap.rcm_group_id_5, rap.rcm_group_id_6, rap.rcm_group_id_7, rap.rcm_group_id_8, rap.rcm_group_id_9, rap.rcm_group_id_10, rap.rcm_group_id_11, rap.rcm_group_id_12, rap.rcm_group_id_13, rap.rcm_group_id_14, rap.rcm_group_id_15, rap.rcm_group_id_16, rap.rcm_group_id_17, rap.rcm_group_id_18, rap.rcm_group_id_19, rap.rcm_group_id_20)', 'INNER');
        $this->db->join('ra_group rag', 'ragp.ra_group_id = rag.group_id', 'INNER');

        $this->db->where($where);

        $query = $this->db->get();

        if ($query) {
            $ret = [];
            foreach ($query->result('array') as $row) {
                $ret[] = $row;
            }

            return $ret;
        }
        return FALSE;
    }
}


