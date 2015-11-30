<?php
namespace ESG\Panther\Dao;

class CompetitorMapDao extends BaseDao
{
    private $tableName = "competitor_map";
    private $voClassname = "CompetitorMapVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

    public function getActiveComp($ext_sku = "", $platform_country_id = "", $where = [], $option = [], $classname = "CompetitorMappingDto")
    {
        $this->db->from("competitor_map AS cmap");
        $this->db->join("competitor AS c", "c.id = cmap.competitor_id", "INNER");
        $this->db->where(["c.status" => 1, "cmap.status" => 1]);

        if ($ext_sku) {
            $this->db->where(["ext_sku" => $ext_sku]);
        } else {
            return FALSE;
        }

        if ($platform_country_id) {
            $this->db->where(["c.country_id" => $platform_country_id]);
        }

        return $this->commonGetList($classname, $where, $option, "
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

}


