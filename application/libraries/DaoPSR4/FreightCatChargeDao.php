<?php
namespace AtomV2\Dao;

class FreightCatChargeDao extends BaseDao
{
    private $tableName = "freight_cat_charge";
    private $voClassName = "FreightCatChargeVo";

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

    public function get_nearest_amount($fcat_id, $weight)
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

        $this->include_vo();

        if ($query = $this->db->query($sql, array($fcat_id, $weight, $weight))) {
            $rs = array();
            foreach ($query->result($this->get_vo_classname()) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }

    public function calcLogisticCost($platform_id, $sku)
    {
        $sql = <<<SQL
        SELECT
            sp.prod_sku,
            s.origin_country,
            pbv.dest_country,
            fcc.currency_id,
            ex.rate,
            fcc.amount,
            round(fcc.amount*ex.rate,2) converted_amount
        FROM supplier_prod sp
        JOIN supplier s
            ON s.id                    = sp.supplier_id
            AND sp.order_default       = 1          # this is bad table design
        JOIN platform_biz_var pbv
            ON pbv.selling_platform_id = ?
        JOIN product p
            ON p.sku                   = sp.prod_sku
        JOIN freight_cat_charge fcc
            ON fcc.origin_country      = left(s.fc_id,2)
            AND fcc.dest_country       = pbv.dest_country
        JOIN freight_category fc
            ON fc.id                   = fcc.fcat_id
            AND fc.id                  = p.freight_cat_id
        LEFT JOIN exchange_rate ex
            ON ex.from_currency_id     = fcc.currency_id
            AND ex.to_currency_id      = pbv.platform_currency_id
        WHERE p.sku = ?
        LIMIT 1
SQL;

        if ($query = $this->db->query($sql, array($platform_id, $sku))) {
            $rs = array();

            if ($query->num_rows() != 1) {
                return FALSE;
            }
            return $query->row_array();
        } else {
            return FALSE;
        }
    }
}
