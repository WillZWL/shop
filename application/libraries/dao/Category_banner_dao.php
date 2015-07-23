<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Category_banner_dao extends Base_dao
{
    private $table_name = "category_banner";
    private $vo_class_name = "Category_banner_vo";
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

    public function get_cat_ban_list($lang_id = "", $classname = "Cat_ban_list_dto")
    {
        $this->include_dto($classname);
        $sql =
            "
            SELECT cb.cat_id AS id, cb.lang_id, cb.country_id, cb.image, cb.flash, cb.priority, c.name FROM category_banner cb
            LEFT JOIN category c
                ON (c.id = cb.cat_id)
            WHERE cb.lang_id = ?
            GROUP BY cb.priority
            ORDER BY cb.priority
            ";

        if ($query = $this->db->query($sql, array($lang_id))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            if ($rs) {
                return (object)$rs;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
}

