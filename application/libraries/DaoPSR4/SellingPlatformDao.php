<?php
namespace ESG\Panther\Dao;

class SellingPlatformDao extends BaseDao
{
    private $tableName = "selling_platform";
    private $voClassName = "SellingPlatformVo";

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

    public function getPlatformByLang($where = [], $option = [])
    {
        $this->db->from("selling_platform sp");
        $this->db->join("platform_biz_var pbv", "sp.selling_platform_id = pbv.selling_platform_id", "INNER");
        $this->db->where($where);

        if (empty($option["num_rows"])) {
            $this->db->select("sp.*");

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
                foreach ($query->result($this->getVoClassname()) as $obj) {
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

    public function getPlatformListWithCountryId($country_id = "")
    {
        $sql = "SELECT sp.*
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON sp.selling_platform_id = pbv.selling_platform_id
                WHERE (sp.type = 'WEBSITE') AND sp.status = 1
                    AND pbv.platform_country_id = ?";

        if ($result = $this->db->query($sql, $country_id)) {

            $result_arr = [];

            foreach ($result->result($this->getVoClassname()) as $obj) {
                $result_arr[$obj->getType()][] = $obj;
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function getPlatformListWithLangId($lang_id = "")
    {
        $sql = "SELECT sp.*
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON sp.selling_platform_id = pbv.selling_platform_id
                JOIN country c
                    ON c.id = pbv.platform_country_id
                WHERE (sp.type = 'WEBSITE') AND sp.status = 1
                    AND c.allow_sell = 1 AND c.status = 1
                    AND pbv.language_id = ?";

        if ($result = $this->db->query($sql, $lang_id)) {

            $result_arr = [];

            foreach ($result->result($this->getVoClassname()) as $obj) {
                $result_arr[$obj->getType()][] = $obj;
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function getWebsiteCountryList() {
        $sql = "select pbv.platform_country_id from selling_platform sp
                inner join platform_biz_var pbv on pbv.selling_platform_id=sp.selling_platform_id
                where sp.status=1 and type='WEBSITE' and sp.selling_platform_id like 'WEB%' group by pbv.platform_country_id order by pbv.platform_country_id";
        $result = $this->db->query($sql);
        if (!$result) {
            return FALSE;
        }

        return $result->result_array();
    }

    public function getPlatformTypeList()
    {
        $sql = "SELECT DISTINCT type
                FROM selling_platform
                WHERE status = 1
                ORDER BY type";

        if ($result = $this->db->query($sql)) {

            $result_arr = [];

            foreach ($result->result($this->getVoClassname()) as $obj) {
                $result_arr[] = $obj->getType();
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function getSellingPlatformWithLangId($where = [], $option = [], $classname = "SellingPlatformWithLangIdDto")
    {
        $this->db->from("selling_platform AS sp");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = sp.selling_platform_id", "INNER");
        return $this->commonGetList($classname, $where, $option, "sp.*, pbv.language_id lang_id");
    }

    public function getPlatformListWithAllowSellCountry($type = "", $classname = 'SellingPlatformDto')
    {
        $where = ['sp.type' => $type, 'sp.status' => 1, 'c.allow_sell' => 1, 'c.status' => 1];
        $option = ['limit' => '-1'];
        $this->db->from('selling_platform AS sp');
        $this->db->join('platform_biz_var AS pbv', "pbv.selling_platform_id = sp.selling_platform_id", "INNER");
        $this->db->join('country AS c', "c.country_id = pbv.platform_country_id", "INNER");
        return $this->commonGetList($classname, $where, $option, "sp.*, c.country_id");
    }
}
