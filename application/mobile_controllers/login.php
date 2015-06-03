<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Login extends MOBILE_Controller
{
	private $lang_id = "en";

	public function Login()
	{
		DEFINE("SKIPCUR", 1);
		parent::MOBILE_Controller(array('template'=>'default'));
		$this->load->helper(array('url', 'object', 'tbswrapper'));
		$this->load->model('website/client_model');
		$this->load->model('mastercfg/country_model');
		$this->load->model('website/common_data_prepare_model');
		$this->load->library('encrypt');
		$this->load->library('template');
	}

	public function checkout_login()
	{
		$data["back"] = $this->input->get("back");
		if ($this->input->post("posted"))
		{
			if ($_POST["password"])
			{
				if (!$this->client_model->client_service->login($_POST["email"], $_POST["password"]))
				{
					$_SESSION["NOTICE"] = $this->_get_fail_msg();
				}
			}
		}
		redirect(base_url() . urldecode($data["back"]));
	}

	public function index()
	{
		$data = $this->common_data_prepare_model->get_data_array($this);

		if ((array_key_exists('load_myaccount_page', $data)) && ($data['load_myaccount_page'] === TRUE))
		{
			$data['email'] = $this->input->post("page") ? "" : htmlspecialchars($this->input->post("email"));

			if($data["bill_to_list"])
			{
				$i = 0;
				foreach($data["bill_to_list"] AS $cobj)
				{
					$bill_country_arr[$i]["id"] = $cobj->get_id();
					$bill_country_arr[$i]["display_name"] = $cobj->get_lang_name();
					if($cobj->get_id() == PLATFORMCOUNTRYID)
					{
						$bill_country_arr[$i]["selected"] = "SELECTED";
					}
					else
					{
						$bill_country_arr[$i]["selected"] = "";
					}
					$i++;
				}

				if ($i > 0)
				{
					$data['bill_country_arr'] = $bill_country_arr;
				}
			}

			//these value are displayed to users according to the language_id
			$title[0]['value'] = $data['lang_text']['title_mr'];
			$title[1]['value'] = $data['lang_text']['title_mrs'];
			$title[2]['value'] = $data['lang_text']['title_miss'];

			//these value are storied into database, always english version
			$title[0]["value_EN"] = "Mr";
			$title[1]["value_EN"] = "Mrs";
			$title[2]["value_EN"] = "Miss";

			if($data['lang_id'] != 'es')
			{
				$title[3]['value'] = $data['lang_text']['title_dr'];
				$title[3]["value_EN"] = "Dr";
			}
			$data['title'] = $title;

			$this->load_tpl('content', 'login', $data, TRUE, TRUE);
		}
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	public function _check_login($value)
	{
		if (!$this->objResponse)
		{
			$this->load->library('xajax');
			$this->objResponse = new xajaxResponse();
		}
		if (!($rs = $this->client_model->client_service->login($value["email"], $value["password"])))
		{
			$this->objResponse->alert($this->_get_fail_msg());
		}
		$this->objResponse->setReturnValue($rs);

		return $this->objResponse;
	}

	public function _get_fail_msg()
	{
		$lfw = $this->common_data_prepare_model->get_data_array($this);
		return $lfw[get_lang_id()];
	}
}

/* End of file checkout.php */
/* Location: ./app/public_controllers/checkout.php */