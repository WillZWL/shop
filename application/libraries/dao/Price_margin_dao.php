<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Price_margin_dao extends Base_dao
{
    private $table_name="price_margin";
    private $vo_class_name="Price_margin_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_last_update()
    {
        $this->db->from("$this->table_name AS pm");
        $this->db->select('MAX(pm.modify_on) last_modify_on');

        $resultset = $this->db->get();

        if ($resultset)
        {
            return $resultset->result('object','');
        }
        else
        {
            return FALSE;
        }
    }

    public function replace($obj = NULL)
    {
        if (!$obj)
        {
            return false; // Nothing can replace.
        }

        $id = "system";
        if(isset($_SESSION["user"]["id"]))
            $id = $_SESSION["user"]["id"];


        // #sbf #4254 fix bug; need transaction
        $this->db->trans_start();
        $sql = 'INSERT INTO price_margin (sku, platform_id, profit, margin,
                    create_on, create_at, create_by, modify_on, modify_at, modify_by)
                VALUES(?, ?, ?, ?, now(), ?, ?, now(), ?, ?)
                    ON DUPLICATE KEY UPDATE
                        profit = ?, margin = ?, modify_on = now(), modify_by = ?';

/*      return $this->db->query($sql, array($obj->get_sku(), $obj->get_platform_id(),
            $obj->get_shiptype(), $obj->get_profit(), $obj->get_margin(),
            $_SERVER["SERVER_ADDR"], 'system', $_SERVER["SERVER_ADDR"], 'system',
            $obj->get_shiptype(), $obj->get_profit(), $obj->get_margin()));*/
         $this->db->query($sql,
                            array($obj->get_sku(), $obj->get_platform_id(),
                                    $obj->get_profit(), $obj->get_margin(),
                                    'localhost', $id, 'localhost', $id,
                                    $obj->get_profit(), $obj->get_margin(), $id)
                                );
          $this->db->trans_commit();
          return;

    }

    public function get_cross_sell_product( $prod_info, $platform_id, $language_id, $price, $price_adjustment, $classname = 'cross_selling_product_dto')
    {
            $cat_id = $prod_info->get_cat_id();
            $sub_cat_id = $prod_info->get_sub_cat_id();
            $sub_sub_cat_id = $prod_info->get_sub_sub_cat_id();

            if($cat_id == 2)
            {
                $cat_selection = " p.sub_sub_cat_id = {$sub_sub_cat_id} ";
            }
            else
            {
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

        if($result = $this->db->query($sql))
        {
            $this->include_dto($classname);
            $result_arr = array();

            foreach($result->result("object", $classname) as $obj)
            {
                $result_arr[] = $obj;
            }
            return $result_arr;
        }

        return FALSE;
    }
}

/* End of file Price_margin_dao.php */
/* Location: ./system/application/libraries/dao/Price_margin_dao.php */