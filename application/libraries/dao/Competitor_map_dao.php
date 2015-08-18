<?php

include_once 'Base_dao.php';

class Competitor_map_dao extends Base_dao
{
    private $table_name = "competitor_map";
    private $vo_classname = "Competitor_map_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
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

    public function get_active_mapped_list($country_id = "", $master_sku = "", $classname = "Competitor_map_vo")
    {
        #gets active competitor that are mapped to a master_sku
        if ($country_id && $master_sku) {
            $sql = "
                    SELECT
                        cmap.id,
                        cmap.ext_sku,
                        cmap.competitor_id,
                        cmap.status,
                        cmap.match,
                        cmap.last_price,
                        cmap.now_price,
                        cmap.product_url,
                        cmap.note_1,
                        cmap.note_2,
                        cmap.comp_stock_status,
                        cmap.comp_ship_charge,
                        cmap.reprice_min_margin,
                        cmap.reprice_value,
                        cmap.create_on,
                        cmap.create_at,
                        cmap.create_by,
                        cmap.modify_on,
                        cmap.modify_at,
                        cmap.modify_by
                    FROM competitor_map cmap
                    INNER JOIN competitor comp
                        ON cmap.competitor_id=comp.id
                        AND comp.status = 1
                        AND comp.country_id = ?
                    WHERE
                        cmap.ext_sku = ?

                    ORDER BY cmap.status DESC
                    ";


            $rs = array();
            if ($query = $this->db->query($sql, array($country_id, $master_sku))) {
                $this->include_vo($this->get_vo_classname());
                foreach ($query->result($classname) as $row) {
                    $rs[] = array(
                        "master_sku" => $row->get_ext_sku(),
                        "competitor_id" => $row->get_competitor_id(),
                        "status" => $row->get_status(),
                        "match" => $row->get_match(),
                        "last_price" => $row->get_last_price(),
                        "now_price" => $row->get_now_price(),
                        "product_url" => $row->get_product_url(),
                        "note_1" => $row->get_note_1(),
                        "note_2" => $row->get_note_2(),
                        "comp_stock_status" => $row->get_comp_stock_status(),
                        "comp_ship_charge" => $row->get_comp_ship_charge(),
                        "reprice_min_margin" => $row->get_reprice_min_margin(),
                        "reprice_value" => $row->get_reprice_value(),
                        "create_on" => $row->get_create_on(),
                        "create_at" => $row->get_create_at(),
                        "create_by" => $row->get_create_by(),
                        "modify_on" => $row->get_modify_on(),
                        "modify_at" => $row->get_modify_at(),
                        "modify_by" => $row->get_modify_by()
                    );
                }

                return $rs;
            }
        }

        return FALSE;

    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_active_comp($ext_sku = "", $platform_country_id = "", $where = array(), $option = array())
    {
        $classname = "Competitor_mapping_dto";

        $this->db->from("competitor_map AS cmap");
        $this->db->join("competitor AS c", "c.id = cmap.competitor_id", "INNER");
        $this->db->where(array("c.status" => 1, "cmap.status" => 1));

        if ($ext_sku) {
            $this->db->where(array("ext_sku" => $ext_sku));
        } else {
            return FALSE;
        }

        if ($platform_country_id) {
            $this->db->where(array("c.country_id" => $platform_country_id));
        }

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
                    c.competitor_name,
                    c.country_id,
                    cmap.competitor_id,
                    ext_sku,
                    c.status AS comp_status,
                    cmap.status AS cmap_status,
                    `match`,
                    last_price,
                    now_price,
                    product_url,
                    note_1,
                    note_2,
                    comp_stock_status,
                    comp_ship_charge,
                    reprice_min_margin,
                    reprice_value,
                    sourcefile_timestamp,
                    cmap.create_on AS cmap_create_on,
                    cmap.create_at AS cmap_create_at,
                    cmap.create_by AS cmap_create_by,
                    cmap.modify_on AS cmap_modify_on,
                    cmap.modify_at AS cmap_modify_at,
                    cmap.modify_by AS cmap_modify_by
                    ");
    }

    public function get_reprice_compmap_list_by_platform($platform_id = "", $reprice = 'N', $where = array(), $option = array())
    {
        if ($platform_id) {
            $platform_country_id = substr($platform_id, -2);
        }
        $classname = "Competitor_mapping_dto";

        $this->db->from("competitor_map AS cmap");
        $this->db->join("competitor AS c", "c.id = cmap.competitor_id AND c.status = 1", "INNER");
        $this->db->join("sku_mapping skumap", "skumap.ext_sku = cmap.ext_sku AND skumap.ext_sys = 'WMS' and skumap.`status` = 1", "INNER");
        $this->db->join("price pr", "pr.sku = skumap.sku AND pr.platform_id = '$platform_id' AND pr.listing_status='L' AND pr.auto_price = 'C'", "INNER");
        $this->db->where(array("c.status" => 1, "c.country_id" => $platform_country_id, "cmap.status" => 1, "cmap.match" => 1));
        $this->db->order_by("skumap.sku ASC");
        $option["limit"] = -1;
        $this->include_dto($classname);
        $list = $this->common_get_list($where, $option, $classname, "
                    c.competitor_name,
                    c.country_id,
                    cmap.competitor_id,
                    cmap.ext_sku,
                    pr.sku,
                    c.status AS comp_status,
                    cmap.status AS cmap_status,
                    `match`,
                    last_price,
                    now_price,
                    product_url,
                    note_1,
                    note_2,
                    comp_stock_status,
                    comp_ship_charge,
                    reprice_min_margin,
                    reprice_value,
                    sourcefile_timestamp,
                    pr.price AS platform_selling_price
                    ");

        $rs = array();
        if ($list) {
            foreach ($list as $obj) {
                $rs[$obj->get_sku()][] = $obj;
            }
        }
        return $rs;
    }

    public function get_competitor_rpt_data($where)
    {
        $this->db->from("competitor_map cmap");
        $this->db->join("competitor c", "c.id = cmap.competitor_id AND c.status = 1", "INNER");
        $this->db->join("sku_mapping skumap", "skumap.ext_sku = cmap.ext_sku AND skumap.ext_sys = 'WMS' and skumap.`status` = 1", "INNER");
        $this->db->join("product p", "p.sku = skumap.sku", "INNER");
        $this->db->join("price pr", "pr.sku = skumap.sku", "INNER");
        $this->db->group_by("p.sku");
        $this->db->select('
                            skumap.ext_sku,
                            skumap.sku,
                            p.name,
                            pr.price,
                            pr.listing_status,
                            pr.auto_price,
                            pr.platform_id,
                            cmap.sourcefile_timestamp,
                            GROUP_CONCAT(cmap.note_1) AS note_1,
                            GROUP_CONCAT(cmap.note_2) AS note_2,
                            GROUP_CONCAT(c.competitor_name) AS competitor_name,
                            GROUP_CONCAT(CAST(cmap.now_price AS CHAR)) AS competitor_price
                        ');

        $where ["cmap.status"] = 1;
        $this->db->where($where);

        $rs = array();
        if ($query = $this->db->get()) {
            foreach ($query->result() as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        } else {
            return FALSE;
        }
    }


    public function get_list_by_url($prod_url = "", $country_id = "", $classname = "Competitor_map_vo")
    {
        if ($prod_url && $country_id) {
            $sql = "
                    SELECT compmap.* from competitor_map compmap
                    INNER JOIN competitor comp ON comp.country_id= ? AND comp.id=compmap.competitor_id AND comp.status=1
                    where compmap.product_url= ? AND compmap.status=1
                    ORDER BY compmap.modify_on DESC
                    ";

            if ($query = $this->db->query($sql, array($country_id, $prod_url))) {
                $this->include_vo($this->get_vo_classname());
                $rs = $query->result($classname);
                return $rs;
            }
        }
        return FALSE;
    }

    public function update_last_price($country_id)
    {
        if ($country_id) {
            $sql = "
                    UPDATE competitor_map cpmap
                    INNER JOIN competitor c ON cpmap.competitor_id = c.id AND c.status = 1 AND c.country_id = ?
                    SET cpmap.last_price = cpmap.now_price
                    WHERE cpmap.status = 1
                    ";

            if ($query = $this->db->query($sql, array($country_id))) {
                $this->db->query("commit;");
                return TRUE;
            } else {
                if ($this->db->trans_autocommit) {
                    $this->db->trans_rollback();
                    $this->db->trans_commit();
                }
                return FALSE;
            }
        } else {
            return false;
        }
    }

    public function get_product_identifier_list_grouped_by_country($where = array())
    {
        $this->db->from('product_identifier pi');

        if ($where) {
            $this->db->where($where);
        }

        $rs = array();

        if ($query = $this->db->get()) {
            $this->include_vo($this->get_vo_classname());
            foreach ($query->result($this->get_vo_classname()) as $obj) {
                $rs[$obj->get_country_id()] = array("ean" => $obj->get_ean(), "mpn" => $obj->get_mpn(), "upc" => $obj->get_upc(), "status" => $obj->get_status());
            }
            return $rs;
        }

        return FALSE;
    }
}
