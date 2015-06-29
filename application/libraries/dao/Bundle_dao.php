<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Bundle_dao extends Base_dao
{
    private $table_name="bundle";
    private $vo_class_name="Bundle_vo";
    private $seq_name="product";
    private $seq_mapping_field="sku";
    private $website_status_priority = array('I'=>0, 'IS'=>10, 'P'=>20, 'O'=>99);
    //private $website_status_priority_flip;

    public function __construct()
    {
        parent::__construct();
        //$this->$website_status_priority_flip = array_flip($this->website_status_priority);
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

    public function get_website_status_priority($data)
    {
        return $this->website_status_priority[$data];
    }

    public function get_list_w_name($where=array(), $option=array(), $classname="")
    {

        $this->db->from('v_bundle AS vb');
        $this->db->join('product AS p', 'p.sku = vb.bundle_sku', 'INNER');
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');

        if ($where["prod_grp_cd"] != "")
        {
            $this->db->like('p.prod_grp_cd', $where["prod_grp_cd"]);
        }

        if ($where["sku"] != "")
        {
            $this->db->like('p.sku', $where["sku"]);
        }

        if ($where["name"] != "")
        {
            $this->db->like('vb.bundle_name', $where["name"]);
        }

        if ($where["colour"] != "")
        {
            $this->db->like('cl.name', $where["colour"]);
        }

        if ($where["category"] != "")
        {
            $this->db->like('c.name', $where["category"]);
        }

        if ($where["sub_cat"] != "")
        {
            $this->db->like('sc.name', $where["sub_cat"]);
        }

        if ($where["sub_sub_cat"] != "")
        {
            $this->db->like('ssc.name', $where["sub_sub_cat"]);
        }

        if ($where["brand"] != "")
        {
            $this->db->like('b.brand_name', $where["brand"]);
        }

        if ($where["components"] != "")
        {
            $this->db->like('vb.components', $where["components"]);
        }

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            $this->db->select('p.sku, vb.bundle_name AS name, c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.status, p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by');

            $this->db->order_by($option["orderby"]);

            if (empty($option["limit"]))
            {
                $option["limit"] = $this->rows_limit;
            }

            elseif ($option["limit"] == -1)
            {
                $option["limit"] = "";
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

            if ($query = $this->db->get())
            {
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
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

    public function get_bundle_list($where=array(), $option=array(), $classname="")
    {

        $this->db->from('v_bundle_list');

        if ($where["prod_sku"] != "")
        {
            $this->db->where('prod_sku', $where["prod_sku"]);
        }

        if ($where["component_sku"] != "")
        {
            $this->db->where('component_sku', $where["component_sku"]);
        }

        if ($where["component_order"] != "")
        {
            $this->db->where('component_order', $where["component_order"]);
        }

        if ($where["components"] != "")
        {
            $this->db->where('components', $where["components"]);
        }

        if ($where["bundle_name"] != "")
        {
            $this->db->like('bundle_name', $where["bundle_name"]);
        }

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            if (isset($option["orderby"]))
            {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"]))
            {
                $option["limit"] = $this->rows_limit;
            }

            elseif ($option["limit"] == -1)
            {
                $option["limit"] = "";
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

            if ($query = $this->db->get())
            {
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
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

    public function get_avail_prod_bundle_list($sku, $platform_id = 'WSGB', $lang_id = 'en')
    {
        if (empty($sku))
        {
            return array();
        }
        /*
        $sql = "SELECT bs.prod_sku, bs.component_sku, p.name, vpo.prod_name component_name,
                    vpo.image component_image_file_ext, vpo.website_quantity, vpo.website_status, vpo.listing_status component_listing_status
                FROM
                (
                    SELECT *
                    FROM bundle b
                    WHERE b.component_sku = ?
                )a
                JOIN bundle bs
                    ON a.prod_sku = bs.prod_sku
                JOIN v_prod_overview vpo
                    ON vpo.sku = bs.component_sku
                JOIN product p
                    ON p.sku = bs.prod_sku
                WHERE vpo.platform_id = ? AND bs.prod_sku NOT IN
                (
                    SELECT b.prod_sku
                    FROM
                    (
                        SELECT bs.prod_sku, vpo.listing_status
                        FROM
                        (
                            SELECT *
                            FROM bundle b
                            WHERE b.component_sku = ?
                        )a
                        JOIN bundle bs
                            ON a.prod_sku = bs.prod_sku
                        JOIN v_prod_overview vpo
                            ON vpo.sku = bs.component_sku
                        JOIN product p
                            ON p.sku = bs.prod_sku
                        WHERE vpo.platform_id = ?
                    )b
                    WHERE b.listing_status = 'N'
                )
                ";
        */
        $sql = "
        SELECT b.prod_sku, b.component_sku, p.name component_name, p.image component_image_file_ext, p.website_quantity, p.website_status, pr.listing_status component_listing_status, pb.name bundle_name
                FROM bundle b
                JOIN (
                    SELECT prod_sku
                    FROM bundle
                    WHERE component_sku = ?
                ) bd
                ON b.prod_sku = bd.prod_sku
                JOIN product p
                    ON b.component_sku = p.sku
                LEFT JOIN price pr
                    ON pr.sku = p.sku
                JOIN product pb
                    ON b.prod_sku = pb.sku
                WHERE pr.platform_id = ?
        ";

        $result = $this->db->query($sql, array($sku, $platform_id, $sku, $platform_id));
        $bundle_list = array();

        if (!$result)
        {
            return $bundle_list;
        }

        $result_arr = $result->result_array();

        $this->include_dto('Prod_bundle_dto');

        $temp_prod_bundle_dto = new prod_bundle_dto();

        $result_rows = count($result_arr);
        $bundle_count = 0;
        $comp_count = 0;
        $min_website_qty = 0;
        $website_status = $this->get_website_status_priority('I');

        for ($i = 0; $i < $result_rows; $i++)
        {
            if ($temp_prod_bundle_dto->get_prod_sku() != $result_arr[$i]['prod_sku'])
            {
                $temp_prod_bundle_dto = new prod_bundle_dto();
                $temp_prod_bundle_dto->set_prod_sku($result_arr[$i]['prod_sku']);
                $temp_prod_bundle_dto->set_main_prod_sku($sku);
                $temp_prod_bundle_dto->set_bundle_name($result_arr[$i]['bundle_name']);
                //$temp_prod_bundle_dto->set_name($result_arr[$i]['name']);

                if ($temp_prod_bundle_dto->get_prod_sku() != '')
                {
                    $bundle_list[$bundle_count++] = $temp_prod_bundle_dto;
                }

                $min_website_qty = $result_arr[$i]['website_quantity'];
                $website_status = $this->get_website_status_priority($result_arr[$i]['website_status']);

                $temp_prod_bundle_dto->set_website_quantity($min_website_qty);
                $temp_prod_bundle_dto->set_website_status($result_arr[$i]['website_status']);

                $temparr = array();
            }
            else
            {
                $temparr = $temp_prod_bundle_dto->get_component_sku_list();

                if ($min_website_qty > $result_arr[$i]['website_quantity'])
                {
                    $min_website_qty = $result_arr[$i]['website_quantity'];
                    $temp_prod_bundle_dto->set_website_quantity($min_website_qty);
                }

                if ($website_status < $this->get_website_status_priority($result_arr[$i]['website_status']))
                {
                    $website_status = $this->get_website_status_priority($result_arr[$i]['website_status']);
                    $temp_prod_bundle_dto->set_website_status($result_arr[$i]['website_status']);
                }
            }

            $temparr[$comp_count]['component_sku'] = $result_arr[$i]['component_sku'];
            $temparr[$comp_count]['component_name'] = $result_arr[$i]['component_name'];
            $temparr[$comp_count]['component_image_file_ext'] = $result_arr[$i]['component_image_file_ext'];
            $temparr[$comp_count]['component_listing_status'] = $result_arr[$i]['component_listing_status'];
            $temp_prod_bundle_dto->set_component_sku_list($temparr);

            $comp_count++;
        }

        return $bundle_list;
    }

    public function check_bundle_discount($sku_list = array())
    {
        $sql  = "
                SELECT b.component_sku AS sku, c.bundle_discount
                FROM bundle AS b
                INNER JOIN
                (
                    SELECT b.prod_sku
                    FROM bundle AS b
                    LEFT JOIN
                    (
                        SELECT prod_sku, component_sku
                        FROM bundle
                        WHERE component_sku IN ('".implode("', '", $sku_list)."')
                    ) AS bc
                    ON b.prod_sku = bc.prod_sku AND b.component_sku = bc.component_sku
                    GROUP BY b.prod_sku
                    HAVING COUNT(b.component_sku) = COUNT(bc.component_sku)
                ) AS bn
                ON b.prod_sku = bn.prod_sku
                INNER JOIN product AS p
                    ON b.component_sku = p.sku
                INNER JOIN category AS c
                    ON c.id = p.sub_cat_id
                GROUP BY b.component_sku
                ";

        if ($query = $this->db->query($sql))
        {
            $data = array();
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $data[$row->sku] = $row->bundle_discount;
                }
            }
            return $data;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_bundle_component_sku($bundle_sku)
    {
        $sql  = "
                SELECT components
                FROM v_bundle
                WHERE bundle_sku = ?
                ";

        if ($query = $this->db->query($sql, array($bundle_sku)))
        {
            $data = array();
            if ($query->num_rows() > 0)
            {
                foreach ($query->result() as $row)
                {
                    $data = explode(",",$row->components);
                }
            }
            return $data;
        }
        else
        {
            return FALSE;
        }
    }
}

/* End of file bundle_dao.php */
/* Location: ./system/application/libraries/dao/Bundle_dao.php */