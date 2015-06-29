<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Display_banner_config_dao extends Base_dao
{
    private $table_name="display_banner_config";
    private $vo_classname="Display_banner_config_vo";
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

    public function get_different_country_list($display_id, $lang_id)
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
        foreach ($resultp->result("") as $row)
        {
            $rs[] = $row;
        }
        return $rs;
    }

}
/* End of file display_banner_config_dao.php */
/* Location: ./app/libraries/dao/Display_banner_config_dao.php */