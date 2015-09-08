<?php
namespace AtomV2\Dao;

class CurrencyDao extends BaseDao
{
    private $tableName = "currency";
    private $voClassName = "CurrencyVo";

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

    public function getByPlatform($platform)
    {
        $this->db->from('currency c');
        $this->db->join('platform_biz_var pbv', "pbv.platform_currency_id = c.currency_id AND pbv.selling_platform_id = '$platform'", 'INNER');
        $this->db->select('c.*');
        if ($query = $this->db->get()) {
            foreach ($query->result("object", $this->getVoClassName()) as $obj) {
                $tmp = $obj;
            }

            return $tmp;
        }
        return FALSE;
    }

    public function getSign($platform = "")
    {
        $sql = "SELECT c.sign
                FROM currency c
                JOIN platform_biz_var p
                    ON p.platform_currency_id = c.currency_id
                    AND p.selling_platform_id = ?
                LIMIT 1";

        if ($query = $this->db->query($sql, $platform)) {
            return $query->row()->sign;
        }
        return FALSE;

    }

    public function getRoundUp($currency_id)
    {
        $this->db->select('round_up');
        if ($query = $this->db->get_where($this->getTableName(), array("currency_id" => $currency_id), 1)) {
            return $query->row()->round_up;
        } else {
            return FALSE;
        }
    }

    public function getActiveCurrencyList()
    {
        $sql = "SELECT c.*
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON pbv.selling_platform_id = sp.selling_platform_id
                JOIN currency c
                    ON c.currency_id = pbv.platform_currency_id
                WHERE sp.status = 1
                GROUP BY c.currency_id, c.name, c.description, c.sign, c.round_up, c.sign_pos, c.dec_place, c.dec_point, c.thousands_sep";

        $result = $this->db->query($sql);

        $result_arr = array();
        $classname = $this->getVoClassname();

        foreach ($result->result($classname) as $obj) {
            array_push($result_arr, $obj);
        }

        return $result_arr;
    }
}
