<?php
namespace ESG\Panther\Dao;

class CountryExtDao extends BaseDao
{
    private $tableName = "country_ext";
    private $voClassname = "CountryExtVo";

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

    public function getCountryNameInLang($where = [], $option = [], $classname = "CountryLangNameDto")
    {
        $this->db->_protect_identifiers = FALSE;
        $this->db->from('country AS c');
        $this->db->join('language AS l', 'l.status = 1', 'LEFT');
        $this->db->join('country_ext AS ce', 'ce.cid = c.id AND l.id = ce.lang_id', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select("c.id, c.name, ce.name AS lang_name", FALSE);

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
                return $rs ? (object)$rs : $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

}


