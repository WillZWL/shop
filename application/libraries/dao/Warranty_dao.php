<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Warranty_dao extends Base_dao
{
    private $table_name = "warranty";
    private $vo_class_name = "Warranty_vo";
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

    public function get_warranty_by_sku($sku = "", $platform_id = "")
    {
        if (empty($sku) || empty($platform_id)) {
            return false;
        }

        $this->db->from('product AS p');
        $this->db->join('price AS pr', 'p.sku = pr.sku', 'INNER');
        $this->db->join('platform_biz_var AS pbv', 'pbv.selling_platform_id = pr.platform_id', 'INNER');
        $this->db->join('exchange_rate AS er', "er.from_currency_id = pbv.platform_currency_id AND er.to_currency_id = 'HKD'", 'INNER');
        $this->db->join('(SELECT w.sku, w.min_value, w.max_value, p.sub_cat_id
                            FROM warranty w
                            JOIN product p
                                ON w.sku = p.sku
                            )warr', 'pr.price * er.rate BETWEEN warr.min_value AND warr.max_value', 'INNER');
        $this->db->join('ra_prod_cat AS ra', 'ra.ss_cat_id = p.sub_cat_id AND ra.warranty_cat = warr.sub_cat_id', 'INNER');
        $this->db->where(array('p.sku' => $sku, 'pr.platform_id' => $platform_id));
        $this->db->select("warr.sku");

        if ($query = $this->db->get()) {
            return $query->row()->sku;
        }

        return false;
    }

    public function get_warranty_list_by_sku($sku = "", $platform_id = "", $lang_id = "en")
    {
        if (empty($sku) || empty($platform_id)) {
            return false;
        }

        // Get warranty group id array by sku
        for ($i = 1; $i < 21; $i++) {
            $this->db->from('ra_group as rag');
            $this->db->join('ra_product AS rap', 'rag.group_id = rap.rcm_group_id_' . $i, 'INNER');
            $this->db->join('ra_group_content AS rgc', 'rag.group_id = rgc.group_id and rgc.lang_id = "' . $lang_id . '"', 'LEFT');
            $this->db->where(array("rap.sku" => $sku, "rag.warranty" => "1"));
            $this->db->select("rag.group_id, rgc.group_display_name");

            if ($query = $this->db->get()) {
                $id = $query->row()->group_id;
                if (isset($id)) {
                    $rs[$id]['group_name'] = $query->row()->group_display_name;

                    // Get warrranty products by group Id
                    $this->db->from('ra_group_product AS rgp, product AS p');
                    $this->db->join('warranty AS warr', 'warr.sku = rgp.sku ', 'INNER');
                    $this->db->join("(  select p.sku, pr.price * er.rate as Price
                                        from product p, platform_biz_var pbv, exchange_rate er , price pr
                                        where   pbv.selling_platform_id = pr.platform_id
                                        and     er.from_currency_id = pbv.platform_currency_id
                                        and     er.to_currency_id = 'HKD'
                                        and     pr.sku = p.sku
                                        and     pr.platform_id = '{$platform_id}'
                                        and     p.sku = '{$sku}'
                                        ) temp", 'temp.sku = p.sku', 'INNER');
                    $this->db->where(array('p.sku' => $sku, 'rgp.ra_group_id' => $id, "temp.price between warr.min_value and warr.max_value" => null));
                    $this->db->select("rgp.sku as sku");
                    if ($query = $this->db->get()) {
                        foreach ($query->result() as $row) {
                            $rs[$id]['wlist'][] = $row->sku;
                        }

                    }
                }
            }
        }

        if (isset($rs)) {
            return $rs;
        }

        return false;
    }
}


