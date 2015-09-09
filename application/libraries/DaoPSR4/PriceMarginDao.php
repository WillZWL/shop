<?php
namespace ESG\Panther\Dao;

class PriceMarginDao extends BaseDao
{
    private $tableName = "price_margin";
    private $voClassName = "PriceMarginVo";

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

    public function get_last_update()
    {
        $this->db->from("$this->tableName AS pm");
        $this->db->select('MAX(pm.modify_on) last_modify_on');

        $resultset = $this->db->get();

        if ($resultset) {
            return $resultset->result('object', '');
        } else {
            return FALSE;
        }
    }

    public function replace($obj = NULL)
    {
        if (!$obj) {
            return false; // Nothing can replace.
        }

        $id = "system";
        if (isset($_SESSION["user"]["id"])) {
            $id = $_SESSION["user"]["id"];
        }

        $this->db->trans_start();
        $sql = 'INSERT INTO price_margin (sku, platform_id, profit, margin,
                    create_on, create_at, create_by, modify_on, modify_at, modify_by)
                VALUES(?, ?, ?, ?, now(), ?, ?, now(), ?, ?)
                    ON DUPLICATE KEY UPDATE
                        profit = ?, margin = ?, modify_on = now(), modify_by = ?';

        $this->db->query($sql,
            [
                $obj->getSku(), $obj->getPlatformId(),
                $obj->getProfit(), $obj->getMargin(),
                'localhost', $id, 'localhost', $id,
                $obj->getProfit(), $obj->getMargin(), $id
            ]
        );
        $this->db->trans_commit();
        return;

    }

    public function get_cross_sell_product($prod_info, $platform_id, $language_id, $price, $price_adjustment, $classname = 'cross_selling_product_dto')
    {
        $cat_id = $prod_info->getCatId();
        $sub_cat_id = $prod_info->getSubCatId();
        $sub_sub_cat_id = $prod_info->getSubSubCatId();

        if ($cat_id == 2) {
            $cat_selection = " p.sub_sub_cat_id = {$sub_sub_cat_id} ";
        } else {
            $cat_selection = " p.sub_cat_id = {$sub_cat_id} ";
        }

        $sku = $prod_info->get_sku();

        $sql = "SELECT
                    p.sku AS sku, pc.prod_name AS prod_name, price.price AS price, p.image AS image_ext, 10 AS rrp_price,
                    IF(p.display_quantity > p.website_quantity,p.website_quantity, p.display_quantity) AS qty,
                    pm.margin,
                    p.cat_upselling, p.website_status as `status`, price.fixed_rrp, price.rrp_factor
                FROM product AS p
                INNER JOIN product_content AS pc
                    ON pc.prod_sku = p.sku AND pc.lang_id = '{$language_id}'
                LEFT JOIN price_margin AS pm
                    ON pm.sku = p.sku and pm.platform_id = '{$platform_id}'
                INNER JOIN price
                    ON price.sku = p.sku AND price.platform_id = '{$platform_id}' and price.price between ($price-$price_adjustment) and ($price+$price_adjustment)
                WHERE

                {$cat_selection}

                and p.display_quantity>0 and p.website_quantity>0 and (p.website_status = 'I' OR p.website_status = 'P') and p.sku != '{$sku}'
                    and price.listing_status = 'L'
                ORDER BY
                    p.cat_upselling DESC, pm.margin DESC
                LIMIT 3
                ";

        if ($result = $this->db->query($sql)) {
            $this->include_dto($classname);
            $result_arr = array();

            foreach ($result->result($classname) as $obj) {
                $result_arr[] = $obj;
            }
            return $result_arr;
        }

        return false;
    }
}


