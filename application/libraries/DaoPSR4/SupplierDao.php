<?php
namespace ESG\Panther\Dao;

class SupplierDao extends BaseDao
{
    private $table_name="supplier";
    private $vo_class_name="SupplierVo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getSeqName()
    {
        return $this->seq_name;
    }

    public function getSeqMappingField()
    {
        return $this->seq_mapping_field;
    }

    public function getSupplier($prod="")
    {
        if($prod == "")
        {
            return FALSE;
        }
        $sql = "SELECT s.*
                FROM supplier s
                JOIN supplier_prod sp
                    ON sp.supplier_id = s.id
                    AND sp.prod_sku = ?
                    AND sp.order_default = '1'
                LIMIT 1
                ";

        if($query = $this->db->query($sql, $prod))
        {
            foreach($query->result($this->getVoClassname()) as $obj)
            {
                $tmp = $obj;
            }

            return $tmp;
        }
        return FALSE;
    }

    public function checkValidSupplierCost($sku)
    {
        if (empty($sku)) {
            return FALSE;
        }

        $this->db->from("supplier AS s");
        $this->db->join("supplier_prod AS sp", "s.id = sp.supplier_id AND sp.order_default = 1", "INNER");
        $this->db->where("sp.prod_sku", $sku);
        $this->db->select("count(1) total");

        if ($query = $this->db->get()) {
            return $query->row()->total;
        }

        return FALSE;
    }


    public function getListWithName($where = [], $option = [])
    {

        $this->db->from('supplier AS s');
        $this->db->join('region AS sur', 's.supplier_reg = sur.id', 'LEFT');
        $this->db->join('region AS scr', 's.sourcing_reg = scr.id', 'LEFT');

        if ($where["id"] != "") {
            $this->db->operator_where('s.id', $where["id"]);
        }

        if ($where["name"] != "") {
            $this->db->like('s.name', $where["name"]);
        }

        if ($where["supplier_reg"] != "") {
            $this->db->like('sur.region_name', $where["supplier_reg"]);
        }

        if ($where["sourcing_reg"] != "") {
            $this->db->like('scr.region_name', $where["sourcing_reg"]);
        }

        if ($where["status"] != "") {
            $this->db->like('s.status', $where["status"]);
        }

        if (empty($option["num_rows"])) {
            $this->db->select('s.*, sur.region_name AS supplier_reg, scr.region_name AS sourcing_reg');

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
}

/* End of file supplier_dao.php */
/* Location: ./system/application/libraries/dao/supplier_dao.php */