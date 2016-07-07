<?php
namespace ESG\Panther\Dao;

class CourierDao extends BaseDao
{
    private $tableName = "courier";
    private $voClassName = "CourierVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getListWithName($where = [], $option = [], $classname = "")
    {

        $this->db->from('courier AS c');
        $this->db->join('courier_region AS crc', 'c.id = crc.courier_id', 'INNER');
        $this->db->join('region AS r', 'crc.region_id = r.id', 'LEFT');

        if (!empty($where["courier_id"])) {
            $this->db->where('c.id', $where["courier_id"]);
        }

        if (empty($option["orderby"])) {
            $option["orderby"] = "crc.region_id ASC";
        }

        if (empty($option["num_rows"])) {

            $this->db->select('crc.courier_id, crc.region_id, r.region_name');

            $this->db->order_by($option["orderby"]);

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if (!empty($this->rows_limit)) {
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


    public function getRegionCountryList($where = [], $option = [], $classname = "")
    {

        $this->db->from('courier_region AS crc');
        $this->db->join('region_country AS rc', 'crc.region_id = rc.region_id', 'LEFT');
        $this->db->join('region AS r', 'rc.region_id = r.id', 'LEFT');
        $this->db->join('country c', 'rc.country_id = c.id', 'LEFT');
        $this->db->group_by(["crc.courier_id", "r.region_name"]);

        if (!empty($where["courier_id"])) {
            $this->db->where('crc.courier_id', $where["courier_id"]);
        }

        if (empty($option["orderby"])) {
            $option["orderby"] = "r.region_name ASC";
        }

        if (empty($option["num_rows"])) {

            $this->db->select('crc.courier_id, r.region_name, GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ", ") AS countries', FALSE);

            $this->db->order_by($option["orderby"]);

            if (empty($option["limit"])) {
                $option["limit"] = @$this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if (!empty($this->rows_limit)) {
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

    public function getCourierInfo( $so_no ,$class_name = 'CourierVo'){
        
        $where["soal.so_no"] = $so_no;
        $this->db->select('courier.*');
        $this->db->from('courier');
        $this->db->join('so_shipment AS sosh', 'sosh.courier_id = courier.id', 'INNER');
        $this->db->join('so_allocate AS soal', 'sosh.sh_no = soal.sh_no', 'INNER');
        $this->db->where($where);
        $this->db->limit(1);

        if ($query = $this->db->get())
        {
            return $query->row(0, 'object', $class_name);
        }
    }

}


