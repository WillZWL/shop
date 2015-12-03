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
}

/* End of file supplier_dao.php */
/* Location: ./system/application/libraries/dao/supplier_dao.php */