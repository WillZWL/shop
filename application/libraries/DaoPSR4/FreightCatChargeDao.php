<?php

namespace ESG\Panther\Dao;

class FreightCatChargeDao extends BaseDao
{
    private $tableName = "freight_cat_charge";
    private $voClassName = "FreightCatChargeVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function calculateLogisticCost($platform_id, $sku)
    {
        // default set fcc.origin_country = "HK", need to confirm supplier location
        $sql = "
        SELECT
            round(fcc.amount * ex.rate, 2) logistic_cost
        FROM supplier_prod sp
        INNER JOIN supplier s
            ON s.id                    = sp.supplier_id
            AND sp.order_default       = 1
        INNER JOIN platform_biz_var pbv
            ON pbv.selling_platform_id = ?
        INNER JOIN product p
            ON p.sku                   = sp.prod_sku
        INNER JOIN freight_cat_charge fcc
            ON fcc.origin_country      = 'HK'
            AND fcc.dest_country       = pbv.dest_country
            AND p.`freight_cat_id` = fcc.`fcat_id`
        INNER JOIN exchange_rate ex
            ON ex.from_currency_id     = fcc.currency_id
            AND ex.to_currency_id      = pbv.platform_currency_id
        WHERE p.sku = ?
        LIMIT 1;
        ";

        if ($query = $this->db->query($sql, [$platform_id, $sku])) {
            return floatval($query->row('logistic_cost'));
        } else {
            error_log("{$sku} {$platform_id} logistic cost is 0");
            return 0;
        }
    }

    public function getNearestAmount($fcat_id, $weight)
    {
        $sql = "
                SELECT f.fcat_id, fcc.origin_country, fcc.dest_country, fcc.currency_id, fcc.amount
                FROM
                (
                    SELECT fc.id AS fcat_id
                    FROM freight_category AS fc
                    WHERE fc.id != ?
                    ORDER BY (fc.weight >= ?) DESC, ABS(?-fc.weight) ASC
                    LIMIT 1
                ) f
                LEFT JOIN
                    freight_cat_charge fcc
                ON fcc.fcat_id = f.fcat_id
            ";

        if ($query = $this->db->query($sql, [$fcat_id, $weight, $weight])) {
            $rs = [];
            foreach ($query->result($this->getVoClassname()) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }
}
