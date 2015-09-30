<?php
namespace ESG\Panther\Dao;

class ProductSpecDetailsDao extends BaseDao
{
    private $tableName = "product_spec_details";
    private $voClassName = "ProductSpecDetailsVo";

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

    public function getProductSpecWithSku($sku, $lang_id, $classname = "Product_spec_with_sku_dto")
    {
        $sql =
            '
                SELECT psg.func_id AS psg_func_id, psd.ps_id AS ps_func_id,psd.cps_unit_id AS unit_id,  IF(unit_id = "txt",psd.text ,NULL) AS text, psd.start_value, psd.end_value
                FROM product_spec_details psd
                LEFT JOIN category_product_spec cps
                    ON (psd.ps_id = cps.ps_id AND psd.cat_id = cps.cat_id)
                LEFT JOIN product_spec ps
                    ON (psd.ps_id = ps.product_spec_id)
                LEFT JOIN product_spec_group psg
                    ON (ps.psg_id = psg.id)
                WHERE psd.prod_sku = ? AND psd.lang_id = ?
                ORDER BY psg.priority DESC, cps.priority DESC
            ';

        $this->include_dto($classname);

        if ($query = $this->db->query($sql, [$sku, $lang_id])) {
            $rs = $query->result($classname);
            return $rs;
        }
    }

    public function getFullPsdWithLang($sub_cat_id, $sku, $lang_id, $classname = "Product_sd_w_lang_dto")
    {
        $sql =
            '
                SELECT psg.name AS psg_name , ps.product_spec_id AS ps_id, cps.cat_id, ps.name AS ps_name, cps.unit_id, psd.prod_sku, psd.text, psd.start_value, psd.start_standardize_value, psd.end_value, psd.end_standardize_value, psd.status
                FROM product_spec_group psg
                LEFT JOIN product_spec ps
                    ON psg.id = ps.psg_id
                LEFT JOIN category_product_spec cps
                    ON ps.product_spec_id = cps.ps_id AND cps.cat_id = ?
                LEFT JOIN product_spec_details psd
                    ON cps.cat_id = psd.cat_id AND ps.product_spec_id = psd.ps_id AND cps.cat_id = psd.cat_id AND psd.prod_sku = ? AND psd.lang_id = ?
                WHERE psg.status = 1 AND ps.status = 1 AND cps.status = 1
                ORDER BY psg.priority DESC, cps.priority DESC
            ';

        if ($query = $this->db->query($sql, [$sub_cat_id, $sku, $lang_id])) {
            $rs = $query->result($classname);
            return $rs;
        }
    }
}


