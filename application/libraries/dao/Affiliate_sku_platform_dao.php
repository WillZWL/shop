<?php

include_once 'Base_dao.php';

class Affiliate_sku_platform_dao extends Base_dao
{
    private $table_name = "affiliate_sku_platform";
    private $vo_classname = "affiliate_sku_platform_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

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

    public function get_feed_list($platform_id = "")
    {
        $query = "
            select distinct affiliate_id from affiliate_sku_platform #where platform_id = ?
        ";
        $result = $this->db->query($query, $data);
        foreach ($result->result_array() as $row) {
            $rs[] = $row["affiliate_id"];
        }

        return $rs;
    }

    public function get_sku_feed_list($affiliate_id)
    {
        $data = array($affiliate_id);
        $query = "
            select sku, platform_id, status from affiliate_sku_platform where affiliate_id = ?
        ";
        $result = $this->db->query($query, $data);
        foreach ($result->result_array() as $row) {
            $rs[$row["platform_id"]][$row["sku"]] = $row["status"];
        }

        return $rs;
    }

    public function get_feed_list_by_sku($sku, $platform_id, $status = 0)
    {
        // $status=> 0 = auto, 1 = exclude, 2 = include
        $data = array($sku, $platform_id, $status);
        $query = "SELECT GROUP_CONCAT(affiliate_id) AS affiliate_list
                    FROM affiliate_sku_platform
                    WHERE sku = ?
                    AND platform_id = ?
                    AND status = ?";
        $result = $this->db->query($query, $data);

        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                return $row["affiliate_list"];
            }
        }
        return FALSE;
    }

    public function get_sku_feed_status($sku, $platform_id)
    {
        $data = array($sku, $platform_id, $platform_id);
        $query = "
            select
                if(status is null, 0, 1) chk,
                a.id,
                status
            from affiliate a
            left join affiliate_sku_platform asp
            on a.id         = asp.affiliate_id
            and sku         = ?
            and asp.platform_id = ?
            where a.platform_id = ?
            order by chk desc, id asc
        ";
        $result = $this->db->query($query, $data);
        foreach ($result->result_array() as $row) {
            if ($row["status"] == null) $row["status"] = 0;
            $rs[] = $row;
        }

        return $rs;
    }


    public function set_sku_feed_status($affiliate_id, $sku, $platform_id, $status_id)
    {
        $data = array($status_id, $affiliate_id, $sku, $platform_id);
        # 0 - auto
        # 1 - always include
        # 2 - always exclude
        $query = "
            update affiliate_sku_platform
                set status = ?
            where 1
            and affiliate_id = ?
            and sku          = ?
            and platform_id  = ?
        ";
        $this->db->query($query, $data);

        $query = "
            insert into affiliate_sku_platform
            (status, affiliate_id, sku, platform_id)
            values
            (?, ?, ?, ?)
        ";
        $this->db->query($query, $data);

        $query = "commit";
        $this->db->query($query, $data);
    }

    // clean up any entres that is set to auto
    public function clean_up()
    {
        # 0 - auto
        # 1 - always include
        # 2 - always exclude
        $query = "delete from affiliate_sku_platform where status = 0";
        return $this->db->query($query);
    }


}

/* End of file affiliate_dao.php */
/* Location: ./app/libraries/dao/Affiliate_dao.php */