<?php

namespace ESG\Panther\Dao;

class AffiliateSkuPlatformDao extends BaseDao
{
    private $tableName = "affiliate_sku_platform";
    private $voClassname = "AffiliateSkuPlatformVo";

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

    public function getFeedListBySku($sku, $platform_id, $status = 0)
    {
        $data = [$sku, $platform_id, $status];
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

    public function getSkuFeedStatus($sku, $platform_id)
    {
        $data = [$sku, $platform_id, $platform_id];
        $query = "
            select
                if(status is null, 0, 1) chk,
                a.affiliate_id,
                status
            from affiliate a
            left join affiliate_sku_platform asp
            on a.affiliate_id         = asp.affiliate_id
            and sku         = ?
            and asp.platform_id = ?
            where a.platform_id = ?
            order by chk desc, affiliate_id asc
        ";
        $result = $this->db->query($query, $data);
        foreach ($result->result_array() as $row) {
            if ($row["status"] == null) {
                $row["status"] = 0;
            }

            $rs[] = $row;
        }

        return $rs;
    }
}


