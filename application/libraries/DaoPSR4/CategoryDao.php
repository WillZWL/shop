<?php
namespace ESG\Panther\Dao;

class CategoryDao extends BaseDao
{
    private $tableName = "category";
    private $voClassname = "CategoryVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

    public function getCategoryName($lang_id)
    {
        $query = $this->db->query('select c.id, COALESCE(ce.name, c.name) as name from category c left join category_extend ce on c.id = ce.cat_id where status = 1 and lang_id = ?', $lang_id);
        return $query->result_array();
    }

    public function getCategoryFullPath($where = [], $option = [], $className = 'CategoryFullPathDto')
    {
        $this->db->from('category c1');
        $this->db->join('category c2', 'c1.parent_cat_id = c2.id', 'inner');
        $this->db->join('category c3', 'c2.parent_cat_id = c3.id', 'inner');
        $this->db->where(['c1.status' => 1, 'c2.status' => 1, 'c3.status' => 1]);

        $select = 'c1.id as cat_id, c1.name as name, c1.level as level, c2.name as top_name, c2.level as top_level, c3.name as top_top_name, c3.level as top_top_level';

        return $this->commonGetList($className, $where, $option, $select);
    }

    public function getItemWithChildCount($this_level, $id, $classname)
    {
        $sql = 'SELECT a.id, a.name, IFNULL(b.num_of_record,0) AS count_row
                FROM category a
                LEFT JOIN
                    (SELECT c.parent_cat_id,count(1) as num_of_record
                     FROM category c
                     WHERE c.level = ?
                     GROUP BY c.parent_cat_id) b
                     ON b.parent_cat_id = a.id
                WHERE a.level = ?
                AND a.id <> \'0\'';
        if ($id != "") {
            $sql .= ' AND a.parent_cat_id = \'' . $id . '\' ';
        }
        $sql .= 'ORDER BY a.name';

        $rs = [];

        if ($query = $this->db->query($sql, [$this_level + 1, $this_level])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return (object)$rs;
        } else {
            echo mysql_error();
            return FALSE;
        }
    }

    public function getItemWithPopChildCount($this_level, $id, $classname, $order = "priority")
    {
        $_order = $order;
        if (strpos($order, ".") < 0) {
            $_order = "a." . $_order;
        }

        $sql = 'SELECT a.id, a.name, IFNULL(b.num_of_record,0) AS count_row
                FROM category a
                LEFT JOIN
                    (SELECT c.parent_cat_id,count(1) as num_of_record
                     FROM category c
                     WHERE c.level = ?
                        AND c.status = 1
                     GROUP BY c.parent_cat_id) b
                     ON b.parent_cat_id = a.id
                WHERE a.level = ?
                AND a.id <> \'0\'
                AND a.status = 1
                AND b.num_of_record > 0
                AND b.num_of_record IS NOT NULL';;
        if ($id != "") {
            $sql .= ' AND a.parent_cat_id = \'' . $id . '\' ';
        }
        $sql .= 'ORDER BY ' . $_order;

        $rs = [];

        if ($query = $this->db->query($sql, [$this_level + 1, $this_level])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return (object)$rs;
        } else {
            echo mysql_error();
            return FALSE;
        }
    }


    public function getParent($level, $id, $classname)
    {
        $sql = "";
        if ($level == 3) {
            $sql .= 'SELECT v.*, c.name as sub_cat_name, cc.name as cat_name
                    FROM v_sub_sub_category v
                    JOIN category c
                        ON v.sub_cat_id = c.id
                    JOIN category cc
                        ON v.cat_id = cc.id
                    WHERE sub_sub_cat_id = ?
                    LIMIT 1';
        } else if ($level == 2) {
            $sql .= 'SELECT v.*, c.name as cat_name
                    FROM v_sub_category v
                    JOIN category c
                        ON v.cat_id = c.id
                    WHERE sub_cat_id = ?
                    LIMIT 1';
        } else {
            return FALSE;
        }

        $rs = [];

        if ($query = $this->db->query($sql, [$id])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs[0];
        } else {
            echo mysql_error();
            return FALSE;
        }
    }

    public function getFullCatList()
    {
        $sql = "SELECT c.id cat_id, c.name cat_name, sc.id sub_cat_id, sc.name sub_cat_name, ssc.id sub_sub_cat_id, ssc.name sub_sub_cat_name
                FROM category c
                JOIN category sc
                    ON c.id = sc.parent_cat_id
                LEFT JOIN category ssc
                    ON sc.id = ssc.parent_cat_id
                WHERE c.level = 1 AND sc.level = 2 AND (ssc.level = 3 OR ssc.level IS NULL)";

        $result = $this->db->query($sql);

        if (!$result) {
            return FALSE;
        }

        return $result->result_array();

    }

    public function getListedCat($platform_id = "WEBHK")
    {
        $this->db->select('DISTINCT c.id AS cat_id, sc.id AS sub_cat_id, ssc.id AS sub_sub_cat_id, c.sponsored', FALSE);
        $this->db->from('product AS p');
        $this->db->join('price AS pr', 'pr.sku = p.sku AND pr.platform_id = "' . $platform_id . '" AND pr.listing_status = "L"');
        $this->db->join('platform_biz_var AS pbv', 'pbv.selling_platform_id = pr.platform_id');
        $this->db->join('category AS c', 'c.id = p.cat_id AND c.id > 0 AND c.status = 1');
        $this->db->join('category AS sc', 'sc.id = p.sub_cat_id AND sc.id > 0 AND sc.status = 1');
        $this->db->join('category AS ssc', 'ssc.id = p.sub_sub_cat_id AND ssc.id > 0 AND ssc.status = 1', 'left');
        $this->db->where('p.status', 2);
        $this->db->order_by('c.sponsored DESC, c.id, sc.id, ssc.id');

        $result = $this->db->get();

        if (!$result) {
            return FALSE;
        }

        return $result->result_array();
    }

    public function getListedCatTree()
    {
        $sql = 'SELECT DISTINCT ssc.id sub_sub_cat_id, ssc.name sub_sub_cat_name,
                    sc.id sub_cat_id, sc.name sub_cat_name,
                    c.id cat_id, c.name cat_name
                FROM category ssc
                JOIN category sc ON (ssc.parent_cat_id = sc.id AND sc.status = 1)
                JOIN category c ON (sc.parent_cat_id = c.id AND c.status = 1)
                WHERE ssc.status = 1
                ORDER BY c.priority, c.id, sc.priority, sc.id, ssc.priority, ssc.id';

        $sql = 'SELECT DISTINCT ssc.id sub_sub_cat_id, ssc.name sub_sub_cat_name, sc.id sub_cat_id, sc.name sub_cat_name, c.id cat_id, c.name cat_name FROM category ssc
                JOIN category sc ON (ssc.parent_cat_id = sc.id AND sc.status = 1)
                JOIN category c ON (sc.parent_cat_id = c.id AND c.status = 1)
                WHERE ssc.status = 1 and ssc.parent_cat_id <> ssc.id
                ORDER BY c.priority, c.id, sc.priority, sc.id, ssc.priority, ssc.id';

        $result = $this->db->query($sql);

        if (!$result) {
            return FALSE;
        }

        return $result->result_array();
    }

    public function getListIndex($where = [], $option = [], $classname = "")
    {

        $this->db->from('category');

        if (!empty($where["name"])) {
            $this->db->like('name', $where["name"]);
        }

        if (!empty($where["description"])) {
            $this->db->like('description', $where["description"]);
        }

        if (!empty($where["parent_cat_id"])) {
            $this->db->where('parent_cat_id', $where['parent_cat_id']);
        }

        if (!empty($where["level"])) {
            $this->db->like('level', $where["level"]);
        }

        if (!empty($where["status"])) {
            $this->db->where('status', $where["status"]);
        }

        $this->db->where('id >', 0);

        if (empty($option["orderby"])) {
            $option["orderby"] = "id ASC";
        }

        if (empty($option["num_rows"])) {

            $this->db->select('id, name, description, level, status, create_on, create_at, create_by, modify_on, modify_at, modify_by');

            $this->db->order_by($option["orderby"]);

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if (!empty($this->rows_limit)) {
                $this->db->limit($option["limit"], $option["offset"]);
            }


            $rs = [];

            if ($query = $this->db->get()) {

                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            } else {
                echo mysql_error();
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function getProdCat($sku, $classname = "Prod_cat_ws_dto")
    {
        if ($sku == "") {
            return FALSE;
        }

        $this->db->from('product p');

        $this->db->join("(SELECT id, name
                          FROM category
                          WHERE level='1') AS c", "c.id = p.cat_id", "INNER");

        $this->db->join("(SELECT id, name
                          FROM category
                          WHERE level='2') AS sc", "sc.id = p.sub_cat_id", "INNER");

        $this->db->join("(SELECT id, name
                          FROM category
                          WHERE level='3') AS ssc", "ssc.id = p.sub_sub_cat_id", "INNER");

        $this->db->where(["p.sku" => $sku]);

        $this->db->select("p.sku, c.id as cat_id, c.name as cat_name, sc.id as scat_id, sc.name as scat_name, ssc.id as sscat_id, ssc.name as sscat_name");

        $this->db->limit(1);

        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $temp) {
                $obj = $temp;
            }

            return $obj;
        }
    }

    public function retrieveBrandlistForCat($catid, $brand = "", $platform_id = "WEBGB", $classname = "Brand_name_item_cnt_dto")
    {
        $sql = "SELECT p.brand_id, b.brand_name, count(p.sku) as total
            FROM product p
            JOIN brand b
                ON b.id = p.brand_id
            LEFT JOIN (price pr, v_default_platform_id vdp)
                ON pr.sku = p.sku
                AND pr.listing_status = 'L'
                AND pr.platform_id = vdp.platform_id
            LEFT JOIN price pr2
                ON pr2.sku = p.sku
                AND pr2.platform_id = '$platform_id'
            WHERE p.cat_id = ?
            AND (pr2.listing_status = 'L')
            AND (pr2.price OR pr.price) > 0
            AND p.status = '2'";

        if ($brand != "") {
            $sql .= " AND p.brand_id='$brand' ";
        }
        $sql .= "GROUP BY p.brand_id
                ORDER BY brand_name";

        $rs = [];

        if ($query = $this->db->query($sql, $catid)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }
        return FALSE;

    }

    public function retrieveCatlistForScat($catid, $brand = "", $platform_id = "WEBGB", $classname = "CatNameItemCntDto")
    {
        $sql = "SELECT cat.id, cat.name, s.total
                FROM category cat
                JOIN(
                    SELECT sub_cat_id, COUNT(p.sku) AS total
                    FROM product p
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON pr.sku = p.sku
                        AND pr.listing_status = 'L'
                        AND pr.platform_id = vdp.platform_id
                    LEFT JOIN price pr2
                        ON pr2.sku = p.sku
                        AND pr2.platform_id = '$platform_id'
                    WHERE (pr2.listing_status = 'L')
                    AND (pr2.price OR pr.price) > 0
                    AND p.status = '2'";
        if ($brand != "") {
            $sql .= " AND p.brand_id='$brand' ";
        }

        $sql .= "GROUP BY sub_cat_id) AS s
                    ON s.sub_cat_id = cat.id
                WHERE cat.level = '2'
                AND cat.parent_cat_id = ?
                AND cat.status = '1'
                ORDER BY ISNULL(cat.priority), cat.priority, cat.id";

        $rs = [];

        if ($query = $this->db->query($sql, $catid)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }
        return FALSE;
    }

    public function retrieveBrandlistForScat($catid, $brand = "", $platform_id = "WEBGB", $classname = "Brand_name_item_cnt_dto")
    {
        $sql = "SELECT p.brand_id,b.brand_name, count(p.sku) as total
                FROM product p
                JOIN brand b
                    ON b.id = p.brand_id
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON pr.sku = p.sku
                    AND pr.listing_status = 'L'
                    AND pr.platform_id = vdp.platform_id
                LEFT JOIN price pr2
                    ON pr2.sku = p.sku
                    AND pr2.platform_id = '$platform_id'
                WHERE p.sub_cat_id = ?
                AND (pr2.listing_status = 'L')
                AND (pr2.price OR pr.price) > 0
                AND p.status = '2'";
        if ($brand != "") {
            $sql .= " AND p.brand_id='$brand' ";
        }

        $sql .= "       GROUP BY brand_id
                        ORDER BY brand_name";

        $rs = [];

        if ($query = $this->db->query($sql, $catid)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }
        return FALSE;

    }


    public function retrieveCatlistForSscat($catid, $brand = "", $platform_id = "WEBGB", $classname = "CatNameItemCntDto")
    {
        $sql = "SELECT cat.id, cat.name, s.total
                FROM category cat
                JOIN( SELECT sub_sub_cat_id, COUNT(p.sku) AS total
                          FROM product p
                          LEFT JOIN (price pr, v_default_platform_id vdp)
                                ON pr.sku = p.sku
                                AND pr.listing_status = 'L'
                                AND pr.platform_id = vdp.platform_id
                          LEFT JOIN price pr2
                                ON pr2.sku = p.sku
                                AND pr2.platform_id = '$platform_id'
                          WHERE (pr2.listing_status = 'L')
                          AND (pr2.price OR pr.price) > 0
                          AND p.status = '2'";
        if ($brand != "") {
            $sql .= "         AND p.brand_id='$brand' ";
        }
        $sql .= "             GROUP BY sub_sub_cat_id) AS s
                        ON s.sub_sub_cat_id = cat.id
                WHERE cat.level = '3'
                AND cat.parent_cat_id =?
                AND cat.status = '1'";

        $rs = [];

        if ($query = $this->db->query($sql, $catid)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }
        return FALSE;
    }

    public function retrieveBrandlistForSscat($catid, $brand = "", $platform_id = "WEBGB", $classname = "Brand_name_item_cnt_dto")
    {
        $sql = "SELECT  p.brand_id,b.brand_name, count(p.sku) as total
                FROM product p
                JOIN brand b
                    ON b.id = p.brand_id
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON pr.sku = p.sku
                    AND pr.listing_status = 'L'
                    AND pr.platform_id = vdp.platform_id
                LEFT JOIN price pr2
                    ON pr2.sku = p.sku
                    AND pr2.platform_id = '$platform_id'
                WHERE p.sub_sub_cat_id = ?
                AND (pr2.listing_status = 'L')
                AND (pr2.price OR pr.price) > 0
                AND p.status = '2'";
        if ($brand != "") {
            $sql .= " AND p.brand_id = '$brand' ";
        }
        $sql .= "   GROUP BY brand_id
                    ORDER BY brand_name";

        $rs = [];

        if ($query = $this->db->query($sql, $catid)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }
        return FALSE;
    }

    public function retrievePricelistForCat($catid, $brand = "", $platform_id = "WEBGB", $min_price = "", $max_price = "", $classname = "")
    {
        $sql = "SELECT count(p.sku) as total
            FROM product p
            JOIN brand b
                ON b.id = p.brand_id
            LEFT JOIN (price pr, v_default_platform_id vdp)
                ON pr.sku = p.sku
                AND pr.listing_status = 'L'
                AND pr.platform_id = vdp.platform_id
            LEFT JOIN price pr2
                ON pr2.sku = p.sku
                AND pr2.platform_id = '$platform_id'
            JOIN platform_biz_var pbv
                ON (pbv.selling_platform_id = '$platform_id')
            JOIN exchange_rate ex
                ON (pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP')
            WHERE (pr2.listing_status = 'L') AND p.cat_id = ?
            AND p.status = '2'
            AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > $min_price)";

        if ($max_price != "") {
            $sql .= " AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= $max_price)";
        }

        if ($query = $this->db->query($sql, $catid)) {
            return $query->row()->total;
        }
        return FALSE;
    }

    public function retrievePricelistForScat($catid, $brand = "", $platform_id = "WEBGB", $min_price = "", $max_price = "", $classname = "")
    {
        $sql = "SELECT count(p.sku) as total
                FROM product p
                JOIN brand b
                    ON b.id = p.brand_id
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON pr.sku = p.sku
                    AND pr.listing_status = 'L'
                    AND pr.platform_id = vdp.platform_id
                LEFT JOIN price pr2
                    ON pr2.sku = p.sku
                    AND pr2.platform_id = '$platform_id'
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = '$platform_id')
                JOIN exchange_rate ex
                    ON (pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP')
                WHERE (pr2.listing_status = 'L') AND p.sub_cat_id = ?
                AND p.status = '2'
                AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > $min_price)";

        if ($max_price != "") {
            $sql .= " AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= $max_price)";
        }

        if ($query = $this->db->query($sql, $catid)) {
            return $query->row()->total;
        }
        return FALSE;
    }

    public function retrievePricelistForSscat($catid, $brand = "", $platform_id = "WEBGB", $min_price = "", $max_price = "", $classname = "")
    {
        $sql = "SELECT count(p.sku) as total
                FROM product p
                JOIN brand b
                    ON b.id = p.brand_id
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON pr.sku = p.sku
                    AND pr.listing_status = 'L'
                    AND pr.platform_id = vdp.platform_id
                LEFT JOIN price pr2
                    ON pr2.sku = p.sku
                    AND pr2.platform_id = '$platform_id'
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = '$platform_id')
                JOIN exchange_rate ex
                    ON (pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP')
                WHERE (pr2.listing_status = 'L') AND p.sub_sub_cat_id = ?
                AND p.status = '2'
                AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > $min_price)";

        if ($max_price != "") {
            $sql .= " AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= $max_price)";
        }

        if ($query = $this->db->query($sql, $catid)) {
            return $query->row()->total;
        }
        return FALSE;
    }


    public function getChildWithCount($level = '1', $id = '0', $status = '1', $classname = "CatNameItemCntDto")
    {
        $sql = "SELECT c.id, c.name, IFNULL(s.ttl,0) as total
                FROM category c
                LEFT JOIN (SELECT cc.parent_cat_id, count(cc.id) as ttl
                      FROM category cc
                      GROUP BY cc.parent_cat_id) AS s
                    ON s.parent_cat_id = c.id
                WHERE c.level = ?
                AND c.parent_cat_id = ?
                AND c.status = '1'
                AND id <> '0'
                ORDER BY c.name ASC";

        $rs = [];

        if ($query = $this->db->query($sql, [$level, $id])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function getCombinedCatList($where = [], $option = [], $classname = "Cat_sub_cat_id_dto")
    {
        $sql = "SELECT sc.id, CONCAT(c.name,' -> ',sc.name) as name
                FROM category as c
                JOIN category as sc
                  ON c.id = sc.parent_cat_id
                WHERE c.id <> '0'
                UNION ALL
                SELECT id, name FROM category
                WHERE level = '1' and name <> 'base'
                ORDER BY name";

        $rs = [];

        if ($query = $this->db->query($sql)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function getFavouriteCategory($limit = 1, $platform)
    {
        $sql = "SELECT c.id, SUM(so_item.amount) ttl_sales
                FROM category c
                JOIN product p
                ON c.id = p.cat_id
                JOIN so_item
                ON so_item.prod_sku = p.sku
                JOIN so
                ON so.so_no = so_item.so_no
                WHERE p.website_status= 'I'
                AND p.website_quantity > 0
                AND so.platform_id = '$platform'
                AND DATEDIFF(CURDATE(),so.order_create_date) <=14
                GROUP BY c.id
                ORDER BY ttl_sales DESC, c.id
                LIMIT $limit";

        $rs = [];

        if ($query = $this->db->query($sql)) {
            foreach ($query->result("array") AS $arr) {
                $rs[] = $arr;
            }
            return $rs;
        }
        return FALSE;
    }

    public function getMenuArrWithLang($lang_id)
    {
        $sql = "
                SELECT c.id, ce.name, ce.lang_id, c.level, c.parent_cat_id
                FROM category as c
                JOIN category_extend as ce
                    on c.id = ce.cat_id
                WHERE c.id > 0 AND ce.lang_id = ?
                ORDER BY c.level, ISNULL(c.priority), c.priority, c.id
                ";

        if ($query = $this->db->query($sql, $lang_id)) {
            foreach ($query->result_array() as $k => $v) {
                $data["list"][$v["level"]][$v["parent_cat_id"]][] = $v;
                $data["allcat"][$v["parent_cat_id"]][] = $v;
            }
            return $data;
        }
        return FALSE;
    }

    public function getMenuListWithPlatformId($lang_id, $platform_id, $classname = "Menu_list_w_lang_dto")
    {
        $sql = "SELECT c.id, IFNULL(ce.name,c.name) name, ce.lang_id, c.level, c.parent_cat_id
                FROM category as c
                LEFT JOIN category_extend as ce
                    on c.id = ce.cat_id AND ce.lang_id = ?
                LEFT JOIN
                (
                    SELECT p.sku, p.cat_id, p.sub_cat_id, count(*) total
                    FROM product p
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                    LEFT JOIN price pr2
                        ON (p.sku = pr2.sku AND pr2.platform_id = '$platform_id')
                    WHERE
                        p.status = 2
                        AND (pr2.listing_status = 'L')
                        AND p.website_status = 'I'
                        AND p.website_quantity > 0
                        AND (pr.price OR pr2.price) > 0
                    GROUP BY p.cat_id
                )cat
                    ON c.id = cat.cat_id
                LEFT JOIN
                (
                    SELECT p.sku, p.cat_id, p.sub_cat_id, count(*) total
                    FROM product p
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                    LEFT JOIN price pr2
                        ON (p.sku = pr2.sku AND pr2.platform_id = '$platform_id')
                    WHERE
                        p.status = 2
                        AND (pr2.listing_status = 'L')
                        AND p.website_status = 'I'
                        AND p.website_quantity > 0
                        AND (pr.price OR pr2.price) > 0
                    GROUP BY p.sub_cat_id
                )sc
                    ON c.id = sc.sub_cat_id
                WHERE c.id > 0 AND c.status = 1 AND if(cat.total>0, cat.total, sc.total) > 0
                ORDER BY c.level, ISNULL(c.priority), c.priority, c.id";

        if ($query = $this->db->query($sql, $lang_id)) {
            foreach ($query->result($classname) as $obj) {
                $data["list"][$obj->getLevel()][$obj->getParentCatId()][] = $obj;
                $data["allcat"][$obj->getParentCatId()][] = $obj;
            }
            return $data;
        }

        return FALSE;
    }

    public function getMenuListWithLang($lang_id, $classname = "Menu_list_w_lang_dto")
    {
        $sql = "
                SELECT c.id, IFNULL(ce.name,c.name) name, ce.lang_id, c.level, c.parent_cat_id
                FROM category as c
                LEFT JOIN category_extend as ce
                    on c.id = ce.cat_id AND ce.lang_id = ?
                WHERE c.id > 0 AND c.status = 1
                ORDER BY c.level, ISNULL(c.priority), c.priority, c.id
                ";

        if ($query = $this->db->query($sql, $lang_id)) {
            foreach ($query->result($classname) as $obj) {
                $data["list"][$obj->getLevel()][$obj->getParentCatId()][] = $obj;
                $data["allcat"][$obj->getParentCatId()][] = $obj;
            }
            return $data;
        }

        return FALSE;
    }

    public function getBestSellingCat($platform = "WEBGB", $lang_id = "en", $classname = "Best_selling_cat_dto")
    {
        $sql = "SELECT cat.id, cat.name, cat.parent_cat_id, cat.level, cat.add_colour_name, cat.priority,
                    cc.lang_id, cc.image, cc.flash, cc.text, cc.latest_news, cc.status,
                    cc.create_on, cc.create_at, cc.create_by, cc.modify_on, cc.modify_at, cc.modify_by
                FROM product p
                JOIN
                (
                    SELECT soi.prod_sku, SUM(soi.qty) ttl_qty
                    FROM so_item AS soi
                    INNER JOIN so ON (so.so_no = soi.so_no AND so.status > 2
                        AND DATEDIFF(now(), so.create_on) <= 999 AND so.platform_id = '$platform')
                    GROUP BY soi.prod_sku
                ) a
                    ON (p.sku = a.prod_sku)
                JOIN category cat
                    ON p.cat_id = cat.id
                JOIN category_content cc
                    ON cat.id = cc.cat_id AND cc.lang_id = '$lang_id'
                WHERE p.website_status= 'I' AND p.website_quantity > 0
                GROUP BY p.cat_id
                ORDER BY cc.image IS NOT NULL DESC, SUM(a.ttl_qty)DESC";

        $result = $this->db->query($sql);

        if (!$result) {
            return FALSE;
        }

        $result_arr = [];

        foreach ($result->result($classname) as $obj) {
            array_push($result_arr, $obj);
        }

        return $result_arr;
    }

    public function getCatFilterGridInfo($level, $where = [], $option = [])
    {
        $this->db->select("cat.id cat_id, sc.id sub_cat_id, ssc.id sub_sub_cat_id, p.sub_cat_id, IFNULL(scex.name, sc.name) sub_cat_name, p.sub_sub_cat_id, IFNULL(sscex.name, ssc.name) sub_sub_cat_name, count(*) total", false);
        $this->db->from("product AS p");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("category AS ssc", "ssc.id = p.sub_sub_cat_id", "LEFT");
        $this->db->join("category_extend AS scex", "scex.cat_id = sc.id", "LEFT");
        $this->db->join("category_extend AS sscex", "sscex.cat_id = ssc.id", "LEFT");

        if ($option['groupby']) {
            $this->db->group_by($option['groupby']);
        }
        if ($option['orderby']) {
            $this->db->order_by($option['orderby']);
        }

        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = [];
            $array = $query->result_array();
            foreach ($array as $row) {
                switch ($level) {
                    case 1:
                        $ret[] = ["id" => $row["sub_cat_id"], "name" => $row["sub_cat_name"], "total" => $row['total']];
                        break;
                    case 2:
                        $ret[] = ["id" => $row["sub_sub_cat_id"], "name" => $row["sub_sub_cat_name"], "total" => $row['total']];
                        break;
                }
            }
            return $ret;
        }

        return FALSE;
    }

    public function getParentCatId($cat_id)
    {
        if (empty($cat_id)) {
            return false;
        }

        $this->db->select('parent.id');
        $this->db->from('category AS cat');
        $this->db->join('category AS parent', 'cat.parent_cat_id = parent.id', 'INNER');
        $this->db->where(['cat.id' => $cat_id]);

        if ($query = $this->db->get()) {
            return $query->row()->id;
        }
    }

    public function getCatInfoWithLang($where = [], $option = [], $classname = "CatInfoWithLangDto")
    {
        $this->db->from('category AS c');
        $this->db->join('category_extend AS ce', 'c.id = ce.cat_id', 'LEFT');

        return $this->commonGetList($classname, $where, $option, 'c.id AS cat_id, c.level, ce.lang_id, COALESCE(ce.name, c.name) AS name, c.description');
    }
}



