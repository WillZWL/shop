<?php
namespace AtomV2\Dao;

class WeightCatChargeDao extends BaseDao
{
    private $tableName = "weight_cat_charge";
    private $voClassName = "WeightCatChargeVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getNearestAmount($wcat_id, $weight)
    {
        $sql = "
                SELECT w.wcat_id, wcc.delivery_type, wcc.dest_country, wcc.currency_id, wcc.amount
                FROM
                (
                    SELECT wc.id AS wcat_id
                    FROM weight_category AS wc
                    WHERE wc.id != ?
                    ORDER BY (wc.weight >= ?) DESC, ABS(?-wc.weight) ASC
                    LIMIT 1
                ) w
                LEFT JOIN
                    weight_cat_charge wcc
                ON wcc.wcat_id = w.wcat_id
            ";

        $this->include_vo();

        if ($query = $this->db->query($sql, [$wcat_id, $weight, $weight])) {
            $rs = [];
            foreach ($query->result($this->getVoClassname()) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }

    public function get_platform_total_charge($platform_id, $delivery_type, $weight)
    {
        $sql = "
                SELECT wcc.amount * er.rate AS charge
                FROM weight_cat_charge AS wcc
                LEFT JOIN weight_category AS wc
                    ON (wc.id = wcc.wcat_id)
                LEFT JOIN platform_courier AS pc
                    ON (pc.platform_region_id = wcc.region_id)
                LEFT JOIN platform_biz_var AS pbv
                    On (pc.platform_id = pbv.selling_platform_id)
                LEFT JOIN exchange_rate AS er
                    ON (wcc.currency_id = er.from_currency_id AND pbv.platform_currency_id = er.to_currency_id)
                WHERE pc.platform_id = ?
                AND wcc.delivery_type = ?
                ORDER BY (wc.weight >= ?) DESC, ABS(?-wc.weight) ASC
                LIMIT 1
                ";

        $rs = [];
        if ($query = $this->db->query($sql, [$platform_id, $delivery_type, $weight, $weight])) {
            return $query->row()->charge;
        } else {
            return FALSE;
        }

        $rs = [];
        if ($query = $this->db->query($sql, [$platform_id, $shiptype, $weight, $weight])) {
            return $query->row()->charge;
        } else {
            return FALSE;
        }
    }

    public function get_country_weight_charge_by_dest_country($platform_id, $weight, $delivery_type = "STD", $dest_country, $classname = "CountryWeightChargeDto")
    {
        $sql = "
                SELECT wcc.*, wcc.amount * er.rate converted_amount
                FROM weight_cat_charge wcc
                JOIN
                (
                    SELECT wc.id
                    FROM weight_category wc
                    ORDER BY (wc.weight >= ?) DESC, ABS(? - wc.weight) ASC
                    LIMIT 1
                )mwc
                    ON (wcc.wcat_id = mwc.id AND wcc.delivery_type = ?)
                JOIN platform_biz_var pbv
                    ON pbv.dest_country = wcc.dest_country
                LEFT JOIN exchange_rate er
                    ON (wcc.currency_id = er.from_currency_id AND pbv.platform_currency_id  = er.to_currency_id)
                WHERE pbv.selling_platform_id = ? AND wcc.dest_country = ?
                ";

        if ($query = $this->db->query($sql, [$weight, $weight, $delivery_type, $platform_id, $dest_country])) {
            return $query->row()->converted_amount;
        }

        return FALSE;
    }

    public function getCountryWeightChargeByPlatform($platform_id, $weight, $delivery_type = "STD", $classname = "CountryWeightChargeDto")
    {
        $sql = "
                SELECT wcc.*, wcc.amount * er.rate converted_amount
                FROM weight_cat_charge wcc
                JOIN
                (
                    SELECT wc.id
                    FROM weight_category wc
                    ORDER BY (wc.weight >= ?) DESC, ABS(? - wc.weight) ASC
                    LIMIT 1
                )mwc
                    ON (wcc.wcat_id = mwc.id AND wcc.delivery_type = ?)
                JOIN platform_biz_var pbv
                    ON pbv.dest_country = wcc.dest_country
                LEFT JOIN exchange_rate er
                    ON (wcc.currency_id = er.from_currency_id AND pbv.platform_currency_id  = er.to_currency_id)
                WHERE pbv.selling_platform_id = ?
                ";

        if ($query = $this->db->query($sql, [$weight, $weight, $delivery_type, $platform_id])) {
            if ($query->row()) {
                return $query->row()->converted_amount;
            }
        }

        return FALSE;
    }

    public function getFullWeightCatChargeList($where = [], $option = [], $classname = "WeightCatChargeWithWeightDto")
    {
        $this->db->from('weight_category AS wc');
        $this->db->join('weight_cat_charge AS wcc', 'wc.id = wcc.wcat_id', 'INNER');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {


            $this->db->select('wcc.wcat_id, wc.weight, wcc.delivery_type, wcc.dest_country, wcc.currency_id, wcc.amount');

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

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

            $rs = [];

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

    public function insertWcc($obj)
    {
        return $this->getDao()->insert($obj);
    }

    public function updateWcc($obj)
    {
        return $this->getDao()->update($obj);
    }
}
