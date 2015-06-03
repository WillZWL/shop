<?php
class Display_category_banner_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/display_category_banner_service');
		$this->load->library('service/language_service');
		$this->load->library('service/country_service');
		$this->load->library('service/graphic_service');
		$this->load->library('service/category_service');

	}

	public function get_list($level,$parent="")
	{
		return $this->display_category_banner_service->get_list_with_name($level,$parent);
	}

	public function get_display_banner($where = array())
	{
		return $this->display_category_banner_service->get_display_banner($where);
	}

	public function get_display_banner_list($where = array(), $option = array())
	{
		return $this->display_category_banner_service->get_display_banner_list($where, $option);
	}

	public function get_db_w_graphic($catid, $banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image="")
	{
		return $this->display_category_banner_service->get_db_w_graphic($catid, $banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image);
	}

	public function insert_display_banner($obj)
	{
		$this->db->_protect_identifiers = TRUE;
		return $this->display_category_banner_service->insert($obj);
	}

	public function update_display_banner($obj)
	{
		$this->db->_protect_identifiers = TRUE;
		return $this->display_category_banner_service->update($obj);
	}

	public function get_db_num_rows($where = array())
	{
		return $this->display_category_banner_service->get_db_num_rows($where);
	}

	public function get_different_country_list($catid, $display_id, $lang_id)
	{
		return $this->display_category_banner_service->get_different_country_list($catid, $display_id, $lang_id);
	}

	public function get_publish_banner($catid, $display_id, $position_id, $country_id, $lang_id, $usage = "PB")
	{
		return $this->display_category_banner_service->get_publish_banner($catid, $display_id, $position_id, $country_id, $lang_id, $usage = "PB");
	}

	public function get_cat_detail($where=array())
	{
		return $this->category_service->get($where);
	}
}
