<?php defined('BASEPATH') OR exit('No direct script access allowed');


include_once 'Base_dao.php';

class Product_complementary_acc_dao extends Base_dao
{
    private $table_name="product_complementary_acc";
    private $vo_classname="product_complementary_acc_vo";
    private $seq_name="";
    private $seq_mapping_field="";
    private $accessory_catid_arr;

    public function __construct()
    {
        parent::__construct();
        $this->set_accessory_catid_arr();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function set_accessory_catid_arr()
    {
        $this->accessory_catid_arr = $this->get_accessory_catid_arr();
    }

    public function get_accessory_catid_arr()
    {
        /* ======================================================================
            IMPORTANT!
            This will get all current category IDs that below to Complementary Accessories
            if there are more accessory cat ids in future,
            add it to this array $ca_catid_arr.
        ========================================================================= */

        return $accessory_catid_arr = array("753");
    }

    public function check_cat($sku="", $is_ca = true)
    {
        # if you want to check if product is NOT a complementary accessory,
        # then set $is_ca to false
        $ret = array();
        $ret["status"] = false;

        if($sku)
        {
            $sql = <<<SQL
                    SELECT * FROM product where sku = ? LIMIT 1
SQL;
            $query = $this->db->query($sql, array($sku));
            if($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $cat_id = $row->cat_id;
                }

                if($is_ca)
                {
                    // check if product SKU is in complementary accessory category
                    if(in_array($cat_id, $this->accessory_catid_arr))
                        $ret["status"] = true;
                    else
                        $ret["message"] = __LINE__." product_complementary_acc_dao.php. SKU<$sku> is not a Complementary Accessory.";
                }
                else
                {
                    if(in_array($cat_id, $this->accessory_catid_arr) === false)
                        $ret["status"] = true;
                }
            }
            else
            {
                $ret["message"] = __LINE__." product_complementary_acc_dao.php. No cat_id exists for SKU<$sku>";
            }
        }
        else
            $ret["message"] = __LINE__." product_complementary_acc_dao.php. No SKU passed in.";

        return $ret;
    }

    public function get_mapped_acc_list_w_name($where = array(), $option = array(), $active = true, $classname = "Complementary_accessory_list_dto")
    {
        $this->db->from("product_complementary_acc AS pca");
        # default to only active mapped status
        if(!$option["all_status"])
            $where["accmap.status"] = 1;

        #sbf #3746 complementary accessory must be mapped
        $this->db->join("sku_mapping AS accmap", "accmap.sku = pca.accessory_sku AND accmap.ext_sys='WMS'", "INNER");
        $this->db->join("product AS p", "pca.accessory_sku = p.sku", "inner");
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');
        // $this->db->join('supplier_prod AS sp', 'sp.prod_sku = pca.accessory_sku', 'LEFT');
        if($active)
        {
            $where["pca.status"] = 1;
        }
        $this->db->where($where);

        if (empty($option["num_rows"]))
        {
            $this->include_dto($classname);
            $this->db->select('
                                pca.id, pca.mainprod_sku, pca.accessory_sku, pca.dest_country_id, pca.status AS ca_status,
                                pca.modify_on, pca.modify_at, pca.modify_by, pca.create_on, pca.create_at, pca.create_by,
                                p.name,
                                c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.image AS image_file
                            ');
            $option["limit"] = "";

            if (isset($option["orderby"]))
            {
                $this->db->order_by($option["orderby"]);
            }
            else
            {
                $this->db->order_by("ca_status DESC, pca.mainprod_sku ASC");
            }

            if (!isset($option["offset"]))
            {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "")
            {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();
            $query = $this->db->get();

            if ($query->num_rows() > 0)
            {
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
            }
            else
            {
                return NULL;
            }
        }
        else
        {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }

        return FALSE;

    }

    public function get_all_accessory($where=array(), $option=array(), $like=array(), $classname = "Complementary_accessory_list_dto")
    {
        $ca_catid = implode(',', $this->accessory_catid_arr);

        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys='WMS' AND map.status=1", "INNER");
        $this->db->join('category AS c', 'p.cat_id = c.id', 'INNER');
        // $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        // $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        // $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        // $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');
        if($where)
        {
            $this->db->where($where);
        }

        $like_clause = "";
        if(is_array($like))
        {
            # if more than one $like array passed in, then make it as OR WHERE $key LIKE '%$value%'
            $count = 0;
            foreach ($like as $key => $value)
            {
                if($count == 0)
                    $like_clause .= "$key LIKE '%".$this->db->escape_str($value)."%'";
                else
                    $like_clause .= " OR $key LIKE '%".$this->db->escape_str($value)."%'";

                $count++;
            }
            $like_clause = "($like_clause)";
            $this->db->where($like_clause);
        }
        $this->db->where_in("p.cat_id", $ca_catid);
        $this->db->select('
                                p.sku AS accessory_sku, p.name AS name, c.name AS category
                            ');

        if (!isset($option["offset"]))
        {
            $option["offset"] = 0;
        }
        if(!$option["limit"])
        {
            $option["limit"] = "";
        }
        if ($this->rows_limit != "")
        {
            $this->db->limit($option["limit"], $option["offset"]);
        }

        // SELECT `p`.`sku` AS accessory_sku, `p`.`name` AS name, `c`.`name` AS category
        // FROM (`product` AS p) INNER JOIN `category` AS c ON `p`.`cat_id` = `c`.`id`
        // WHERE (p.name LIKE '%%te%%' OR p.sku LIKE '%%te%%') AND `p`.`cat_id` IN ('750') LIMIT 50
        $rs = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }

            return (object) $rs;
        }

    }

}
