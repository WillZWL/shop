<?php
namespace ESG\Panther\Dao;

class CountryDao extends BaseDao
{
    private $tableName = "country";
    private $voClassname = "CountryVo";

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
        return $this->voClassname;
    }

    public function getListLang($lang, $where = array(), $option = array(), $classname = "CountryLangNameDto")
    {
        // Hardcoded to language_id = 'en' only
        $sql = "SELECT c.id, c.fc_id, IFNULL(ce.name, c.name) as name, l.name lang_name
                FROM country c
                JOIN country_ext ce
                    ON ce.cid = c.id AND ce.lang_id = ?
                JOIN language l
                    ON l.id = c.language_id
                WHERE c.allow_sell = '1'
                    AND c.status = '1'
                    AND c.language_id = 'en'
                ORDER BY ce.name";

        if (($query = $this->db->query($sql, $lang)) != FALSE) {

            $ret = array();
            foreach ($query->result($classname) as $obj) {
                $ret[] = $obj;
            }
            return $ret;
        }
        return FALSE;
    }

    public function getSellCurrencyList()
    {
        $sql = "SELECT distinct currency_id
                FROM country AS c1
                WHERE allow_sell = 1
                ORDER BY currency_id";

        $rs = array();

        if ($query = $this->db->query($sql)) {
            foreach ($query->result($this->getVoClassname()) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function getSellCountryList($detail = 1)
    {
        $sql = "
                SELECT *
                FROM country AS c1
                WHERE allow_sell = 1
                ORDER BY id = 'US' DESC, name
                ";

        if ($detail) {

            $rs = array();

            if ($query = $this->db->query($sql)) {
                foreach ($query->result($this->getVoClassname()) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        } else {
            $sql = "
                    SELECT COUNT(*) AS total
                    FROM (" . $sql . ") AS c";
            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function getFullCountryList($detail = 1)
    {
        $sql = "
                SELECT *
                FROM country AS c1
                WHERE status = 1
                ORDER BY id = 'US' DESC, name
                ";

        if ($detail) {

            $rs = array();

            if ($query = $this->db->query($sql)) {
                foreach ($query->result($this->getVoClassname()) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        } else {
            $sql = "
                    SELECT COUNT(*) AS total
                    FROM (" . $sql . ") AS c";
            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function getRmaCountryList($lang = "en")
    {
        $sql = "SELECT c.id, IFNULL(ce.name, c.name) AS name, rf.rma_fc AS fc_id
                FROM country c
                INNER JOIN rma_fc rf
                    ON rf.cid = c.id
                INNER JOIN country_ext ce
                    ON ce.cid = c.id
                    AND ce.lang_id = ?
                WHERE c.allow_sell = 1
                AND c.status = 1";

        if ($query = $this->db->query($sql, $lang)) {
            $ret = array();
            foreach ($query->result($this->getVoClassname()) as $obj) {
                $ret[] = $obj;
            }

            return $ret;
        }

        return FALSE;
    }

    public function getListWRmaFc($where = array(), $option = array(), $classname = "CountryRmaFcDto")
    {
        $this->db->from('country AS c');
        $this->db->join('rma_fc r', 'r.cid = c.country_id', 'INNER');

        if (!isset($option["num_rows"])) {

            $this->db->select('c.*, r.rma_fc');

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

            if ($query = $this->db->get_where('', $where, $option["limit"], $option["offset"])) {
                $classname = ($classname) ? : $this->getVoClassname();
                $rs = [];
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }

        } else {
            $this->db->select("COUNT(*) as total", "FALSE");
            $this->db->where($where);
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function getCountryIdWithPlatform($platform_id)
    {
        $sql = "SELECT c.id
                FROM country c
                JOIN platform_biz_var pbv
                    ON c.country_id = platform_country_id
                WHERE c.status = 1 AND c.allow_sell = 1 AND pbv.selling_platform_id = ?";

        if (($query = $this->db->query($sql, $platform_id)) != FALSE) {
            return $query->row()->id;
        }

        return FALSE;
    }

    public function getCountryLanguageList()
    {
        $sql = "SELECT sp.id, c.id, c.id_3_digit, c.name, c.description, c.status, c.currency_id, pbv.language_id, c.fc_id, c.allow_sell, c.create_on, c.create_at, c.create_by, c.modify_on, c.modify_at, c.modify_by
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON sp.id = pbv.selling_platform_id
                JOIN country c
                    ON pbv.platform_country_id = c.id
                WHERE (sp.type = 'WEBSITE' OR sp.type = 'SKYPE') AND sp.status = 1
                    AND c.allow_sell = 1 AND c.status = 1
                GROUP BY c.id, c.id_3_digit, c.name, c.description, c.status, c.currency_id, pbv.language_id, c.fc_id, c.allow_sell
                ORDER BY name ASC";

        if ($result = $this->db->query($sql)) {

            $result_arr = array();
            $classname = $this->getVoClassname();

            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[] = $obj;
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function isAvailableCountryId($country_id = null)
    {
        if (!is_null($country_id)) {
            $sql = "SELECT c.id FROM country c WHERE c.status = 1 AND c.url_enable = 1 AND c.id = ?";

            if (($query = $this->db->query($sql, $country_id)) != FALSE) {
                return TRUE;
            }

            return FALSE;
        }
        return FALSE;
    }

    public function getAllAvailableCountryWithCorrectLang($lang_id)
    {
        $sql = "select c.id, ce.name, c.currency_id, c.language_id from country_ext ce
                inner join country c on c.id=ce.cid and c.status=1 and ce.lang_id='" . $lang_id . "' and c.allow_sell=1 order by ce.name";

        if ($result = $this->db->query($sql)) {

            $result_arr = array();
            $classname = $this->getVoClassname();

            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[] = $obj;
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function isAllowedPostal($country_code, $postal_code)
    {
        $sql = "select * from country_blocked_postal_code
        where country_id = ? and blocked_postal_code = ?";

        if ($result = $this->db->query($sql, array($country_code, $postal_code))) {
            if ($result->num_rows > 0) return false; else return true;
        }
    }

}
