<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Cache_product_feed_dao extends Base_dao
{
    private $table_name = "cache_product_feed";
    private $vo_class_name = "Cache_product_feed_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

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

    public function save_xml_skype_feed($data = NULL)
    {
        if (!is_array($data)) {
            return;
        }
//print_r($this->db);
        /*$vo = $this->get();
        $vo->set_sku($data['sku']);
        $vo->set_platform_id($data['platform_id']);
        $this->delete($vo);


        $vo->set_prod_name($data['prod_name']);
        $vo->set_prod_url($data['prod_url']);
        $vo->set_currency_id($data['currency_id']);
        $vo->set_listing_status($data['listing_status']);
        $vo->set_expiry_time('2011-12-09 07:26:23');
        $vo->set_create_at('');
        $vo->set_create_by('');
        $vo->set_modify_at('');
        $vo->set_modify_by('');
        $this->insert($vo);
        return;*/

        $sql = "REPLACE INTO cache_product_feed (
                    sku, platform_id, prod_name, prod_url, currency_id, price,
                    promotion_price, bundle_price, shipping_cost, promo_text,
                    listing_status,
                    expiry_time, create_on, create_at, create_by,
                    modify_on, modify_at, modify_by
                )
                VALUES (
                    ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?,
                    DATE_ADD(now(), INTERVAL ? MINUTE), now(), 'localhost', 'system',
                    now(), 'localhost', 'system'
                );";

        $input = array(
            $data['sku'], $data['platform_id'], $data['prod_name'],
            $data['prod_url'], $data['currency_id'], $data['price'],
            $data['promotion_price'], $data['bundle_price'],
            $data['shipping_cost'], $data['promo_text'],
            $data['listing_status'], $data['cache_time']
        );

        $this->db->query($sql, $input);
    }

    public function get_xml_skype_feed($data = NULL)
    {
        if (empty($data['sku']) || empty($data['platform_id'])) {
            return NULL;
        }

        $sql = "SELECT cpf.sku sku_display,
                    platform_id, prod_name, prod_url, currency_id, price,
                    promotion_price, bundle_price, shipping_cost, promo_text,
                    listing_status
                FROM cache_product_feed cpf
                WHERE sku = ? AND platform_id = ?
                    AND expiry_time >= now() LIMIT 1";

        $input = array($data['sku'], $data['platform_id']);

        $resultset = $this->db->query($sql, $input);

        if (!$resultset) {
            return NULL;
        }

        $result = (array)$resultset->result();
        return (array)$result[0];
    }
}


