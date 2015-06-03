<?php
include_once 'website_prod_info_dto.php';

class Website_prod_search_info_dto extends Website_prod_info_dto
{
	private $with_bundle;
	private $cat_name;
	private $sub_cat_name;
	private $sub_sub_cat_name;
	private $image_file_ext;

	public function __construct()
	{
		parent::Website_prod_info_dto();
	}

	public function set_with_bundle($data)
	{
		$this->with_bundle = $data;
	}

	public function get_with_bundle()
	{
		return $this->with_bundle;
	}

	public function set_cat_name($data)
	{
		$this->cat_name = $data;
	}

	public function get_cat_name()
	{
		return $this->cat_name;
	}

	public function set_sub_cat_name($data)
	{
		$this->sub_cat_name = $data;
	}

	public function get_sub_cat_name()
	{
		return $this->sub_cat_name;
	}

	public function set_sub_sub_cat_name($data)
	{
		$this->sub_sub_cat_name = $data;
	}

	public function get_sub_sub_cat_name()
	{
		return $this->sub_sub_cat_name;
	}

	public function set_image_file_ext($data)
	{
		$this->image_file_ext = $data;
	}

	public function get_image_file_ext()
	{
		return $this->image_file_ext;
	}
}

/* End of file website_prod_search_info_dto.php */
/* Location: ./app/libraries/dto/website_prod_search_info_dto.php */