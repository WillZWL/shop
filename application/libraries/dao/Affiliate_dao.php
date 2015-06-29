<?php

include_once 'Base_dao.php';

class Affiliate_dao extends Base_dao
{
    private $table_name="affiliate";
    private $vo_classname="Affiliate_vo";
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

    public function set_feed_platform($affiliate_id, $platform_id = "")
    {
        if ($platform_id == "")
        {
            $data = array($affiliate_id);
            $query = "
                update affiliate set
                    platform_id = ''
                where id = ?
            ";
            $this->db->query($query, $data);
        }
        else
        {
            $data = array($platform_id, $affiliate_id);
            $query = "
                update affiliate set
                    platform_id = ?
                where id = ?
            ";
            // var_dump($query);
            $this->db->query($query, $data);
        }

        $query = "commit";
        $this->db->query($query, $data);
    }

    public function get_feed_platform()
    {
        $result = $this->get_list(array(), array("limit"=>-1));
        foreach ($result as $dbrow)
        {
            $row["id"] = $dbrow->get_id();
            $row["platform_id"] = $dbrow->get_platform_id();

            $list[] = $row;
        }
        return $list;
    }

}

/* End of file affiliate_dao.php */
/* Location: ./app/libraries/dao/Affiliate_dao.php */