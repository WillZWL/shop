<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Custom_classification_mapping_dao extends Base_dao {
    private $table_name="custom_classification_mapping";
    private $vo_class_name="Custom_classification_mapping_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function Custom_classification_mapping_dao(){
        parent::__construct();
    }

    public function Sub_cat_var_dao(){
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

    public function get_sccc($where=array())
    {
        return $this->get($where);
    }

    public function update_pcc($obj)
    {
        return $this->update($obj);
    }

    public function include_pcc_vo()
    {
        return $this->include_vo();
    }

    public function add_pcc(Base_vo $obj)
    {
        return $this->insert($obj);
    }

    public function get_ccm_list($where=array(), $option=array(), $classname="sub_cat_custom_classification_dto")
    {
        if(isset($where["ccm.country_id"]))
        {
            $ccm_clause = " AND (ccm.country_id = '" . $where['ccm.country_id'] . "')";
            unset($where["ccm.country_id"]);
        }
        $this->db->from("category AS sc");
        $this->db->join("category AS cat", "cat.id = sc.parent_cat_id", "INNER");
        $this->db->join("custom_classification_mapping AS ccm", "sc.id = ccm.sub_cat_id $ccm_clause", "LEFT");
        $this->db->join("custom_classification AS cc", "cc.id = ccm.custom_class_id", "LEFT");
        $this->db->where(array("sc.level"=>2, "cat.level"=>1));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'cat.id cat_id, cat.name cat_name, sc.id sub_cat_id, sc.name sub_cat_name, ccm.country_id, cc.code, cc.description, cc.duty_pcent, ccm.create_on, ccm.create_at, ccm.create_by, ccm.modify_on, ccm.modify_at, ccm.modify_by');
    }

    public function get_all_ccm_list($sub_cat, $option=array(), $classname="sub_cat_custom_classification_dto")
    {
        $sql = <<<SQL
                    SELECT
                        ccm.sub_cat_id, ccm.country_id, cc.code, cc.duty_pcent, cc.description
                    FROM custom_classification_mapping ccm
                    INNER JOIN custom_classification cc on ccm.custom_class_id = cc.id
                    WHERE
                         ccm.sub_cat_id = $sub_cat
                    order by ccm.country_id asc
SQL;
        $query = $this->db->query($sql);
        if (!$query)
        {
            return FALSE;
        }
        $array = $query->result_array();
        return $array;
    }

    public function get_hs_by_subcat_and_country($where=array(), $option=array(), $classname="product_custom_class_dto")
    {
        $this->db->from("custom_classification cc");
        $this->db->join("custom_classification_mapping ccm","ccm.custom_class_id = cc.id","INNER");
        $this->db->where($where);
        $this->db->select("cc.code, cc.description",false);
        $rs = array();
        //$this->include_dto($classname);
        /*$this->db->save_queries = true;
        $this->db->get();
        echo "<pre>"; var_dump($this->db->last_query()); var_dump($this->db->_error_message());die();*/

        if ($query = $this->db->get())
        {
            if($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $rs[] = $row;
                }
            }
            return $rs;
        }
        else
        {
            return false;
        }
    }
//  public function get_main_cc_list($sub_cat, $option=array(), $classname="sub_cat_custom_classification_dto")
//  {
//      $sql = <<<SQL
//                  SELECT
//                      ccm.sub_cat_id, ccm.country_id, cc.code, cc.duty_pcent
//                  FROM custom_classification_mapping ccm
//                  INNER JOIN custom_classification cc on ccm.custom_class_id = cc.id
//                  WHERE
//                       ccm.sub_cat_id = $sub_cat
//                  order by ccm.country_id asc
// SQL;
//      $query = $this->db->query($sql);
//      if (!$query)
//      {
//          return FALSE;
//      }
//      $array = $query->result_array();
//      return $array;
//  }

}

?>