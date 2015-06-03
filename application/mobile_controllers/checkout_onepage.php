<?php
DEFINE ('ALLOW_REDIRECT_DOMAIN', 0);

include_once(APPPATH . "public_controllers/checkout_redirect_method.php");
class Checkout_onepage extends Checkout_redirect_method
{
	const STEP_LOGIN = "login";
	const STEP_BILLING_INFORMATION = "billing";
	const STEP_SHIPPING_INFORMATION = "shipping";
	const STEP_PAYMENT_INFORMATION = "payment";

	const MAXIMUM_STEP = STEP_PAYMENT_INFORMATION;
	private $block_list = array("login"=>"login", "billing"=>"billing", "shipping"=>"shipping", "payment"=>"payment");

	private $_step;
	private $_current_step;

	public function __construct()
	{
		parent::Checkout_redirect_method();
		$this->load->library('service/context_config_service');
		$this->load->library('service/price_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/delivery_service');
		$this->load->model('website/checkout_model');
		$this->load->model('website/common_data_prepare_model');

		$this->load->library('service/affiliate_service');	# SBF#1986
	}

	public function index()
	{
		$data = $this->common_data_prepare_model->get_data_array($this);

		$this->load_tpl('content', 'checkout/checkout_main_content', $data, TRUE);
	}

	public function set_current_step($step)
	{
		$this->_current_step = $step;
	}

	public function get_current_step()
	{
		return $this->_current_step;
	}

	public function get_header()
	{
		$this->load_template("template/checkout/checkout_onepage.php", $data);
	}

	public function getBlockStyle()
	{
		$blockStyle = array();

		$first_allow_list = $this->getAllowList($this->_current_step);
		$display_block = $this->getDisplayBlocks();
		foreach($this->block_list as $key => $value)
		{
			if (in_array($key, $first_allow_list) && in_array($key, $display_block))
				$blockStyle[$key] = "section allow";
			else if (in_array($key, $display_block))
				$blockStyle[$key] = "section";
			else
				$blockStyle[$key] = "hide";
		}
		return $blockStyle;
	}

	public function getAllowList($current_step)
	{
		$allow_list = array();
		foreach($this->block_list as $key => $value)
		{
			if ($value == $current_step)
				break;
			$allow_list[] = $value;
		}
		return $allow_list;
	}

	public function get_next_step($current_step)
	{
		$needReturn = false;
		$processing_block_list = $this->getDisplayBlocks();
		if (Checkout_onepage::MAXIMUM_STEP != $current_step)
		{
			foreach ($processing_block_list as $value)
			{
				if ($value == $current_step)
				{
					$needReturn = true;
					continue;
				}
				if ($needReturn)
					return $value;
			}
		}
		else
			return "";
	}

	private function _displayBlock($key)
	{
		if ((PLATFORMID == "WEBPH") && ($key == "billing"))
			return false;
		else
			return true;
	}

	public function getDisplayBlocks()
	{
		$displayBlock = array();
		foreach($this->block_list as $key => $value)
		{
			if (!$this->_displayBlock($key))
				continue;
			$displayBlock[] = $value;
		}
		return $displayBlock;
	}

	public function getAllowLogin()
	{
		return $this->_displayBlock("billing");
	}

	public function getDisplayBlockList()
	{
		$hiddenList = "";
		foreach($this->block_list as $key => $value)
		{
			if (!$this->_displayBlock($key))
				continue;
			if ($hiddenList == "")
				$hiddenList .= $value;
			else
				$hiddenList .= "," . $value;
		}
		return $hiddenList;
	}

	public function getBlockList()
	{
		$blockList = "";
		foreach($this->block_list as $key => $value)
		{
			if ($blockList == "")
				$blockList .= $value;
			else
				$blockList .= "," . $value;
		}
		return $blockList;
	}

	public function getBlockTitle($step)
	{
		return $this->block_list[$step];
	}

    public function getSteps()
    {
        $steps = array();
        return $steps;
    }

	public function is_same_listing_changing_country($new_country_id)
	{
		$this->common_data_prepare_model->get_data_array($this);
	}

	public function calculate_delivery_surcharge($country_id = '', $postcode = '')
	{
		return $this->common_data_prepare_model->get_data_array($this, array("country_id" => $country_id, "postcode" => $postcode));
	}

	public function ajax_cal_del_surcharge($country_id = '', $postcode = '')
	{
		$this->common_data_prepare_model->get_data_array($this, array("country_id" => $country_id, "postcode" => $postcode));
	}
}
/* End of file checkout_one_page.php */
/* Location: ./app/public_controllers/checkout_onepage.php */
