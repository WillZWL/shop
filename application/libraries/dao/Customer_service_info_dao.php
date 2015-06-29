<?php

include_once 'Base_dao.php';

class Customer_service_info_dao extends Base_dao
{
    private $table_name="customer_service_info";
    private $vo_classname="Customer_service_info_vo";
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

    public function get_short_text($platform_id)
    {
        $sql = "SELECT short_text
                FROM customer_service_info
                WHERE platform_id = ?";

        if($query = $this->db->query($sql, array($platform_id)))
        {
            return $query->row()->short_text;
        }
    }

    public function get_cs_contact_list_by_country($where)
    {
        // var_dump($where); die();
        $this->db->from("customer_service_info AS csi");
        $this->db->join("platform_biz_var AS pbv", "csi.platform_id = pbv.selling_platform_id", "INNER");
        $this->db->join("country AS c", "c.id = pbv.platform_country_id", "INNER");
        $this->db->select("csi.short_text, c.name, csi.operating_hours, lang_id, pbv.platform_country_id");
        $this->db->where($where);

        if($query = $this->db->get())
        {
            // var_dump($this->db->last_query()); die();
            $ret = array();
            $array = $query->result_array();
            foreach($array as $row)
            {
                $image_src = "/images/icon-" . strtolower($row["platform_country_id"]) . ".png";
                $ret[] = array
                (
                    "country_name"=>$row["name"],
                    "contact_number"=>$row["short_text"],
                    "operating_hours"=>$row["operating_hours"],
                    "image_src"=>$image_src,
                    "lang_id"=>$row["lang_id"]
                );
                // var_dump($image_src); die();
                // var_dump($row["platform_country_id"]); die();
            }
            return $ret;
        }
        return FALSE;
    }
}

/* End of file customer_service_info_dao.php */
/* Location: ./app/libraries/dao/Customer_service_info_dao.php */