<?php

namespace ESG\Panther\Dao;
class DisplayBannerDao extends BaseDao
{
    private $table_name = "display_banner";
    private $vo_classname = "DisplayBannerVo";
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

    public function getDbWithGraphic($banner_type = '', $display_id = '', $position_id = '', $slide_id ='', $country_id = '', $lang_id = '', $usage = '', $backup_image = "", $classname = "DisplayBannerWithGraphicDto")
    {
        if ($country_id) {
            if ($banner_type == "F") {
                if ($backup_image) {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.image_id = g.id) AND g.status = 1
                        WHERE db.display_id = '$display_id' AND db.position_id = '$position_id' AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage' AND db.country_id = '$country_id'
                    ";
                } else {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.flash_id = g.id) AND g.status = 1
                        WHERE db.display_id = '$display_id' AND db.position_id = '$position_id' AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage' AND db.country_id = '$country_id'
                    ";
                }
            } else {
                $sql =
                    "
                    SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                    FROM display_banner db
                    LEFT JOIN display_banner_config dbc
                        ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                    LEFT JOIN graphic g
                        ON (db.image_id = g.id) AND g.status = 1
                    WHERE db.display_id = '$display_id' AND db.position_id = '$position_id' AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id = '$country_id'
                ";
            }
        } else {
            if ($banner_type == "F") {
                if ($backup_image) {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.image_id = g.id AND g.status = 1)
                        WHERE db.display_id = '$display_id' AND db.position_id = '$position_id' AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id IS NULL
                    ";
                } else {
                    $sql =
                        "
                        SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                        FROM display_banner db
                        LEFT JOIN display_banner_config dbc
                            ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                        LEFT JOIN graphic g
                            ON (db.flash_id = g.id AND g.status = 1)
                        WHERE db.display_id = '$display_id' AND db.position_id = '$position_id' AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id IS NULL
                    ";
                }
            } else {
                $sql =
                    "
                    SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
                    FROM display_banner db
                    LEFT JOIN display_banner_config dbc
                        ON (dbc.banner_type = '$banner_type' AND dbc.id = db.display_banner_config_id)
                    LEFT JOIN graphic g
                        ON (db.image_id = g.id AND g.status = 1)
                    WHERE db.display_id = '$display_id' AND db.position_id = '$position_id' AND db.slide_id = '$slide_id' AND db.lang_id = '$lang_id' AND db.usage = '$usage'  AND db.country_id IS NULL
                ";
            }
        }
        if ($query = $this->db->query($sql)) {
            $rs = $query->result($classname);
            return $rs[0];
        }
    }

}

