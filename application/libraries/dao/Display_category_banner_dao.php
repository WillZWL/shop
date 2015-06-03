<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Display_category_banner_dao extends Base_dao
{
	private $table_name="display_category_banner";
	private $vo_classname="Display_category_banner_vo";
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

	public function get_list_with_name($level="1",$parent="0",$classname="Banner_cat_list_dto")
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

		$this->include_dto($classname);

		$rs = array();

		if($query = $this->db->query($sql))
		{
			foreach($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}

			return $rs;
		}

		return FALSE;
	}

	public function get_db_w_graphic($catid, $banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image = "" , $classname="Banner_w_graphic_dto")
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
						FROM display_category_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.image_id = g.id) AND g.status = 1
						WHERE db.catid = ? AND db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ? AND db.country_id = ?
					';
				}
				else
				{
					$sql =
					'
						SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
						FROM display_category_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.flash_id = g.id) AND g.status = 1
						WHERE db.catid = ? AND db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ? AND db.country_id = ?
					';

				}
			}
			else
			{
				$sql =
				'
					SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
					FROM display_category_banner db
					LEFT JOIN display_banner_config dbc
						ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
					LEFT JOIN graphic g
						ON (db.image_id = g.id) AND g.status = 1
					WHERE db.catid = ? AND db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id = ?
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
						FROM display_category_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.image_id = g.id AND g.status = 1)
						WHERE db.catid = ? AND db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id IS NULL
					';
				}
				else
				{
					$sql =
					'
						SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
						FROM display_category_banner db
						LEFT JOIN display_banner_config dbc
							ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
						LEFT JOIN graphic g
							ON (db.flash_id = g.id AND g.status = 1)
						WHERE db.catid = ? AND db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id IS NULL
					';
				}
			}
			else
			{
				$sql =
				'
					SELECT db.*, g.id AS graphic_id, g.type AS graphic_type, g.location AS graphic_location, g.file AS graphic_file, dbc.banner_type
					FROM display_category_banner db
					LEFT JOIN display_banner_config dbc
						ON (dbc.banner_type = ? AND dbc.id = db.display_banner_config_id)
					LEFT JOIN graphic g
						ON (db.image_id = g.id AND g.status = 1)
					WHERE db.catid = ? AND db.display_id = ? AND db.position_id = ? AND db.slide_id = ? AND db.lang_id = ? AND db.usage = ?  AND db.country_id IS NULL
				';
			}
		}
		$this->include_dto($classname);


		if ($query = $this->db->query($sql, array($banner_type, $catid, $display_id, $position_id, $slide_id, $lang_id, $usage, $country_id)))
		{
			$rs = $query->result($classname);
			return $rs[0];
		}
	}

}

?>