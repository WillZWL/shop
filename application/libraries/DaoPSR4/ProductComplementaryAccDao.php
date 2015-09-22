<?php
namespace ESG\Panther\Dao;

class ProductComplementaryAccDao extends BaseDao
{
    private $tableName = "product_complementary_acc";
    private $voClassname = "productComplementaryAccVo";
    private $accessory_catid_arr;

    public function __construct()
    {
        parent::__construct();
        $this->set_accessory_catid_arr();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

    public function get_accessory_catid_arr()
    {
        return $accessory_catid_arr = ["753"];
    }

    public function set_accessory_catid_arr()
    {
        $this->accessory_catid_arr = $this->get_accessory_catid_arr();
    }

    public function checkCat($sku = "", $is_ca = true)
    {
        $ret = [];
        $ret["status"] = false;

        if ($sku) {
            $sql = <<<SQL
                    SELECT * FROM product where sku = ? LIMIT 1
SQL;
            $query = $this->db->query($sql, [$sku]);
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $cat_id = $row->cat_id;
                }

                if ($is_ca) {

                    if (in_array($cat_id, $this->accessory_catid_arr)) {
                        $ret["status"] = true;
                    } else {
                        $ret["message"] = __LINE__ . " product_complementary_acc_dao.php. SKU<$sku> is not a Complementary Accessory.";
                    }
                } else {
                    if (in_array($cat_id, $this->accessory_catid_arr) === false)
                        $ret["status"] = true;
                }
            } else {
                $ret["message"] = __LINE__ . " product_complementary_acc_dao.php. No cat_id exists for SKU<$sku>";
            }
        } else
            $ret["message"] = __LINE__ . " product_complementary_acc_dao.php. No SKU passed in.";

        return $ret;
    }

    public function getMappedAccListWithName($where = [], $option = [], $active = true, $classname = "ComplementaryAccessoryListDto")
    {
        $this->db->from("product_complementary_acc AS pca");

        if (empty($option["all_status"])) {
            $where["accmap.status"] = 1;
        }

        $this->db->join("sku_mapping AS accmap", "accmap.sku = pca.accessory_sku AND accmap.ext_sys='WMS'", "INNER");
        $this->db->join("product AS p", "pca.accessory_sku = p.sku", "inner");
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');

        if ($active) {
            $where["pca.status"] = 1;
        }
        $this->db->where($where);

        if (empty($option["num_rows"])) {
            $this->db->select('
                                pca.id, pca.mainprod_sku, pca.accessory_sku, pca.dest_country_id, pca.status AS ca_status,
                                pca.modify_on, pca.modify_at, pca.modify_by, pca.create_on, pca.create_at, pca.create_by,
                                p.name,
                                c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.image AS image_file
                            ');
            $option["limit"] = "";

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            } else {
                $this->db->order_by("ca_status DESC, pca.mainprod_sku ASC");
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if (!empty($this->rows_limit)) {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = [];
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            } else {
                return NULL;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;

    }

    public function get_all_accessory($where = [], $option = [], $like = [], $classname = "ComplementaryAccessoryListDto")
    {
        $ca_catid = implode(',', $this->accessory_catid_arr);

        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys='WMS' AND map.status=1", "INNER");
        $this->db->join('category AS c', 'p.cat_id = c.id', 'INNER');

        if ($where) {
            $this->db->where($where);
        }

        $like_clause = "";
        if (is_array($like)) {
            $count = 0;
            foreach ($like as $key => $value) {
                if ($count == 0)
                    $like_clause .= "$key LIKE '%" . $this->db->escape_str($value) . "%'";
                else
                    $like_clause .= " OR $key LIKE '%" . $this->db->escape_str($value) . "%'";

                $count++;
            }
            $like_clause = "($like_clause)";
            $this->db->where($like_clause);
        }
        $this->db->where_in("p.cat_id", $ca_catid);
        $this->db->select('
                                p.sku AS accessory_sku, p.name AS name, c.name AS category
                            ');

        if (!isset($option["offset"])) {
            $option["offset"] = 0;
        }
        if (!$option["limit"]) {
            $option["limit"] = "";
        }
        if ($this->rows_limit != "") {
            $this->db->limit($option["limit"], $option["offset"]);
        }

        $rs = [];
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return (object)$rs;
        }

    }

}
