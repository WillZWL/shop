<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_banner extends MY_Controller
{
	private $app_id = "MKT0028";
	private $lang_id = "en";

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url','directory','notice'));
		$this->load->model('marketing/category_banner_model');
	}

	public function index($language_id="")
	{
		define('CAT_PH', $this->context_config_service->value_of("cat_img_path"));
		include_once APPPATH."language/".$this->_get_app_id()."01_".$this->_get_lang_id().".php";
		$data["lang"] = $lang;

		if($this->input->post('posted'))
		{
			$sub_cat_id = $_POST['sub_cat_id'];
			$sub_cat_list = $_POST['sub_cat_list'];
			$country_list = $_POST['country_list'];
			$i = 0;
			foreach($country_list As $country_id)
			{
				foreach($sub_cat_list AS $key=>$value)
				{
					$key++;
					$data['cat_ban'] = $this->category_banner_model->get_cat_ban(array("cat_id"=>$value, "lang_id"=>$language_id, "country_id"=>$country_id));
					if(!($data['cat_ban']))
					{
						$data['cat_ban'] = $this->category_banner_model->get_cat_ban();
						$data['cat_ban']->set_cat_id($value);
						$data['cat_ban']->set_lang_id($language_id);
						$data['cat_ban']->set_country_id($country_id);
						$data['cat_ban']->set_priority($key);
						$this->category_banner_model->insert_cat_ban($data['cat_ban']);
					}
					else
					{
						$data['cat_ban']->set_priority($key);
						$this->category_banner_model->update_cat_ban($data['cat_ban']);
					}
				}
				if($_FILES["image"]["name"] && $sub_cat_id)
				{
					$data['cat_ban'] = $this->category_banner_model->get_cat_ban(array("cat_id"=>$sub_cat_id, "lang_id"=>$language_id, "country_id"=>$country_id));
					$att_name = $sub_cat_id.'-'.$language_id.'-'.$country_id;
					$ext_file = $this->category_banner_model->upload_image(array("name"=>"image", "file_name"=>$att_name, "upload_path"=>CAT_PH, "allowed_types"=>"gif|jpg|jpeg|png", "is_image"=>TRUE));
					$data['cat_ban']->set_image($ext_file);
					$this->category_banner_model->update_cat_ban($data['cat_ban']);

					$data['updated_banner'] = $sub_cat_id.'-'.$language_id.'-'.$country_id;
					$data['updated_language'] = $this->category_banner_model->get_language(array("id"=>$language_id));
					$data['updated_country'][$i] = $this->category_banner_model->get_country(array("id"=>$country_id));
					$data['updated_cat_ban'] = $this->category_banner_model->get_category(array("id"=>$sub_cat_id));
					$i++;
				}
			}
		}
		$data['notice'] = notice($lang);
		$data['country_list'] = $this->category_banner_model->get_country_list(array("status"=>1), array("orderby"=>"name ASC"));
		$data['language_list'] = $this->category_banner_model->get_language_list(array("status"=>1), array("orderby"=>"name ASC"));
		$data['sub_cat_list'] = $this->category_banner_model->get_sub_cat_list(array("level"=>2), array("orderby"=>"name ASC"));
		if(!($data['cat_ban_list'] = $this->category_banner_model->get_cat_ban_list($language_id)))
		{
			$data['cat_ban_list'] = $this->category_banner_model->get_sub_cat_list(array("level"=>2), array("orderby"=>"name ASC"));
		}
		$data['language_id'] = $language_id;
		$data['sub_cat_id'] = $sub_cat_id;
		$data['country_list_w_lang'] = $this->category_banner_model->get_country_list(array("status"=>1, "language_id"=>$data['language_id']), array("orderby"=>"name ASC"));
		$this->load->view('marketing/category_banner/category_banner_view', $data);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}
}

