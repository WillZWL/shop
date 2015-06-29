<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Domain_platform_dao extends Base_dao
{
    private $table_name = "domain_platform";
    private $vo_class_name = "Domain_platform_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_domain_platform_w_lang($type = "object", $classname = "Domain_platform_w_lang_dto")
    {
        if ($type == "object") {
            $this->include_dto($classname);
        }

        $sql = "
                SELECT dp.*, pbv.platform_country_id, pbv.language_id, pbv.platform_currency_id, sp.type
                FROM domain_platform AS dp
                LEFT JOIN platform_biz_var AS pbv
                ON dp.platform_id = pbv.selling_platform_id
                LEFT JOIN selling_platform AS sp
                    ON sp.id = dp.platform_id
                WHERE dp.domain = ? OR ? LIKE dp.domain
                    AND sp.status = 1
                ORDER BY dp.domain = ? DESC, dp.domain = '%'
                LIMIT 1
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']))) {
            if ($query->num_rows()) {
                return $query->row(0, $type, $classname);
            }
        }
        return FALSE;
    }

    public function get_domain_platform_w_lang_override($platform_type, $type = "object", $classname = "Domain_platform_w_lang_dto")
    {

        if ($type == "object") {
            $this->include_dto($classname);
        }

        $sql = "
                SELECT dp.*, sp.id AS platform_id, npbv.platform_country_id, npbv.language_id, npbv.platform_currency_id, sp.type
                FROM domain_platform AS dp
                LEFT JOIN platform_biz_var AS pbv
                    ON pbv.selling_platform_id = dp.platform_id
                LEFT JOIN (selling_platform AS sp, platform_biz_var AS npbv)
                    ON (sp.id = npbv.selling_platform_id AND pbv.platform_country_id = npbv.platform_country_id AND sp.type = ?)
                WHERE dp.domain = ? OR ? LIKE dp.domain
                    AND sp.status = 1
                ORDER BY dp.domain = ? DESC, dp.domain = '%'
                LIMIT 1
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($platform_type, $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']))) {
            if ($query->num_rows()) {
                return $query->row(0, $type, $classname);
            }
        }

        return FALSE;
    }

    public function get_by_country_id($country_id, $platform_type, $domain_type, $type = "object", $classname = "Domain_platform_w_lang_dto")
    {

        if ($type == "object") {
            $this->include_dto($classname);
        }

        $sql = "
                SELECT dp.*, pbv.platform_country_id, pbv.language_id, pbv.platform_currency_id, sp.type
                FROM domain_platform AS dp
                LEFT JOIN platform_biz_var AS pbv
                    ON dp.platform_id = pbv.selling_platform_id
                LEFT JOIN selling_platform AS sp
                    ON sp.id = dp.platform_id
                WHERE pbv.platform_country_id = ?
                    AND dp.domain_type = ?
                    AND dp.status = 1
                    AND sp.status = 1
                ORDER BY sp.type = ? DESC
                LIMIT 1
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($country_id, $domain_type, $platform_type))) {
            if ($query->num_rows()) {
                return $query->row(0, $type, $classname);
            }
        }
        return FALSE;
    }

    public function get_by_platform_id($platform_id, $domain_type, $type = "object", $classname = "Domain_platform_w_lang_dto")
    {

        if ($type == "object") {
            $this->include_dto($classname);
        }

        $sql = "
                SELECT dp.*, pbv.platform_country_id, pbv.language_id, pbv.platform_currency_id, sp.type
                FROM domain_platform AS dp
                LEFT JOIN platform_biz_var AS pbv
                    ON dp.platform_id = pbv.selling_platform_id
                LEFT JOIN selling_platform AS sp
                    ON sp.id = dp.platform_id
                WHERE sp.id = ?
                    AND dp.domain_type = ?
                    AND sp.status = 1
                    AND dp.status = 1
                LIMIT 1
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($platform_id, $domain_type))) {
            if ($query->num_rows()) {
                return $query->row(0, $type, $classname);
            }
        }
        return FALSE;
    }

}
