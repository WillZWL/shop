<?php
namespace ESG\Panther\Dao;

class PlatformBizVarDao extends BaseDao
{
    private $tableName = "platform_biz_var";
    private $voClassName = "PlatformBizVarVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getListWithPlatformName($where = [], $option = [], $classname = "PlatformBizVarWithPlatformNameDto")
    {
        $select_str = "pbv.*, s.name AS platform_name";
        $this->db->from('platform_biz_var AS pbv');
        $this->db->join('selling_platform AS s', 'pbv.selling_platform_id = s.selling_platform_id', 'INNER');
        return $this->commonCetList($classname, $where, $option, $select_str);
    }

    public function getPricingToolPlatformList($sku, $platform_type, $classname = "PlatformBizVarWithPlatformNameDto")
    {
        $sql = "SELECT
                    pbv.*, s.name AS platform_name, c.name AS platform_country
                FROM platform_biz_var AS pbv
                JOIN selling_platform AS s
                    ON pbv.selling_platform_id = s.selling_platform_id
                JOIN country AS c
                    ON c.country_id = pbv.platform_country_id
                LEFT JOIN price AS pr
                    ON pr.platform_id = pbv.selling_platform_id AND pr.platform_id = s.selling_platform_id AND pr.sku = ?
                WHERE type = ? AND s.status = 1
                ORDER BY pr.listing_status = 'L' DESC, s.selling_platform_id ASC";

        if ($result = $this->db->query($sql, [$sku, $platform_type])) {
            $result_arr = [];

            foreach ($result->result($classname) as $obj) {
                $result_arr[] = $obj;
            }
            return $result_arr;
        }

        return FALSE;
    }

    public function getListWithCountryName($where = [], $option = [], $classname = "PlatformBizVarWithPlatformNameDto")
    {
        $select_str = "pbv.*, c.name AS platform_country";
        $this->db->from('platform_biz_var AS pbv');
        $this->db->join('selling_platform AS s', 'pbv.selling_platform_id = s.selling_platform_id', 'INNER');
        $this->db->join('country AS c', 'c.country_id = pbv.platform_country_id', 'INNER');
        return $this->commonCetList($classname, $where, $option, $select_str);
    }

    public function getUniqueDestCountryList()
    {
        $sql = "SELECT DISTINCT(pbv.dest_country) AS country_id, c.name AS country_name, pbv.platform_currency_id
                FROM platform_biz_var pbv
                INNER JOIN country c
                    ON c.country_id = pbv.dest_country
                ";
        if ($query = $this->db->query($sql)) {
            foreach ($query->result() as $row) {
                $res[] = ["country_id" => $row->country_id, "country_name" => $row->country_name, "currency_id" => $row->platform_currency_id];
            }
            return $res;
        }
        return FALSE;
    }

    public function getDestCountryWithDeliveryTypeList()
    {
        $sql = "SELECT sp.type, sp.selling_platform_id, pbv.dest_country country_id, c.name as country_name, pbv.platform_currency_id
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON sp.selling_platform_id = pbv.selling_platform_id
                JOIN country c
                    ON c.country_id = pbv.dest_country
                ";
        if ($query = $this->db->query($sql)) {
            foreach ($query->result() as $row) {
                $res[$row->type][] = ["country_id" => $row->country_id, "country_name" => $row->country_name, "currency_id" => $row->platform_currency_id];
            }
            return $res;
        }
        return FALSE;
    }

    public function getFreeDeliveryLimit($platform_id)
    {
        $sql = "SELECT free_delivery_limit
                FROM platform_biz_var
                WHERE selling_platform_id = ?";

        if ($query = $this->db->query($sql, [$platform_id])) {
            return $query->row()->free_delivery_limit;
        }
    }

}


