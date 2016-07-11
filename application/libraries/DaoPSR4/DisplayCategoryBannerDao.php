<?php

namespace ESG\Panther\Dao;

class DisplayCategoryBannerDao extends BaseDao
{
    private $tableName = "display_category_banner";
    private $voClassname = "DisplayCategoryBannerVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

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

    public function getListWithName($level = "1", $parent = "0", $classname = "BannerCatListDto")
    {
        $sql = "SELECT c.id, c.name, c.level, IFNULL(pv.pv_cnt,0) AS pv_cnt, IFNULL(pb.pb_cnt,0) AS pb_cnt, IFNULL(stat.status,0) AS status, IFNULL(s.ttl,0) as count_row
                FROM category c
                LEFT JOIN (SELECT catid, count(position_id) AS pv_cnt
                             FROM display_category_banner
                             WHERE `usage`='PV'
                             GROUP BY catid
                                ) AS pv
                    ON c.id = pv.catid
                LEFT JOIN (SELECT catid, count(position_id) AS pb_cnt
                             FROM display_category_banner
                             WHERE `usage`='PB'
                             GROUP BY catid
                            ) AS pb
                    ON c.id = pb.catid
                LEFT JOIN (SELECT catid, count(status) AS status
                             FROM display_category_banner
                             WHERE `status` = 'A'
                             GROUP BY catid
                            ) as stat
                    ON c.id = stat.catid
                LEFT JOIN (SELECT cc.parent_cat_id, count(cc.id) as ttl
                        FROM category cc
                        GROUP BY cc.parent_cat_id) AS s
                    ON s.parent_cat_id = c.id
                WHERE c.level = $level
                AND c.parent_cat_id = $parent
                AND id <> '0'
                ORDER BY c.name ASC";
        $rs = array();
        if ($query = $this->db->query($sql)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }

        return FALSE;
    }

    public function getDbWithGraphic($catid = '', $banner_type = '', $display_id = '', $position_id = '', $slide_id = '', $country_id = '', $lang_id = '', $usage = '', $backup_image = "", $classname = "BannerWithGraphicDto")
    {
        if ($country_id) {
            if ($banner_type == "F") {
                if ($backup_image) {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_category_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.image_id = g.id) AND g.status = 1
                        WHERE db.catid = $catid AND db.display_id = $display_id AND db.position_id = $position_id AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage' AND db.country_id = '$country_id'
                    ";
                } else {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_category_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.flash_id = g.id) AND g.status = 1
                        WHERE db.catid = $catid AND db.display_id = $display_id AND db.position_id = $position_id AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage' AND db.country_id = '$country_id'
                    ";

                }
            } else {
                $sql =
                    "
                    SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                    FROM display_category_banner db
                    LEFT JOIN display_banner_config dbc
                        ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                    LEFT JOIN graphic g
                        ON (db.image_id = g.id) AND g.status = 1
                    WHERE db.catid = $catid AND db.display_id = $display_id AND db.position_id = $position_id AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id = '$country_id'
                ";
            }
        } else {
            if ($banner_type == "F") {
                if ($backup_image) {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_category_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.image_id = g.id AND g.status = 1)
                        WHERE db.catid = $catid AND db.display_id = $display_id AND db.position_id = $position_id AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id IS NULL
                    ";
                } else {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_category_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.flash_id = g.id AND g.status = 1)
                        WHERE db.catid = $catid AND db.display_id = $display_id AND db.position_id = $position_id AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id IS NULL
                    ";
                }
            } else {
                $sql =
                    "
                    SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                    FROM display_category_banner db
                    LEFT JOIN display_banner_config dbc
                        ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                    LEFT JOIN graphic g
                        ON (db.image_id = g.id AND g.status = 1)
                    WHERE db.catid = $catid AND db.display_id = $display_id AND db.position_id = $position_id AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id IS NULL
                ";
            }
        }
        if ($query = $this->db->query($sql)) {
            $rs = $query->result($classname);
            return $rs[0];
        }
    }

}

?>