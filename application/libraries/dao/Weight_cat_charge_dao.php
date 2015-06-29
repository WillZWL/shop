<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Weight_cat_charge_dao extends Base_dao {
    private $table_name="weight_cat_charge";
    private $vo_class_name="Weight_cat_charge_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct(){
        parent::__construct();
    }

    public function get_vo_classname(){
        return $this->vo_class_name;
    }

    public function get_table_name(){
        return $this->table_name;
    }

    public function get_seq_name(){
        return $this->seq_name;
    }

    public function get_seq_mapping_field(){
        return $this->seq_mapping_field;
    }

    public function get_nearest_amount($wcat_id, $weight)
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

        if ($query = $this->db->query($sql, array($wcat_id, $weight, $weight)))
        {
            $rs = array();
            foreach ($query->result($this->get_vo_classname()) as $obj)
            {
                $rs[] = $obj;
            }
            return (object) $rs;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_platform_total_charge($platform_id, $delivery_type, $weight)
    {
        $sql  = "
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

        $rs = array();
        if ($query = $this->db->query($sql, array($platform_id, $delivery_type, $weight, $weight)))
        {
            return $query->row()->charge;
        }
        else
        {
            return FALSE;
        }

        $rs = array();
        if ($query = $this->db->query($sql, array($platform_id, $shiptype, $weight, $weight)))
        {
            return $query->row()->charge;
        }
        else
        {
            return FALSE;
        }
    }
/*
    // old version with region
    public function get_country_weight_charge($platform_id, $weight, $delivery_type="", $country_id="", $classname="Country_weight_charge_dto")
    {

        $get_amount = $delivery_type != "" && $country_id != "";
        $blind = array($weight, $weight, $platform_id);

        $this->include_dto($classname);

        $sql  = "
                SELECT wcc.*, wcc.amount * er.rate AS amount, c.id AS country_id
                FROM delivery AS d
                LEFT JOIN (weight_cat_charge AS wcc)
                    ON (d.delivery_type_id = wcc.delivery_type)
                INNER JOIN
                    (
                    SELECT wc.id
                    FROM weight_category AS wc
                    ORDER BY (wc.weight >= ?) DESC, ABS(?-wc.weight) ASC
                    LIMIT 1
                    ) AS mwc
                    ON (wcc.wcat_id = mwc.id AND wcc.type = 'CH')
                LEFT JOIN (region AS r, region_country AS rc, country AS c)
                    ON (wcc.region_id = r.id AND r.id = rc.region_id AND rc.country_id = c.id AND c.allow_sell = 1)
                LEFT JOIN exchange_rate AS er
                    ON (
                        wcc.currency_id = er.from_currency_id AND er.to_currency_id = (
                                                                                            SELECT platform_currency_id
                                                                                            FROM platform_biz_var
                                                                                            WHERE selling_platform_id = ?
                                                                                            )
                        )
                ";

        if ($get_amount)
        {
            $sql .= "
                    WHERE wcc.delivery_type = ?
                    AND c.id = ?
                    ";
            $blind[] = $delivery_type;
            $blind[] = $country_id;
        }

        $sql .= "
                GROUP BY wcc.delivery_type, wcc.region_id, country_id
                ";

        $rs = array();
        if ($query = $this->db->query($sql, $blind))
        {
            if ($get_amount)
            {
                return $query->row()->amount;
            }
            else
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
            return FALSE;
        }
    }

    // old version with region
    public function get_country_weight_charge_by_platform($platform_id, $weight, $delivery_type="", $classname="Country_weight_charge_dto")
    {
        $get_amount = $delivery_type != "";
        $blind = array($weight, $weight, $platform_id);

        $this->include_dto($classname);

        $sql  = "
                SELECT wcc.*, wcc.amount * er.rate AS amount
                FROM delivery AS d
                LEFT JOIN (weight_cat_charge AS wcc)
                    ON (d.delivery_type_id = wcc.delivery_type)
                INNER JOIN
                    (
                    SELECT wc.id
                    FROM weight_category AS wc
                    ORDER BY (wc.weight >= ?) DESC, ABS(?-wc.weight) ASC
                    LIMIT 1
                    ) AS mwc
                    ON (wcc.wcat_id = mwc.id AND wcc.type = 'CH')
                LEFT JOIN (region AS r, region_country AS rc)
                    ON (wcc.region_id = r.id AND r.id = rc.region_id)
                INNER JOIN platform_biz_var AS pbv
                    ON (pbv.platform_country_id = rc.country_id)
                LEFT JOIN exchange_rate AS er
                    ON (wcc.currency_id = er.from_currency_id AND pbv.platform_currency_id = er.to_currency_id)
                WHERE pbv.selling_platform_id = ?
                ";

        if ($get_amount)
        {
            $sql .= "
                    AND wcc.delivery_type = ?
                    ";
            $blind[] = $delivery_type;
        }

        $sql .= "
                GROUP BY wcc.delivery_type, wcc.region_id
                ";

        $rs = array();
        if ($query = $this->db->query($sql, $blind))
        {
            if ($get_amount)
            {
                return $query->row()->amount;
            }
            else
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
            return FALSE;
        }
    }
*/
    public function get_country_weight_charge_by_dest_country($platform_id, $weight, $delivery_type="STD", $dest_country, $classname="Country_weight_charge_dto")
    {
        $this->include_dto($classname);
        $sql  = "
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

        if ($query = $this->db->query($sql, array($weight, $weight, $delivery_type, $platform_id, $dest_country)))
        {
            return $query->row()->converted_amount;
        }

        return FALSE;
    }

    public function get_country_weight_charge_by_platform($platform_id, $weight, $delivery_type="STD", $classname="Country_weight_charge_dto")
    {
        $this->include_dto($classname);
        $sql  = "
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

        if ($query = $this->db->query($sql, array($weight, $weight, $delivery_type, $platform_id)))
        {
            return $query->row()->converted_amount;
        }

        return FALSE;
    }

    public function get_full_weight_cat_charge_list($where = array(), $option = array(), $classname="Weight_cat_charge_w_weight_dto")
    {
        $this->db->from('weight_category AS wc');
        $this->db->join('weight_cat_charge AS wcc', 'wc.id = wcc.wcat_id', 'INNER');

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            $this->db->select('wcc.wcat_id, wc.weight, wcc.delivery_type, wcc.dest_country, wcc.currency_id, wcc.amount');

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

    public function insert_wcc($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update_wcc($obj)
    {
        return $this->get_dao()->update($obj);
    }
}

/* End of file weight_cat_charge_dao.php */
/* Location: ./system/application/libraries/dao/Weight_cat_charge_dao.php */