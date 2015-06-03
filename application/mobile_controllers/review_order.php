<?php
class Review_order extends MOBILE_Controller
{

	public function Review_order()
	{
		parent::MOBILE_Controller(array("template" => "default"));
		$this->load->helper(array('url'));
		$this->load->model('website/common_data_prepare_model');
		$this->load->model('website/cart_session_model');
		$this->load->model('website/checkout_model');
		$this->template->add_js('/js/checkform.js');
	}

	public function index()
	{
		$data = $this->common_data_prepare_model->get_data_array($this);
/*
		$data['lang_text'] = $this->get_language_file('', '', 'index');
		$item_status = $this->input->get('item_status');
		$removed_sku = $this->input->get('not_valid_sku');
		$need_restriction_message = false;

		if (isset($item_status) && isset($removed_sku))
		{
			require_once(BASEPATH . 'plugins/My_plugin/validator/digits_validator.php');
			$digits_validator_allow_empty = new Digits_validator(array("allow_empty" => true));

			require_once(BASEPATH . 'plugins/My_plugin/validator/regex_validator.php');
			$regex_validator = new Regex_validator(Regex_validator::REGX_SKU_FORMAT);

			if ($digits_validator_allow_empty->is_valid($item_status) && $regex_validator->is_valid($removed_sku))
			{
				if (($item_status == Cart_session_service::NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM)
					|| ($item_status == Cart_session_service::NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM)
					|| ($item_status == Cart_session_service::DIFFERENT_PREORDER_ITEM)
					|| ($item_status == Cart_session_service::DIFFERENT_ARRIVING_ITEM))
				{
					$need_restriction_message = true;
				}
			}
		}

		if (!$need_restriction_message)
		{
			$data['lang_text']['restriction'] = "";
		}

		if (isset($_POST["promotion_code"]))
		{
			if ($this->input->post("promotion_code"))
			{
				$_SESSION["promotion_code"] = $_POST["promotion_code"];
				$email = $this->input->post("email");
				$_SESSION["POSTFORM"]["email"] = $email;
				$this->cart_session_service->set_email($email);
			}
			else
			{
				unset($_SESSION["promotion_code"]);
			}
		}
		elseif (isset($_POST["del_country_id"]))
		{
			$_SESSION["POSTFORM"] = $_POST;
		}

		$result = $this->cart_session_model->get_detail(PLATFORMID);
		$item_total = 0;

		// SBF #2236, GST checking
		$need_gst_display = FALSE;
		if (PLATFORMCOUNTRYID == 'NZ')
		{
			$need_gst_display = TRUE;
		}

		$gst_total = 0;
		$gst_order = FALSE;
		if ($need_gst_display)
		{
			foreach($result["cart"] AS $key=>$val)
			{
				$gst_total += $val["gst"];
			}

			if ($gst_total > 0)
			{
				$gst_order = TRUE;
			}
		}

		$data["need_gst_display"] = $need_gst_display;
		$data["gst_order"] = $gst_order;

		foreach($result["cart"] AS $key=>$val)
		{
			$item_total += $val["total"];
			$data["cart"][$val["sku"]]["prod_name"] = $val["name"];
			$data["cart"][$val["sku"]]["price"] = platform_curr_format(PLATFORMID, $val["price"]);
			$data["cart"][$val["sku"]]["qty"] = $val["qty"];
			$data["cart"][$val["sku"]]["increase_url"] = base_url()."review_order/update/".$val["sku"]."/".($val["qty"]+1);
			if($val["qty"]-1 > 0)
			{
				$data["cart"][$val["sku"]]["decrease_url"] = base_url()."review_order/update/".$val["sku"]."/".($val["qty"]-1);
			}
			$data["cart"][$val["sku"]]["sub_total"] = platform_curr_format(PLATFORMID, $val["total"]);
			$data["cart"][$val["sku"]]["remove_url"] = base_url()."review_order/remove/".$val["sku"];
			$data["cart"][$val["sku"]]["prod_url"] = $this->cart_session_model->get_prod_url($val["sku"]);
		}
		$data["delivery_charge"] = platform_curr_format(PLATFORMID, $result["dc_default"]["charge"]);
		$data["item_amount"] = platform_curr_format(PLATFORMID, $item_total);
		$data["gst_total"] = platform_curr_format(PLATFORMID, $gst_total);
		$total = $item_total - $result["dc_default"]["charge"]*1;
		if($result['promo']['disc_amount'])
		{
			$total = $item_total - $result['promo']['disc_amount'];
			if ($result['promo']['disc_amount'] > 0)
			{
				$result['promo']['display_disc_amount'] = "-" . platform_curr_format(PLATFORMID, $result['promo']["disc_amount"]);
			}
		}
		$data["total"] = platform_curr_format(PLATFORMID, $total + $gst_total);
		$data['promo'] = $result['promo'];

		foreach($result["cart"] as $key=>$cart_obj)
		{
			$tracking_list["sku"] = $cart_obj["sku"];
			$tracking_list["unit_price"] = $cart_obj["price"];
			$tracking_list["currency"] = $cart_obj["product_cost_obj"]->get_platform_currency_id();
			$tracking_list["product_name"] = $cart_obj["name"];
			$tracking_list["qty"] = $cart_obj["qty"];
			$tracking_list["total"] = $cart_obj["total"];
			$tracking_products[] = $tracking_list;
		}
		$data["tracking_data"]["products"] = $tracking_products;
		$data["tracking_data"]["total_amount"] = $total + $gst_total;
*/
		if(!$data["cart"])
			$this->load_tpl('content', 'review_order_no_product', $data, TRUE, TRUE);
		else
			$this->load_tpl('content', 'review_order', $data, TRUE, TRUE);
	}
/*************************************
*	we don't use remove.ini, because there is no template file, use index.ini directly instead
***************************************/
	public function remove($sku = "")
	{
		if($sku != "")
		{
			$this->cart_session_model->remove($sku, PLATFORMID);
		}
		$this->index();
	}
/*************************************
*	we don't use update.ini, because there is no template file, use index.ini directly instead
***************************************/
	public function update($sku ="", $qty="")
	{
		$allow_result = $this->cart_session_model->cart_session_service->is_allow_to_add($sku, 1, PLATFORMID);
		if ($allow_result <= Cart_session_service::DECISION_POINT)
		{
			if($sku != "" && $qty != "")
			{
				$this->cart_session_model->update($sku, $qty, PLATFORMID);
			}
			$this->index();
		}
		else
		{
			redirect(base_url() . "review_order?item_status=" . $allow_result . "&not_valid_sku=" . $sku);
		}
	}
}

/* End of file review_order.php */
/* Location: ./app/public_controllers/review_order.php */