<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Landpage_video_listing_dao extends Base_dao
{
    private $table_name = "landpage_video_listing";
    private $vo_class_name = "Landpage_video_listing_vo";
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
    /*
        public function get_item_list($catid,$type,$classname)
        {
            if($catid === "")
            {
                return FALSE;
            }
            else
            {
                //$limit = $rank - 1;
                */
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
    /*
                        $sql = "SELECT ll.rank,ll.selection, p.name,
                            p.image image_file,
                            pr.price, ROUND(pr.price / 0.80, 2) rrp,
                            p.website_status, p.website_quantity, p.quantity
                        FROM landpage_listing ll
                        JOIN product p
                            ON p.sku = ll.selection AND p.website_status = 'I'
                        JOIN price pr
                            ON p.sku = pr.sku AND pr.platform_id = 'WSGB' AND pr.listing_status = 'l' and price.pr > 0
                        WHERE ll.catid = '$catid'
                        AND ll.type = '$type'
                        ORDER BY ll.rank";

                if ($limit > 1)
                {
                    $sql .= " LIMIT $limit";
                }

                $this->include_dto($classname);

                if ($query = $this->db->query($sql))
                {
                    //return $query->row("0", "array");
                    $result_arr = $query->result_array();

                    return $result_arr;
                }
                else
                {
                    echo mysql_error();
                    return FALSE;
                }
            }
        }
    */
    public function get_item_by_rank($catid, $l_type, $v_type, $rank, $platform, $src, $classname)
    {
        if ($catid === "") {
            return FALSE;
        } else {
            $limit = $rank - 1;

            $sql = "SELECT ll.rank, ll.ref_id, ll.video_type, ll.sku, p.name,
                        concat('/images/product/', ll.sku, '.', p.image) image_file,
                        pr.price, ROUND(pr.price / 0.80, 2) rrp,
                        p.website_status, p.website_quantity, p.quantity,p.image
                    FROM landpage_video_listing ll
                    JOIN product p
                        ON p.sku = ll.sku AND p.status = '2' AND p.website_quantity > '0' AND p.website_status = 'I'
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L'
                    LEFT JOIN price pr2
                        ON p.sku = pr2.sku AND pr2.platform_id = '$platform' AND pr2.listing_Status = 'L'
                    WHERE ll.catid = '$catid'
                    AND ll.listing_type = '$l_type'
                    AND ll.platform_id = '$platform'
                    AND (pr2.price OR pr.price) > 0
                    AND ll.video_type = '$v_type'
                    AND ll.src = '$src'
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

    public function get_list_w_pname($catid, $mode, $l_type, $v_type, $platform, $src, $rtype = "object", $classname)
    {
        if ($catid === "") {
            return FALSE;
        } else {
            if ($catid !== 0) {
                $cat_filter_str = " ll.catid = $catid AND";
            }

            if ($v_type != "") {
                $v_type_filter_str = " AND ll.video_type = '$v_type' ";
            }

            $sql = "SELECT ll.rank, ll.ref_id, ll.video_type, ll.src, ll.sku, p.name,
                        concat('/images/product/', ll.sku, '.', p.image) image_file,
                        if(pr2.price>0,pr2.price,vdc.default_platform_converted_price) price,
                        ROUND(if(pr2.price>0,pr2.price,vdc.default_platform_converted_price) / 0.80, 2) rrp,
                        p.website_status, p.website_quantity, p.quantity,p.image
                    FROM landpage_video_listing ll
                    JOIN product p
                        ON p.sku = ll.sku AND p.status = '2' AND p.website_quantity > '0' AND p.website_status = 'I'
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L'
                    LEFT JOIN price pr2
                        ON p.sku = pr2.sku AND pr2.platform_id = ?
                    JOIN v_default_converted_price vdc
                        ON vdc.sku = p.sku AND vdc.platform_id = pr2.platform_id
                    WHERE $cat_filter_str ll.mode = ?
                    AND ll.listing_type = ?
                    AND ll.platform_id = ?
                    $v_type_filter_str
                    AND (pr2.price OR pr.price) > 0
                    AND ll.src = ?
                    AND pr2.listing_Status = 'L'
                    ORDER BY ll.rank";

            $rs = array();
            if ($query = $this->db->query($sql, array($platform, $mode, $l_type, $platform, $src))) {
                if ($rtype == "object") {
                    $this->include_dto($classname);
                    foreach ($query->result($classname) as $obj) {
                        $rs[] = $obj;
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

        $this->db->join("(SELECT catid, count(ref_id) as cnt FROM landpage_video_listing WHERE listing_type='" . $type . "' AND mode='M' GROUP BY catid) AS s", "s.catid = p.id", "LEFT");

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

    public function update_rank_w_video_list($cat_id = '', $listing_type = '',
                                             $video_list = array(), $platform = '', $video_type = '', $mode = 'A')
    {
        if ($cat_id === '' || empty($listing_type) || empty($mode) || count($video_list) <= 0 || empty($platform) || empty($video_type)) {
            return FALSE;
        }
        $result = $this->q_delete(array('catid' => $cat_id, 'listing_type' => $listing_type, 'mode' => $mode, 'platform_id' => $platform, 'video_type' => $video_type));

        if ($result === FALSE) {
            return FALSE;  // Deletion is fail.
        }

        $rank = 1;

        foreach ($video_list as $video) {
            $vo = $this->get();

            $vo->set_catid($cat_id);
            $vo->set_sku($video->get_sku());
            $vo->set_lang_id($video->get_lang_id());
            $vo->set_listing_type($listing_type);
            $vo->set_video_type($video_type);
            $vo->set_mode($mode);
            $vo->set_rank($rank++);
            $vo->set_platform_id($platform);
            $vo->set_ref_id($video->get_ref_id());
            $vo->set_src($video->get_src());

            $success = $this->insert($vo);
            if (!$success) {
                $this->db->trans_rollback();
            }
        }
    }
}

?>