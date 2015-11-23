<?php

namespace ESG\Panther\Dao;

class AffiliateDao extends BaseDao
{
    private $tableName = "affiliate";
    private $voClassname = "AffiliateVo";

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

    public function setFeedPlatform($affiliate_id, $platform_id = "")
    {
        if ($platform_id == "") {
            $data = [$affiliate_id];
            $query = "
                update affiliate set
                    platform_id = ''
                where id = ?
            ";
            $this->db->query($query, $data);
        } else {
            $data = [$platform_id, $affiliate_id];
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
        $result = $this->getList([], ["limit" => -1]);
        foreach ($result as $dbrow) {
            $row["id"] = $dbrow->getId();
            $row["platform_id"] = $dbrow->getPlatformId();

            $list[] = $row;
        }
        return $list;
    }
}


