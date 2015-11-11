<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Landpage_listing_dao extends Base_dao
{
    private $table_name = "landpage_listing";
    private $vo_class_name = "Landpage_listing_vo";
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

    public function get_item_list($catid, $type, $classname)
    {
        if ($catid === "") {
            return FALSE;
        } else {
            //$limit = $rank - 1;

            /*$sql = "SELECT ll.rank,ll.selection, p.name,
                        concat('/images/product/', ll.selection, '.', p.image) image_file,
                        pr.price, ROUND(pr.price / 0.80, 2) rrp,
                        p.website_status, p.website_quantity, p.quantity
                    FROM landpage_listing ll
                    JOIN product p
                        ON p.sku = ll.selection AND p.website_status = 'I'
                    JOIN price pr
                        ON p.sku = pr.sku AND pr.platform_id = 'WSGB' AND pr.listing_status = 'l'
                    WHERE ll.catid = '$catid'
                    AND ll.type = '$type'
                    ORDER BY ll.rank";*/

            $sql = "SELECT ll.rank,ll.selection, p.name,
                        p.image image_file,
                        pr.price, ROUND(pr.price / 0.80, 2) rrp,
                        p.website_status, p.website_quantity, p.display_quantity, p.quantity
                    FROM landpage_listing ll
                    JOIN product p
                        ON p.sku = ll.selection AND p.website_status = 'I'
                    JOIN price pr
                        ON p.sku = pr.sku AND pr.platform_id = 'WEBGB' AND pr.listing_status = 'l' and price.pr > 0
                    WHERE ll.catid = '$catid'
                    AND ll.type = '$type'
                    ORDER BY ll.rank";

            if ($limit > 1) {
                $sql .= " LIMIT $limit";
            }

            $this->include_dto($classname);

            if ($query = $this->db->query($sql)) {
                //return $query->row("0", "array");
                $result_arr = $query->result_array();

                return $result_arr;
            } else {
                echo mysql_error();
                return FALSE;
            }
        }
    }

    public function get_manual_item_by_rank($catid, $type, $platform, $mode, $classname = "Product_list_w_name_dto")
    {
        $select = "p.name, p.sku";
        $this->db->from('landpage_listing AS ll');
        $this->db->join('product as p', 'll.selection = p.sku', 'INNER');
        $where = array("ll.type" => $type,
            "ll.catid" => $catid,
            "ll.mode" => $mode,
            "ll.platform_id" => $platform);
        $this->db->where($where);
        $this->db->order_by("ll.rank");
//      $option = array("orderby" => "ll.rank", "limit" => -1);
//      return $this->common_get_list($where, $option, $classname, $select_str);
        $rs = array();

        $this->db->select($select);
        $this->include_dto($classname);
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return false;
    }

    public function get_item_by_rank($catid, $type, $rank, $platform, $classname)
    {
        if ($catid === "") {
            return FALSE;
        } else {
            $limit = $rank - 1;

            $sql = "SELECT ll.rank,ll.selection, p.name,
                        concat('/images/product/', ll.selection, '.', p.image) image_file,
                        pr.price, ROUND(pr.price / 0.80, 2) rrp,
                        p.website_status, p.website_quantity, p.display_quantity, p.quantity,p.image
                    FROM landpage_listing ll
                    JOIN product p
                        ON p.sku = ll.selection AND p.website_status = 'I'
                    JOIN price pr
                        ON p.sku = pr.sku AND pr.platform_id = '$platform' AND pr.listing_status = 'l' and pr.price >0
                    WHERE ll.catid = '$catid'
                    AND ll.type = '$type'
                    AND ll.platform_id = '$platform'
                    ORDER BY ll.rank
                    LIMIT $limit, 1";

            $this->include_dto($classname);

            if ($query = $this->db->query($sql)) {
                return $query->row("0", "array");
            } else {
                echo mysql_error();
                return FALSE;
            }

        }
    }

    public function get_list_w_pname($catid, $mode, $type, $platform, $classname, $rtype = "object")
    {
        if ($catid === "") {
            return FALSE;
        } else {
            if ($catid !== 0) {
                $cat_filter_str = " ll.catid = $catid AND";
            }

            $sql = "SELECT ll.rank,ll.selection, p.name, p.image,p.quantity, p.website_status,
                        concat('/images/', ll.selection, '.', p.image) image_file,
                        if(pr2.price>0,pr2.price,vdc.default_platform_converted_price) price,
                        ROUND(if(pr2.price>0,pr2.price,vdc.default_platform_converted_price) / 0.80, 2) rrp,
                        p.display_quantity, p.website_quantity, p.quantity get_item_by_rank
                    FROM landpage_listing ll
                    JOIN product p
                        ON p.sku = ll.selection AND p.status='2'
                        AND p.website_quantity > '0'
                        AND p.website_status = 'I'
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L'
                    LEFT JOIN price pr2
                        ON p.sku = pr2.sku AND pr2.platform_id = ?
                    JOIN v_default_converted_price vdc
                        ON vdc.sku = p.sku AND vdc.platform_id = pr2.platform_id
                    WHERE $cat_filter_str ll.mode = ?
                    AND ll.type = ?
                    AND ll.platform_id = ?
                    AND pr2.listing_status = 'L'
                    AND (pr2.price OR pr.price) > 0
                    ORDER BY ll.rank";

            $rs = array();
            if ($query = $this->db->query($sql, array($platform, $mode, $type, $platform))) {
                if ($rtype == "object") {
                    $this->include_dto($classname);
                    foreach ($query->result($classname) as $obj) {
                        $rs[$obj->get_rank()] = $obj;
                    }
                    return $rs;
                } else {
                    foreach ($query->result_array() as $arr) {
                        $rs[] = $arr;
                    }
                    return $rs;
                }
            } else {
                echo mysql_error();
                return FALSE;
            }

        }
    }

    public function get_index_list($where = array(), $option = array(), $type, $classname = "Cat_stat_dto")
    {
        $this->db->from('category p');

        $this->db->join("(SELECT catid, count(selection) as cnt FROM landpage_listing WHERE type='" . $type . "' GROUP BY catid) AS s", "s.catid = p.id", "LEFT");

        if ($where["name"] != "") {
            $this->db->like("p.name ", $where["name"]);
        }

        if ($where["description"] != "") {
            $this->db->like("p.description ", $where["description"]);
        }

        if ($where["level"] != "") {
            $this->db->where("p.level", $where["level"]);
        }

        if ($where["status"] != "") {
            $this->db->where("p.status", $where["status"]);
        }

        //$this->db->where("p.id <>","0");

        if ($where["manual"] != "") {
            if ($where["manual"] == "Y") {
                $this->db->where("s.cnt >", 0);
            } else {
                $this->db->where("s.cnt IS NULL OR s.cnt = '0'");
            }
        }

        if (empty($option["num_rows"])) {
            $this->db->select("p.id, p.name, p.description, p.level, p.status, s.cnt");

            $this->db->order_by($option["orderby"]);

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }


            $this->include_dto($classname);

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs;
            }

            echo $this->db->_error_message();
        } else {
            $this->db->select("COUNT(*) AS total");
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function update_rank_w_prod_list($cat_id = '', $type = '',
                                            $prod_list = array(), $platform = "", $mode = 'A')
    {
        if ($cat_id === '' || empty($type) || empty($mode) || count($prod_list) <= 0 || empty($platform)) {
            return FALSE;
        }

        $result = $this->q_delete(array('catid' => $cat_id, 'type' => $type, 'mode' => $mode, 'platform_id' => $platform));

        if ($result === FALSE) {
            return FALSE;  // Deletion is fail.
        }

        $rank = 1;

        foreach ($prod_list as $prod) {
            $vo = $this->get();

            $vo->set_catid($cat_id);
            $vo->set_type($type);
            $vo->set_mode($mode);
            $vo->set_rank($rank++);
            $vo->set_platform_id($platform);
            $vo->set_selection($prod->get_sku());

            $success = $this->insert($vo);
            if (!$success) {
                $this->db->trans_rollback();
            }
        }


    }

    public function get_item_w_name_list($where = array(), $option = array(), $calssname = "Product_cost_dto")
    {
        $select_str = 'vpo.*, pc.prod_name AS content_prod_name, pc.extra_info, IF(vpi.component_order = -1, 0, 1) AS with_bundle';

        $this->db->from('landpage_listing AS ll');
        if ($option["prod_type_id"]) {
            $this->db->join('product_type AS pt', 'll.selection = pt.sku', 'INNER');
            $where["pt.type_id"] = $option["prod_type_id"];
        }
        $this->db->join('v_prod_overview_wo_shiptype AS vpo', 'll.selection = vpo.sku AND ll.platform_id = vpo.platform_id', 'INNER');
        $this->db->join('product_content AS pc', "pc.prod_sku = vpo.sku AND pc.lang_id='" . ($option["lang_id"] ? $option["lang_id"] : "en") . "'", 'LEFT');
        $this->db->join('v_prod_items AS vpi', 'vpi.prod_sku = vpo.sku AND vpi.component_order < 1', 'INNER');

        if ($option["groupby"]) {
            $this->db->group_by($option["groupby"]);
        }

        $this->include_dto($calssname);
        return $this->common_get_list($where, $option, $calssname, $select_str);
    }

    public function get_landpage_product_info($where = array(), $option = array(), $calssname = "Listing_info_dto")
    {
        $select_str = "pr.platform_id, p.sku, IFNULL(p.name, pc.prod_name) prod_name, pc.short_desc, p.image AS image_ext, pbv.platform_currency_id AS currency_id, pr.price, pr.fixed_rrp, pr.rrp_factor, IF(p.display_quantity > p.website_quantity,p.website_quantity, p.display_quantity) AS qty, IF((pr.listing_status = 'L') AND IF(p.display_quantity > p.website_quantity,p.website_quantity, p.display_quantity) > 0 , p.website_status, 'O') AS status";

        $this->db->from('landpage_listing AS ll');
        $this->db->join('product AS p', 'll.selection = p.sku', 'INNER');
        $this->db->join('price AS pr', 'll.selection = pr.sku AND p.sku = pr.sku AND ll.platform_id = pr.platform_id', 'LEFT');
        $this->db->join('platform_biz_var pbv', 'pr.platform_id = pbv.selling_platform_id', 'INNER');
        $this->db->join('product_content pc', 'pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id', 'LEFT');

        if ($option["groupby"]) {
            $this->db->group_by($option["groupby"]);
        }

        $this->include_dto($calssname);
        return $this->common_get_list($where, $option, $calssname, $select_str);
    }

}


