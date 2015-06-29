<?php

include_once 'Base_dao.php';

class Ixten_reprice_rule_dao extends Base_dao
{
    private $table_name="ixten_reprice_rule";
    private $vo_classname="Ixten_reprice_rule_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_ixten_reprice_rule_list($where=array(), $option=array())
    {
        if($rule_list = $this->get_list($where, $option))
        {
            foreach($rule_list as $rule)
            {
                $ret[$rule->get_platform_id()][] = $rule;
            }

            return $ret;
        }

        return false;
    }

    public function get_list_index($where=array(), $option=array())
    {
        if(!isset($option["num_row"]))
        {
            return $this->get_list($where, $option);
        }
        else
        {
            $this->db->from('ixten_reprice_rule');

            $this->db->where($where);

            $this->db->select("COUNT(*) as total");

            if($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }
        return FALSE;
    }
}

/* End of file ixten_reprice_rule_dao.php */
/* Location: ./app/libraries/dao/Ixten_reprice_rule_dao.php */