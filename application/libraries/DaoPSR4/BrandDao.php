<?php
namespace AtomV2\Dao;

class BrandDao extends BaseDao
{
    private $tableName = "brand";
    private $voClassName = "BrandVo";

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

    public function getBrandListWRegion($where = [], $option = [], $classname = "BrandWRegionDto")
    {
        $this->db->from('brand AS b');

        if (empty($option["orderby"])) {
            $option["orderby"] = "b.brand_name ASC";
        }

        if (empty($option["num_rows"])) {
            $this->db->select('b.id, b.brand_name, b.description, b.status, b.create_on, b.create_at, b.create_by, b.modify_on, b.modify_at, b.modify_by');
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
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }


    public function getBrandListWSrcReg($where = [], $option = [], $classname = "BrandWRegionDto")
    {

        $this->db->from('brand AS b');

        $this->db->where($where);

        if (empty($option["orderby"])) {
            $option["orderby"] = "b.brand_name ASC";
        }

        if (empty($option["num_rows"])) {

            $this->db->select('b.id, b.brand_name, b.description, b.status, b.create_on, b.create_at, b.create_by, b.modify_on, b.modify_at, b.modify_by');

            $this->db->order_by($option["orderby"]);

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
                if ($option["limit"] == 1) {
                    return $rs[0];
                } else {
                    return (object)$rs;
                }
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function getListedBrandByCat($cat_id = '')
    {
        if (empty($cat_id)) {
            return [];
        }

        $sql = 'SELECT distinct b.id, b.brand_name
            FROM brand b
            JOIN product p
                ON (p.brand_id = b.id AND p.cat_id = ? AND p.status = 2)
            WHERE b.status = 1
            ORDER BY b.brand_name';

        $result = $this->db->query($sql, $cat_id);

        if (!$result) {
            return [];
        }

        return $result->result_array();
    }

    public function getBrandFilterGridInfo($where = [], $option = [])
    {
        $this->db->select("br.id, br.brand_name, count(*) total");
        $this->db->from("product AS p");
        $this->db->join("brand AS br", "br.id = p.brand_id", "INNER");

        if ($option['groupby']) {
            $this->db->group_by($option['groupby']);
        }
        if ($option['orderby']) {
            $this->db->order_by($option['orderby']);
        }
        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = [];
            $array = $query->result_array();
            foreach ($array as $row) {
                $ret[] = ["id" => $row["id"], "name" => $row["brand_name"], "total" => $row['total']];
            }
            return $ret;
        }

        return FALSE;
    }

}
