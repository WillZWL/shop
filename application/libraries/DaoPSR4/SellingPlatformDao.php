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

    public function get_platform_by_lang($where = array(), $option = array())
    {
        $this->db->from("selling_platform sp");
        $this->db->join("platform_biz_var pbv", "sp.id = pbv.selling_platform_id", "INNER");
        $this->db->where($where);

        if (empty($option["num_rows"])) {
            $this->include_vo();
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

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($this->get_vo_classname()) as $obj) {
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

    public function get_platform_list_w_country_id($country_id = "")
    {
        $sql = "SELECT sp.*
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON sp.id = pbv.selling_platform_id
                WHERE (sp.type = 'WEBSITE' OR sp.type = 'SKYPE') AND sp.status = 1
                    AND pbv.platform_country_id = ?";

        if ($result = $this->db->query($sql, $country_id)) {
            $this->include_vo();

            $result_arr = array();
            $classname = $this->get_vo_classname();

            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[$obj->get_type()][] = $obj;
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function get_platform_list_w_lang_id($lang_id = "")
    {
        $sql = "SELECT sp.*
                FROM selling_platform sp
                JOIN platform_biz_var pbv
                    ON sp.id = pbv.selling_platform_id
                JOIN country c
                    ON c.id = pbv.platform_country_id
                WHERE (sp.type = 'WEBSITE' OR sp.type = 'SKYPE') AND sp.status = 1
                    AND c.allow_sell = 1 AND c.status = 1
                    AND pbv.language_id = ?";

        if ($result = $this->db->query($sql, $lang_id)) {
            $this->include_vo();

            $result_arr = array();
            $classname = $this->get_vo_classname();

            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[$obj->get_type()][] = $obj;
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function get_platform_type_list()
    {
        $sql = "SELECT DISTINCT type
                FROM selling_platform
                WHERE status = 1
                ORDER BY type";

        if ($result = $this->db->query($sql)) {
            $this->include_vo();

            $result_arr = array();
            $classname = $this->get_vo_classname();

            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[] = $obj->get_type();
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function get_selling_platform_w_lang_id($where = array(), $option = array(), $classname = "selling_platform_w_lang_id_dto")
    {
        $this->db->from("selling_platform AS sp");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = sp.id", "INNER");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "sp.*, pbv.language_id lang_id");
    }

    public function get_platform_list_w_allow_sell_country($type = "")
    {
        //Only those platform not started with WEB%
        if (isset($type) && $type == "MARKETPLACE") {
            $sql = "SELECT sp.id AS platform_id, c.id AS country_id
                    FROM selling_platform sp
                    JOIN platform_biz_var pbv
                        ON sp.id = pbv.selling_platform_id
                    JOIN country c
                        ON c.id = pbv.platform_country_id
                    WHERE (sp.id NOT LIKE 'WEB%') AND sp.status = 1
                        AND c.allow_sell = 1 AND c.status = 1";
        } //Original query, still be used to retrieve the WEBSITE type
        else {
            $sql = "SELECT sp.id AS platform_id, c.id AS country_id
                    FROM selling_platform sp
                    JOIN platform_biz_var pbv
                        ON sp.id = pbv.selling_platform_id
                    JOIN country c
                        ON c.id = pbv.platform_country_id
                    WHERE (sp.type = ?) AND sp.status = 1
                        AND c.allow_sell = 1 AND c.status = 1";
        }

        if ($result = $this->db->query($sql, $type)) {
            $this->include_vo();

            $result_arr = array();
            $classname = $this->get_vo_classname();

            foreach ($result->result() as $row) {
                $result_arr[] = array("platform_id" => $row->platform_id, "country_id" => $row->country_id);
            }
            return $result_arr;
        }
        return FALSE;
    }
}

?>
