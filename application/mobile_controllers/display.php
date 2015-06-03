<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Display extends MOBILE_Controller
{
	public function Display()
	{
		parent::MOBILE_Controller(array('template'=>'default'));
		$this->load->library('template');
		$this->load->helper(array('url','directory','datetime','tbswrapper'));
		$this->load->model('website/home_model');
		$this->load->model('website/common_data_prepare_model');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/kayako_service');
		$this->load->library('service/deliverytime_service');
	}

	public function view($page = '')
	{
		// The page will use /public_html/resources/mobile/template
		$html_page_list = array('about_us', 'bulk_sales', 'conditions_of_use', 'vb_contact');

		$data = array();
		$parameter = array('page'=>$page, 'subscribe-email'=>$_POST['subscribe-email']);
		$data = $this->common_data_prepare_model->get_data_array($this, $parameter);

		$this->template->add_link("rel='canonical' href='".base_url()."/display/view/$page'");	# SEO

		$lang_id = $_SESSION["lang_id"];
		if (empty($lang_id)) $lang_id = 'en';

		if (in_array($page, $html_page_list))
		{
			$this->load_tpl('content', 'static_page/' . $page . '_' . strtolower(PLATFORMCOUNTRYID), "", TRUE);
		}
		elseif ($page == 'faq')
		{
			if (PLATFORMCOUNTRYID == "PH")
			{
				$data['lang_id'] = strtolower(PLATFORMCOUNTRYID);
				$data['kb_url'] = base_url(FALSE) . 'hesk_' . strtolower(PLATFORMCOUNTRYID);
				$this->load_tpl('content', 'faq_kb.php', $data, TRUE);
			}
			else
			{
				$data['lang_id'] = $lang_id;
				switch($lang_id)
				{
					case "en":
						// this uses kayako as our knowledgebase
						$data['kb_url'] = $this->kayako_service->get_help_desk_url('/Knowledgebase/List');
						$this->load_tpl('content', 'faq_kb.php', $data, TRUE);
						break;

					case "it":
					case "fr":
					case "es":
						// this uses hesk as our knowledgebase
						$data['kb_url'] = base_url(FALSE) . 'hesk_' . strtolower(PLATFORMCOUNTRYID);
						$this->load_tpl('content', 'faq_kb.php', $data, TRUE);
						break;

					default:
						#$this->tbswrapper->tbsLoadTemplate('resources/template/faq_es.html', '', '', $data['lang_text']);
						#break;
						$this->load_tpl('content', 'faq_static.php', $data, TRUE);
						break;
				}
			}
		}
		elseif ($page == 'warranty')
		{
			switch (strtolower(PLATFORMCOUNTRYID))
			{
				case 'es' : $warranty_youtube_id = 'wWfzGcZyNag'; break;

				case 'nz' :
				case 'sg' :
				case 'my' :
				case 'hk' :
				case 'au' : $warranty_youtube_id = 'qN5h7zz0AwI'; break;

				case 'gb' :
				case 'fi' :
				case 'ie' :
				case 'us' : $warranty_youtube_id = 'upv2j5oA7jM';   break;
				case 'fr' : $warranty_youtube_id = 'AhQ3wG3nE8E';   break;
				case 'be' : $warranty_youtube_id = 'AhQ3wG3nE8E';   break;

				default   : $warranty_youtube_id = 'upv2j5oA7jM';
			}

			$data['warranty_youtube_id'] = $warranty_youtube_id;
			$this->load_tpl('content', $page, "", TRUE);
		}
		elseif ($page == 'shipping')
		{
			#SBF #4020 - get time frames for default scenario
			$data["ship_days"] = $data["del_days"] = "";
			if($delivery_obj = $this->deliverytime_service->get_deliverytime_obj(PLATFORMCOUNTRYID, 1))
			{
				$data["ship_days"] = $delivery_obj->get_ship_min_day()." - ".$delivery_obj->get_ship_max_day();
				$data["del_days"] = $delivery_obj->get_del_min_day()." - ".$delivery_obj->get_del_max_day();
			}

			// if cannot get delivery obj info, go back to original hard coded days
			$data["lang_id"] = $lang_id = strtolower($_SESSION["lang_id"]);
			if(!$data["ship_days"])
			{
				switch ($lang_id)
				{
					case 'en':
					case 'fr':
					case 'it':
						$data["ship_days"] = "2 - 4";
						break;

					case 'es':
						$data["ship_days"] = "3 - 6";
						break;

					case 'ru':
						$data["ship_days"] = "3 - 5";
						break;

					default:
						$data["ship_days"] = "2 - 4";
						break;
				}
			}
			if(!$data["del_days"])
			{
				switch ($lang_id)
				{
					case 'en':
					case 'es':
					case 'fr':
					case 'it':
						$data["del_days"] = "6 - 9";
						break;

					case 'ru':
						$data["del_days"] = "6 - 26";
						break;

					default:
						$data["del_days"] = "6 - 9";
						break;
				}
			}

			$this->load_tpl('content', $page, $data, TRUE);
		}
		else
		{
			$this->load_tpl('content', $page, "", TRUE);
		}
	}
}
?>