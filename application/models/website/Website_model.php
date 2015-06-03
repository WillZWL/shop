<?php
class Website_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('service/website_service');
		$this->load->library('service/customer_service_info_service');
	}

	public function get_cat_url($cat_id, $relative_path = FALSE)
	{
		return $this->website_service->get_cat_url($cat_id, $relative_path);
	}

	public function get_prod_url($sku, $relative_path = FALSE)
	{
		return $this->website_service->get_prod_url($sku, $relative_path);
	}

	public function get_csi_list($where = array(), $option = array())
	{
		return $this->customer_service_info_service->get_list($where, $option);
	}

	public function get_cs_contact_list_by_country($where = array())
	{
		return $this->customer_service_info_service->get_cs_contact_list_by_country($where);
	}

	public function get_js_kayako_enquiry_question($title, $lang_id = 'EN')
	{
		$enquiry_title = $title;
		if (strtoupper($lang_id) == 'FR')
		{
			$enquiry_type = array('Client Support'=>17, 'Pre-Sales'=>16, 'Returns'=>18, 'Bulk'=>33);
		}
		elseif (strtoupper($lang_id) == 'ES')
		{
			$enquiry_type = array('Client Support'=>28, 'Pre-Sales'=>27, 'Returns'=>29, 'Bulk'=>34);
		}
		elseif (strtoupper($lang_id) == 'IT')
		{
			$enquiry_type = array('Client Support'=>36, 'Pre-Sales'=>35, 'Returns'=>37);
		}
		elseif (strtoupper($lang_id) == 'RU')
		{
			$enquiry_type = array('Client Support'=>39, 'Pre-Sales'=>38, 'Returns'=>40);
		}
		elseif (PLATFORMCOUNTRYID == "PL")
		{
			$enquiry_type = array('Client Support'=>42, 'Pre-Sales'=>41, 'Returns'=>43);
		}
		else
		{
			$enquiry_type = array('Client Support'=>9, 'Pre-Sales'=>10, 'Returns'=>11, 'GE PH'=>31, 'Bulk'=>32);
		}

		$this->load->library('service/kayako_service');

		$js = 'var lang_id = "' . strtoupper($lang_id) . '";';
		$js .= 'var arr_data = new Array();';
		foreach ($enquiry_type as $type=>$custom_field_id)
		{
			$xml = $this->kayako_service->get_custom_field_option_xml($custom_field_id);
			$country_xml = $this->kayako_service->get_custom_field_option_xml($custom_field_id+35);
			//print_r($country_xml);
			if ($xml !== FALSE)
			{
				$tmp_arr = array();
				foreach ($xml as $attribute)
				{
					$tmp_arr['displayorder' . substr('0000000000' . (string) $attribute['displayorder'], -10)] = array('custom_field_option_id'=>(string) $attribute['customfieldoptionid'], 'question'=>(string) $attribute['optionvalue']);
				}
				ksort($tmp_arr);
				$con_tmp_arr = array();
				foreach ($country_xml as $attribute)
				{
					$con_tmp_arr[] = array('custom_field_option_id'=>(string) $attribute['customfieldoptionid'], 'country'=>(string) $attribute['optionvalue']);
				}

				$js .= 'arr_data["' . $type . '"] = new Array();' . "\r\n";
				$js .= 'arr_data["' . $type . '"]["title"] = "' . $enquiry_title[$type] . '";' . "\r\n";
				$js .= 'arr_data["' . $type . '"]["question"] = [];' . "\r\n";
				if($lang_id == 'en')
				{
					$js .= 'arr_data["' . $type . '"]["country"] = [];' . "\r\n";
					foreach ($con_tmp_arr as $country)
					{
						$js .= 'arr_data["' . $type . '"]["country"][' . json_encode($country['custom_field_option_id']) . '] = ' . json_encode($country['country']) . ';' . "\r\n";
					}
				}
				foreach ($tmp_arr as $question)
				{
					$js .= 'arr_data["' . $type . '"]["question"][' . json_encode($question['custom_field_option_id']) . '] = ' . json_encode($question['question']) . ';' . "\r\n";
				}
			}
		}
		return $js;
	}

	public function process_enquiry($enquiry_service, $data, $file, $lang_id = 'EN')
	{
		if ($enquiry_service == 'kayako')
		{
			$this->load->library('service/kayako_service');
			$kayako = new Kayako_service();
			$kayako->set_department($data['enquiry_type']);
			$kayako->set_default_ticket_info();
			$kayako->set_language($lang_id);
			$result = $kayako->create_ticket($data, $file);
			if ($result === FALSE)
			{
				return $kayako->get_rest_error();
			}
			else
			{
				return $result;
			}
		}
	}
}
/* End of file website_model.php */
/* Location: ./app/models/website/website_model.php */