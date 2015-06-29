<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Fd_item_dao extends Base_dao
{
    private $table_name="fd_item";
    private $vo_class_name="Fd_item_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct(){
        parent::__construct();
    }

    public function get_vo_classname(){
        return $this->vo_class_name;
    }

    public function get_table_name(){
        return $this->table_name;
    }

    public function get_seq_name(){
        return $this->seq_name;
    }

    public function get_seq_mapping_field(){
        return $this->seq_mapping_field;
    }

    public function get_product_list_with_prod_detail($id, $platform, $lang="en",$classname="Festive_item_dto")
    {
        $sql = "SELECT fdi.sku, fdi.order,pc.prod_name,p.image as image_file_ext, p.website_quantity, p.website_status, pr.price, IFNULL(inv.inv,0) as quantity
                FROM fd_item fdi
                JOIN product_content pc
                    ON pc.prod_sku = fdi.sku
                JOIN (  SELECT sku,price
                        FROM price
                        WHERE platform_id = ?
                        GROUP BY sku) as pr
                    ON pr.sku = fdi.sku
                JOIN product p
                    ON p.sku = fdi.sku
                LEFT JOIN ( SELECT prod_sku, sum(inventory) as inv
                            FROM inventory
                            GROUP BY prod_sku) as inv
                    ON inv.prod_sku = fdi.sku
                WHERE fdssc_id = ?
                ORDER BY fdi.order ASC";

        $this->include_dto($classname);

        if($query = $this->db->query($sql,array($platform,$id)))
        {
            $result = array();
            foreach($query->result($classname) as $obj)
            {
                $result[] = $obj;
            }

            return $result;
        }
        return FALSE;
    }
}
