<?php
namespace ESG\Panther\Dao;

class DisplayBannerConfigDao extends BaseDao
{
    private $table_name = "display_banner_config";
    private $vo_classname = "DisplayBannerConfigVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getVoClassname()
    {
        return $this->vo_classname;
    }

    public function getSeqName()
    {
        return $this->seq_name;
    }

    public function getSeqMappingField()
    {
        return $this->seq_mapping_field;
    }

    public function getDifferentCountryList($display_id = '', $lang_id = '')
    {
        $sql =
            '
            SELECT DISTINCT dbc.country_id AS id, c.name
            FROM display_banner_config dbc
            JOIN country c
                ON c.id = dbc.country_id
            WHERE `dbc`.`country_id` IS NOT NULL AND `dbc`.`usage` = "PB" AND `dbc`.`status` = 1 AND `dbc`.`display_id` = ? AND `dbc`.`lang_id` = ?
        ';
        $resultp = $this->db->query($sql, array($display_id, $lang_id));
        foreach ($resultp->result() as $row) {
            $rs[] = $row;
        }
        return $rs;
    }

}

