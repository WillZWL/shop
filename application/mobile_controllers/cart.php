<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

Class Cart extends MOBILE_Controller
{
	public function Cart()
	{
		parent::MOBILE_Controller(array("template" => "default"));
		$this->load->helper(array('url'));
		$this->load->model('website/common_data_prepare_model');
		$this->load->model('website/cart_session_model');
		$this->load->model('marketing/upselling_model');
		$this->load->library('service/affiliate_service');
	}

	public function add_item($sku = "")
	{
		$this->add_item_qty($sku, 1);
	}

	public function checkout_item($sku = "", $nextpage="checkout")
	{
		if (empty($sku))
		{
			$sku = $_REQUEST["sku"];
		}

		if (!empty($sku))
		{
			if (!($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORMID)))
			{
				show_error("Product not found", "Product not found");
			}
		}
		else
		{
			show_404();
		}
		if ($nextpage == "prod")
		{
			$prod_obj = $this->product_model->get("product", array("sku"=>$sku));
			redirect(base_url().str_replace(" ", "-", parse_url_char($prod_obj->get_name()))."/"."mainproduct/view/".$sku);
		}
		else
		{
			redirect(base_url()."review_order");
		}
	}

	public function ajax_add_cart($parent_sku = "", $sku = "")
	{
		$data['data']['lang_text'] = $this->_get_language_file();

		$success = 0;
		$prod_obj = $this->product_model->get("product", array("sku"=>$sku));
		if(!$prod_cont_obj = $this->product_model->get_product_content(array("prod_sku"=>$sku, "lang_id"=>get_lang_id())))
		{
			$prod_cont_obj = $this->product_model->get_product_content(array("prod_sku"=>$sku, "lang_id"=>"en"));
		}

		if($prod_cont_obj)
		{
			$prod_name = $prod_cont_obj->get_prod_name();
		}
		else
		{
			$prod_name = $prod_obj->get_name();
		}

		$image_url = get_image_file($prod_obj->get_image(), "m", $sku);

		if (!empty($sku))
		{
			if ($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORMID))
			{
				$success = 1;
			}
		}

		$cart_info = $this->cart_session_model->get_detail(PLATFORMID);
		$cart["total"] = $cart["item"] = 0;
		if($cart_info["cart"])
		{
			foreach($cart_info["cart"] AS $key=>$arr)
			{
				$cart["total"] += $arr["total"];
				$cart["item"] += $arr["qty"];
			}
		}
		$cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);

		if($success)
		{
			$_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku] = 1;
			$add_text = $data['data']['lang_text']['cart_add'];
			echo $add_text."||".$prod_name."||".base_cdn_url().$image_url."||".$cart["total"]."||".$cart["item"];
		}
		else
		{
			$not_avail_text = $data['data']['lang_text']['cart_not_avail'];
			echo $not_avail_text."||".$prod_name."||".base_cdn_url().$image_url."||".$cart["total"]."||".$cart["item"];
		}
	}

	public function ajax_update_warranty($parent_sku = "", $sku = "")
	{
		$data['data']['lang_text'] = $this->_get_language_file();

		$success = 0;
		$prod_obj = $this->product_model->get("product", array("sku"=>$sku));

		if(empty($sku) || isset($_SESSION["warranty"][PLATFORMID][$parent_sku]))
		{
			$old_warranty = $_SESSION["warranty"][PLATFORMID][$parent_sku];

			if (!empty($old_warranty))
			{
				$cur_cart_qty = $_SESSION["cart"][PLATFORMID][$old_warranty];
				$new_qty = $cur_cart_qty*1 - 1;
				if($new_qty <= 0)
				{
					if ($chk_cart = $this->cart_session_model->remove($old_warranty))
					{
						$success = 1;
					}
				}
				else
				{
					if ($chk_cart = $this->cart_session_model->update($old_warranty, $new_qty, PLATFORMID))
					{
						$success = 1;
					}
				}
			}
		}

		if (!empty($sku))
		{
			if ($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORMID))
			{
				$success = 1;
			}
		}

		$cart_info = $this->cart_session_model->get_detail(PLATFORMID);
		$cart["total"] = $cart["item"] = 0;
		if($cart_info["cart"])
		{
			foreach($cart_info["cart"] AS $key=>$arr)
			{
				$cart["total"] += $arr["total"];
				$cart["item"] += $arr["qty"];
			}
		}
		$cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);

		if($success)
		{
			$_SESSION["warranty"][PLATFORMID][$parent_sku] = $sku;
			echo $sku;
			//echo $sku."||".$prod_obj->get_name();
			//echo $add_text."||".$prod_obj->get_name()."||".base_cdn_url().$image_url."||".$cart["total"]."||".$cart["item"];
		}
	}

	public function ajax_rm_cart($parent_sku = "", $sku = "")
	{
		$data['data']['lang_text'] = $this->_get_language_file();

		$success = 0;
		$prod_obj = $this->product_model->get("product", array("sku"=>$sku));
		if(!$prod_cont_obj = $this->product_model->get_product_content(array("prod_sku"=>$sku, "lang_id"=>get_lang_id())))
		{
			$prod_cont_obj = $this->product_model->get_product_content(array("prod_sku"=>$sku, "lang_id"=>"en"));
		}

		if($prod_cont_obj)
		{
			$prod_name = $prod_cont_obj->get_prod_name();
		}
		else
		{
			$prod_name = $prod_obj->get_name();
		}
		$image_url = get_image_file($prod_obj->get_image(), "m", $sku);

		if (!empty($sku))
		{
			if(is_array($_SESSION["cart"][PLATFORMID][$sku]))
			{
				$cur_cart_qty = $_SESSION["cart"][PLATFORMID][$sku]["qty"];
			}
			else
			{
				$cur_cart_qty = $_SESSION["cart"][PLATFORMID][$sku];
			}
			$new_qty = $cur_cart_qty*1 - 1;
			if($new_qty <= 0)
			{
				if ($chk_cart = $this->cart_session_model->remove($sku))
				{
					$success = 1;
				}
			}
			else
			{
				if ($chk_cart = $this->cart_session_model->update($sku, $new_qty, PLATFORMID))
				{
					$success = 1;
				}
			}
		}

		$cart_info = $this->cart_session_model->get_detail(PLATFORMID);
		$cart["total"] = $cart["item"] = 0;
		if($cart_info["cart"])
		{
			foreach($cart_info["cart"] AS $key=>$arr)
			{
				$cart["total"] += $arr["total"];
				$cart["item"] += $arr["qty"];
			}
		}
		$cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);

		if($success)
		{
			if(isset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku]))
			{
				unset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku]);
			}
			$remove_text = $data['data']['lang_text']['cart_remove'];
			echo $remove_text."||".$prod_name."||".base_cdn_url().$image_url."||".$cart["total"]."||".$cart["item"];
		}
		else
		{
			if(isset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku]))
			{
				unset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku]);
			}
			$remove_text = $data['data']['lang_text']['cart_remove'];
			echo $remove_text."||".$prod_name."||".base_cdn_url().$image_url."||".$cart["total"]."||".$cart["item"];
		}
	}

	public function add_item_qty($sku = "", $qty = 0)
	{
		$this->common_data_prepare_model->get_data_array($this, array("sku" => $sku, "qty" => $qty));
	}
}

/* End of file cart.php */
/* Location: ./app/public_controllers/cart.php */