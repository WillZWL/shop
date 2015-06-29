<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Brand_dao extends Base_dao
{
    private $table_name = "brand";
    private $vo_class_name = "Brand_vo";
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

    public function get_brand_list_w_region($where = array(), $option = array(), $classname = "Brand_w_region_dto")
    {

        $this->db->from('brand AS b');
        $this->db->join('(
                    SELECT bn.id, bn.description, GROUP_CONCAT(CONCAT(sr.region_name, "--",  srr.region_name) ORDER BY sr.region_name SEPARATOR ", ") AS regions
                    FROM brand_region br
                    JOIN (brand bn, region sr, region srr)
                        ON (bn.id = br.brand_id AND sr.id = br.sales_region_id AND srr.id = br.src_region_id)
                    GROUP BY bn.id
                ) AS srn', 'b.id = srn.id', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }


        if (empty($option["orderby"])) {
            $option["orderby"] = "b.brand_name ASC";
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('b.id, b.brand_name, b.description, b.status, srn.regions, b.create_on, b.create_at, b.create_by, b.modify_on, b.modify_at, b.modify_by');

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

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }


    public function get_brand_list_w_src_reg($where = array(), $option = array(), $classname = "Brand_w_region_dto")
    {

        $this->db->from('brand AS b');
        $this->db->join('(
                    SELECT bn.id, GROUP_CONCAT(DISTINCT sr.region_name ORDER BY sr.region_name SEPARATOR ", ") AS regions
                    FROM brand_region br
                    JOIN (brand bn, region sr)
                        ON (bn.id = br.brand_id AND sr.id = br.src_region_id)
                    GROUP BY bn.id
                ) AS srn', 'b.id = srn.id', 'LEFT');

        $this->db->where($where);

        if (empty($option["orderby"])) {
            $option["orderby"] = "b.brand_name ASC";
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('b.id, b.brand_name, b.description, b.status, srn.regions, b.create_on, b.create_at, b.create_by, b.modify_on, b.modify_at, b.modify_by');

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

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                if ($option["limit"] == 1) {
                    return $rs[0];
                } else {
                    return (object)$rs;
                }
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_listed_brand_by_cat($cat_id = '')
    {
        if (empty($cat_id)) {
            return array();
        }

        $sql = 'SELECT distinct b.id, b.brand_name
            FROM brand b
            JOIN product p
                ON (p.brand_id = b.id AND p.cat_id = ? AND p.status = 2)
            WHERE b.status = 1
            ORDER BY b.brand_name';

        $result = $this->db->query($sql, $cat_id);

        if (!$result) {
            return array();
        }

        return $result->result_array();
    }

    public function get_brand_filter_grid_info($where = array(), $option = array())
    {
        $this->db->select("br.id, br.brand_name, count(*) total");
        $this->db->from("product AS p");
        $this->db->join("brand AS br", "br.id = p.brand_id", "INNER");

        if ($option['groupby']) {
            $this->db->group_by($option['groupby']);
        }
        if ($option['orderby']) {
            $this->db->order_by($option['orderby']);
        }
        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = array();
            $array = $query->result_array();
            foreach ($array as $row) {
                $ret[] = array("id" => $row["id"], "name" => $row["brand_name"], "total" => $row['total']);
            }
            return $ret;
        }

        return FALSE;
    }

}

/* End of file brand_dao.php */
/* Location: ./system/application/libraries/dao/Brand_dao.php */