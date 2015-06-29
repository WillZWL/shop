<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_banner_dao extends Base_dao
{
    private $table_name = "product_banner";
    private $vo_class_name = "Product_banner_vo";
    private $seq_name = "product_banner";
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

    public function get_product_banner($sku = "", $display_id = "", $position_id = "", $lang_id = "en", $classname = "Product_banner_w_graphic_dto")
    {
        $sql = "SELECT pb.sku, pb.display_id, pb.position_id, pb.image_id, pb.flash_id, pb.height, pb.width, pb.link_type, pb.link, pb.priority, pb.status,
                    pb.create_on, pb.create_at, pb.create_by, pb.modify_on, pb.modify_at, pb.modify_by, g.id graphic_id, g.type graphic_type,
                    g.location graphic_location, g.file graphic_file
                FROM product_banner pb
                JOIN graphic g
                    ON pb.image_id = g.id
                WHERE pb.sku = '$sku'
                    AND pb.display_id = $display_id
                    AND pb.position_id = $position_id
                    AND pb.lang_id = '$lang_id'
                    AND pb.status = 1
                    AND g.status = 1
                ";

        $this->include_dto($classname);

        $rs = array();
        if ($query = $this->db->query($sql)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        } else {
            return FALSE;
        }

    }
}

/* End of file Product_banner_dao.php */
/* Location: ./system/application/libraries/dao/Product_banner_dao.php */