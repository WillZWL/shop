<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Display_banner_dao extends Base_dao
{
	private $table_name="display_banner";
	private $vo_classname="Display_banner_vo";
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

	public function get_db_w_graphic($banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image = "" , $classname="Display_banner_w_graphic_dto")
	{
		if($country_id)
		{
			if($banner_type == "F")
			{
				if($backup_image)
				{
					$sql =
					'
						SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
						FROM display_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.image_id = g.id) AND g.status = 1
						WHERE db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ? AND db.country_id = ?
					';
				}
				else
				{
					$sql =
					'
						SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
						FROM display_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.flash_id = g.id) AND g.status = 1
						WHERE db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ? AND db.country_id = ?
					';

				}
			}
			else
			{
				$sql =
				'
					SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
					FROM display_banner db
					LEFT JOIN display_banner_config dbc
						ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
					LEFT JOIN graphic g
						ON (db.image_id = g.id) AND g.status = 1
					WHERE db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id = ?
				';
			}
		}
		else
		{
			if($banner_type == "F")
			{
				if($backup_image)
				{
					$sql =
					'
						SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
						FROM display_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.image_id = g.id AND g.status = 1)
						WHERE db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id IS NULL
					';
				}
				else
				{
					$sql =
					'
						SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
						FROM display_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.flash_id = g.id AND g.status = 1)
						WHERE db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id IS NULL
					';
				}
			}
			else
			{
				$sql =
				'
					SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
					FROM display_banner db
					LEFT JOIN display_banner_config dbc
						ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
					LEFT JOIN graphic g
						ON (db.image_id = g.id AND g.status = 1)
					WHERE db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id IS NULL
				';
			}
		}
		$this->include_dto($classname);


		if ($query = $this->db->query($sql, array($banner_type, $display_id, $position_id, $slide_id, $lang_id, $usage, $country_id)))
		{
			$rs = $query->result($classname);
			return $rs[0];
		}
	}

}
/* End of file display_banner_dao.php */
/* Location: ./app/libraries/dao/Display_banner_dao.php */