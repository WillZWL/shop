<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

$ws_array = array(NULL, 'index', 'rma', 'rma_confirm', 'rma_edit', 'rma_print', 'update_password');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array))
{
	DEFINE ('PLATFORM_TYPE', 'SKYPE');
}

class Myaccount extends MOBILE_Controller
{
	private $lang_id = "en";
	private $components_list;

	public function Myaccount()
	{
		parent::MOBILE_Controller(array('require_login'=>1, 'load_header'=>1, 'template'=>'default'));
		$this->load->helper(array('url', 'object', 'notice', 'tbswrapper'));
		$this->load->model('website/client_model');
		$this->load->model('order/so_model');
		$this->load->model('mastercfg/courier_model');
		$this->load->model('mastercfg/country_model');
		$this->load->model('website/common_data_prepare_model');
		$this->load->library('template');
		$this->load->library('encrypt');
		$this->load->library('service/region_service');
		$this->load->library('service/client_service');
		$this->load->library('service/event_service');
		$this->load->library('service/pdf_rendering_service');
		$this->load->library('service/courier_service');
		$this->template->add_js("/js/checkform.js");
//		$this->template->add_js("/myaccount/rma_addr_js");
	}

	public function index($page = "order", $rma_no = "")
	{
		$data = $this->common_data_prepare_model->get_data_array($this, array('page'=>$page, 'rma_no'=>$rma_no));

		//
		$profile["active"] = '';
		$profile["block"] = '';
		$rma["active"] = '';
		$rma["block"] = '';
		$order["active"] = '';
		$order["block"] = '';

		if($page == "profile")
		{
			$profile["active"] = 'class="active"';
			$profile["block"] = 'style="display:block;"';
		}
		elseif($page == "rma")
		{
			$rma["active"] = 'class="active"';
			$rma["block"] = 'style="display:block;"';
		}
		else
		{
			$order["active"] = 'class="active"';
			$order["block"] = 'style="display:block;"';
		}

		$data['profile'] = $profile;
		$data['rma'] = $rma;
		$data['order'] = $order;

		// order history
		$orderlist = array();
		if($data["orderlist"])
		{
			$i = 0;
			foreach($data["orderlist"] AS $so_no=>$order_arr)
			{
				$orderlist[$i]["so_no"] = $so_no;
				$orderlist[$i]["client_id"] = $order_arr["client_id"];
				$orderlist[$i]["order_date"] = $order_arr["order_date"];
				$orderlist[$i]["delivery_name"] = $order_arr["delivery_name"];
				$orderlist[$i]["order_status"] = $data['lang_text'][$order_arr["order_status_ini"]];
				$orderlist[$i]["print_invoice_html"] = "";
				if($order_arr["is_shipped"])
				{
					$orderlist[$i]["print_invoice_html"] = '<br /><a href="' . base_url() . 'myaccount/print_invoice/' . $so_no . '" target="_blank" style="font-size:10px;"><u>' . $data['lang_text']['print_invoice'] . '</u></a>';
				}

				switch($order_arr["status_desc_ini"])
				{
					case "shipped_w_tracking_desc":
						$orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"]."_1"] . $order_arr["courier_name"] . $data['lang_text'][$order_arr["status_desc_ini"]."_2"] . '<a href="' . $order_arr["tracking_link"]->get_tracking_link() . $order_arr["tracking_number"] . '" target="_vb">' . $order_arr["tracking_number"] . '</a>';
						break;
					case "shipped_wo_tracking_desc":
						$orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"]."_1"] . $order_arr["courier_name"] . $data['lang_text'][$order_arr["status_desc_ini"]."_2"];
						break;
					default:
						$orderlist[$i]["status_desc"] = $data['lang_text'][$order_arr["status_desc_ini"]];
				}
				$orderlist[$i]["product_name"] = $order_arr["product_name"];
				$orderlist[$i]["total_amount"] = platform_curr_format($order_arr["platform_id"], $order_arr["total_amount"]);
				$i++;
			}
		}
		$data['orderlist'] = $orderlist;

		$data["partial_ship_text"] = "";
		if($data["show_partial_ship_text"] === TRUE)
		{
			$data["partial_ship_text"] = $data["lang_text"]["partial_ship_1"];
		}

		//
		$title[0]["value"] = $data['lang_text']['title_mr'];
		$title[1]["value"] = $data['lang_text']['title_mrs'];
		$title[2]["value"] = $data['lang_text']['title_miss'];

		$title[0]["value_EN"] = "Mr";
		$title[1]["value_EN"] = "Mrs";
		$title[2]["value_EN"] = "Miss";

		if($data['lang_id'] != 'es'){
			$title[3]['value'] = $data['lang_text']['title_dr'];
			$title[3]["value_EN"] = "Dr";
		}

		foreach($title AS $key=>$value)
		{
			if($title[$key]["value_EN"] == $data["client_obj"]->get_title())
			{
				$title[$key]["selected"]="SELECTED";
			}
			else
			{
				$title[$key]["selected"]="";
			}
		}
		$data['title'] = $title;

		//
		$bill_country_arr = array();
		if($data["bill_to_list"])
		{
			$i = 0;
			foreach($data["bill_to_list"] AS $cobj)
			{
				$bill_country_arr[$i]["id"] = $cobj->get_id();
				$bill_country_arr[$i]["display_name"] = $cobj->get_lang_name();
				if($data["client_obj"]->get_country_id() == $cobj->get_id())
				{
					$bill_country_arr[$i]["selected"] = "SELECTED";
				}
				else
				{
					$bill_country_arr[$i]["selected"] = "";
				}
				$i++;
			}
		}
		$data['bill_country_arr'] = $bill_country_arr;

		// edit profile
		$client_obj["email"] = $data["client_obj"]->get_email();
		$client_obj["forename"] = $data["client_obj"]->get_forename();
		$client_obj["surname"] = $data["client_obj"]->get_surname();
		$client_obj["companyname"] = $data["client_obj"]->get_companyname();
		$client_obj["address_1"] = $data["client_obj"]->get_address_1();
		$client_obj["address_2"] = $data["client_obj"]->get_address_2();
		$client_obj["city"] = $data["client_obj"]->get_city();
		$client_obj["state"] = $data["client_obj"]->get_state();
		$client_obj["postcode"] = $data["client_obj"]->get_postcode();
		$client_obj["tel_1"] = $data["client_obj"]->get_tel_1();
		$client_obj["tel_2"] = $data["client_obj"]->get_tel_2();
		$client_obj["tel_3"] = $data["client_obj"]->get_tel_3();
		$client_obj["subscriber"] = $data["client_obj"]->get_subscriber()?"CHECKED":'';
		$data['client_obj'] = $client_obj;

		//
		$bill_country_arr2 = array();
		if($data["bill_to_list"])
		{
			$i = 0;
			foreach($data["bill_to_list"] AS $cobj)
			{
				$bill_country_arr2[$i]["id"] = $cobj->get_id();
				$bill_country_arr2[$i]["display_name"] = $cobj->get_lang_name();
				if($data["rma_obj"]->get_country_id() == $cobj->get_id())
				{

					$bill_country_arr2[$i]["selected"] = "SELECTED";
				}
				else
				{
					$bill_country_arr2[$i]["selected"] = "";
				}
				$i++;
			}
		}
		$data['bill_country_arr2'] = $bill_country_arr2;

		//
		$category[0]["key"] = 0;
		$category[1]["key"] = 1;
		$category[2]["key"] = 2;
		$category[0]["value"] = $data['lang_text']["machine_only"];
		$category[1]["value"] = $data['lang_text']["accessory_only"];
		$category[2]["value"] = $data['lang_text']["machine_and_accessory"];

		foreach($category AS $key=>$value)
		{
			if($data["rma_obj"]->get_category() == $key)
			{
				$category[$key]["selected"]="SELECTED";
			}
			else
			{
				$category[$key]["selected"]="";
			}
		}
		$data['category'] = $category;

		//
		$reason[0]["key"] = 0;
		$reason[1]["key"] = 1;
		$reason[2]["key"] = 2;
		$reason[3]["key"] = 3;
		$reason[0]["value"] = $data['lang_text']["need_repair"];
		$reason[1]["value"] = $data['lang_text']["wrong_product_deliver"];
		$reason[2]["value"] = $data['lang_text']["wrong_product_purchase"];
		$reason[3]["value"] = $data['lang_text']["accident_purchase"];
		foreach($reason AS $key=>$value)
		{
			if($data["rma_obj"]->get_reason() == $key)
			{
				$reason[$key]["selected"]="SELECTED";
			}
			else
			{
				$reason[$key]["selected"]="";
			}
		}
		$data['reason'] = $reason;

		//
		$action_request[0]["key"] = 0;
		$action_request[1]["key"] = 1;
		$action_request[2]["key"] = 2;
		$action_request[0]["value"] = $data['lang_text']["swap"];
		$action_request[1]["value"] = $data['lang_text']["refund"];
		$action_request[2]["value"] = $data['lang_text']["repair"];
		foreach($action_request AS $key=>$value)
		{
			if($data["rma_obj"]->get_action_request() == $key)
			{
				$action_request[$key]["selected"]="SELECTED";
			}
			else
			{
				$action_request[$key]["selected"]="";
			}
		}
		$data['action_request'] = $action_request;

		// rma
		$rma_obj["id"] = $data["rma_obj"]->get_id();
		$rma_obj["so_no"] = $data["rma_obj"]->get_so_no();
		$rma_obj["client_id"] = $data["rma_obj"]->get_client_id();
		$rma_obj["forename"] = $data["rma_obj"]->get_forename();
		$rma_obj["surname"] = $data["rma_obj"]->get_surname();
		$rma_obj["address_1"] = $data["rma_obj"]->get_address_1();
		$rma_obj["address_2"] = $data["rma_obj"]->get_address_2();
		$rma_obj["postcode"] = $data["rma_obj"]->get_postcode();
		$rma_obj["city"] = $data["rma_obj"]->get_city();
		$rma_obj["state"] = $data["rma_obj"]->get_state();
		$rma_obj["country_id"] = $data["rma_obj"]->get_country_id();
		$rma_obj["product_returned"] = $data["rma_obj"]->get_product_returned();
		$rma_obj["serial_no"] = $data["rma_obj"]->get_serial_no();
		$rma_obj["details"] = $data["rma_obj"]->get_details();
		$data['rma_obj'] = $rma_obj;

		//
		if($data["rma_confirm"])
		{
			$confirm_notice = "<p class='green'>";
			$confirm_notice .= $data['lang_text']["request_submit_notice_1"] . "<br>";
			$confirm_notice .=  $data['lang_text']["request_submit_notice_2_1"] . " <a href='" . base_url() .  "myaccount/rma_print/" . $data["rma_obj"]->get_id() . "'  target='rma_print' style='text-decoration:underline;'>" . $data['lang_text']["request_submit_notice_2_2"] . "</a> " . $data['lang_text']["request_submit_notice_2_3"];
			$confirm_notice .= "</p>";
			$confirm_notice .= "<p style='height:30px' class='clear'></p>";
			$agreed = "CHECKED";
			$disabled = "DISABLED";
			$select_box_style = "styled_select_disabled";
		}
		else
		{
			$confirm_notice = "";
			$agreed = "";
			$disabled = "";
			$select_box_style = "styled_select";
		}

		$data['confirm_notice'] = $confirm_notice;
		$data['agreed'] = $agreed;
		$data['disabled'] = $disabled;
		$data['select_box_style'] = $select_box_style;

		$this->load_tpl('content', 'myaccount', $data, TRUE, TRUE);
	}

	public function profile()
	{
		$this->load->model('mastercfg/country_model');
		$this->load->library('encrypt');

		$data = $this->common_data_prepare_model->get_data_array($this);
		$this->index("profile");
	}

	public function rma()
	{
		$data['display_id'] = 8;
		$this->load->model('order/so_model');

		unset($_SESSION["NOTICE"]);
		if ($this->input->post("posted"))
		{
			if (isset($_SESSION["rma_vo"]))
			{
				$this->so_model->include_vo("rma_dao");
				$data["rma_obj"] = unserialize($_SESSION["rma_vo"]);
				set_value($data["rma_obj"], $_POST);
				$data["rma_obj"]->set_client_id($_SESSION["client"]["id"]);
				$data["rma_obj"]->set_status(0);

				$start_pos = strpos($_POST["so_no"], '-');
				if($start_pos != NULL)
				{
					$so_no_trim = substr($_POST["so_no"],$start_pos+1);
				}
				else
				{
					$so_no_trim = $_POST["so_no"];
				}

				// consider cases with split order
				$start_splitpos = strpos($so_no_trim, '/');
				if($start_splitpos != NULL)
				{
					$so_no_trim_split = substr($so_no_trim,$start_pos+1);
				}
				else
				{
					$so_no_trim_split = $so_no_trim;
				}

				$proc = $this->so_model->get("dao", array("so_no"=>$so_no_trim_split,"client_id"=>$_SESSION["client"]["id"],"status"=>6));
				$data["rma_obj"]->set_so_no($so_no_trim_split);

				$data['data']['lang_text'] = $this->_get_language_file('', '', 'index');
				if (empty($proc))
				{
					$_SESSION["NOTICE"] = $data['data']['lang_text']['rma_warning'];
					$_SESSION["rma_obj"] = serialize($data["rma_obj"]);
				}
				else
				{
					if ($rma_obj = $this->so_model->add("rma_dao", $data["rma_obj"]))
					{
						$this->rma_confirm($rma_obj->get_id());
						return;
					}
					else
					{
						$_SESSION["NOTICE"] = "Error: ".__LINE__;
					}
				}
			}
		}

		$this->index("rma");
	}

	public function rma_confirm($rma_no = "")
	{
		$data["display_id"] = 8;
		$rma_obj = $this->so_model->get("rma_dao",array("id"=>$rma_no,"client_id"=>$_SESSION["client"]["id"]));
		if($rma_obj)
		{
			$this->index("rma", $rma_no);
			unset($_SESSION['rma_obj']);
		}
		else
		{
			Redirect("/myaccount/rma");
		}
	}

/*
no one use rma_edit, so do not change to single template for all language.
*/
	public function rma_edit($rma_id = "")
	{
		$data["display_id"] = 8;
		if($this->input->post("update"))
		{
			$this->so_model->include_vo("rma_dao");
			$rma_obj = unserialize($_SESSION["rma_vo"]);
			$components_list = $_POST["components"];
			for ($i=0; $i<14; $i++)
			{
				$components[$i] = $_POST["components"][$i]?1:0;
			}
			$components[$i-1] = $_POST["other"];
			set_value($rma_obj, $_POST);
			//$data["rma_obj"]->set_client_id($_SESSION["client"]["id"]);
			//$data["rma_obj"]->set_status(0);
			$rma_obj->set_components(implode('|', $components));

			$proc = $this->so_model->get("dao", array("so_no"=>$rma_obj->get_so_no(),"client_id"=>$rma_obj->get_client_id(),"status"=>6));
			$wn = array("en"=>"Update failed. Please try again or contact customer support if this repeats.",
						"de"=>"Update gescheitert. Bitte versuchen Sie es erneut oder kontaktieren Sie unseren Kundendienst.",
			"fr"=>"La mise ? jour a échoué. Merci d'essayer de nouveau ou contacter notre service clientèle si cela se produit de nouveau.",
			"es"=>"Actualización fallida. Por favor, inténtelo de nuevo o póngase en contacto con atención al cliente si esto se repite.",
			"pt"=>"Update failed. Please try again or contact customer support if this repeats.",
			"nl"=>"Update failed. Please try again or contact customer support if this repeats.",
			"ja"=>"Update failed. Please try again or contact customer support if this repeats.",
			"pl"=>"Update failed. Please try again or contact customer support if this repeats.",
			"it"=>"Update failed. Please try again or contact customer support if this repeats.",
			"da"=>"Update failed. Please try again or contact customer support if this repeats.",
			"ko"=>"Update failed. Please try again or contact customer support if this repeats.",
			"tr"=>"Update failed. Please try again or contact customer support if this repeats.",
			"sv"=>"Update failed. Please try again or contact customer support if this repeats.",
			"no"=>"Update failed. Please try again or contact customer support if this repeats.",
			"pt-br"=>"Update failed. Please try again or contact customer support if this repeats.",
			"ru"=>"Update failed. Please try again or contact customer support if this repeats.");
			if (empty($proc))
			{

				$_SESSION["NOTICE"] = $wn[get_lang_id()];
			}
			else
			{
				if ($this->so_model->update("rma_dao", $rma_obj) !== FALSE)
				{
					unset($_SESSION["NOTICE"]);
					Redirect("/myaccount/rma_confirm/".$rma_id);
				}
				else
				{
					echo $this->db->last_query()." ".$this->db->_error_message();
					$_SESSION["NOTICE"] = $wn[get_lang_id()];
				}
			}
		}
		$rma_obj = $this->so_model->get("rma_dao",array("id"=>$rma_id,"client_id"=>$_SESSION["client"]["id"]));
		if($rma_obj)
		{
			$data["rma_obj"] = $rma_obj;
			$data["notice"] = $_SESSION["NOTICE"];
			$_SESSION["rma_vo"] = serialize($rma_obj);
			$data["order"] = $this->so_model->get("dao",array("so_no"=>$rma_obj->get_so_no()));

			$data["components_list"] = $this->components_list[get_lang_id()];
			$this->load_view('myaccount/rma_edit_'.get_lang_id(),$data);
		}
		else
		{
			Redirect("/myaccount/rma");
		}
	}

	public function rma_print($rma_no = "")
	{
		$rma_obj = $this->so_model->get("rma_dao",array("id"=>$rma_no,"client_id"=>$_SESSION["client"]["id"]));
		if($rma_obj)
		{
			$data["rma_obj"] = $rma_obj;
			$country_obj = $this->country_model->country_service->get(array("id"=>$rma_obj->get_country_id()));
			$data["country_name"] = $country_obj->get_name();
			$data["order"] = $this->so_model->get("dao",array("so_no"=>$rma_obj->get_so_no()));
			$data["components_list"] = $this->components_list[get_lang_id()];
			$data['lang_text'] = $this->_get_language_file();
			$this->load_view('myaccount/rma_print', $data);
		}
		else
		{
			show_404();
		}
	}

	public function rma_addr_js()
	{
		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		$offset = 60 * 60 * 24;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);

		//$sell_to_list = $this->country_model->get_sell_to_list(get_lang_id());
		$sell_to_list = $this->country_model->get_rma_fc_list(get_lang_id());
		$carr = array();
		foreach($sell_to_list as $country)
		{
			$carr[] = "'".$country->get_id()."':['".strtolower(substr($country->get_fc_id(),0,2))."','".addslashes($country->get_name())."']";
		}

		$js = "var rmacountrylist = {".implode(",",$carr)."}";
		unset($carr);
		$js .= "

				function get_content(country_id)
				{

					t = country_id?rmacountrylist[country_id][0]:'';
					switch(t)
					{
						case 'us':
						addr = '<b>RMR Holdings</b><br />100 Executive Boulevard, Suite 101, <br />Ossining, New York, 10562<br />USA';
						break;

						case 'sg':
						addr = '<b>71 Ubi Crescent</b><br />Unit 04-09<br />Postal Code 408571<br />Singapore';
						break;

						case 'nz':
						addr = '<b>Plaza Level</b><br/>41 Shortland St<br>Auckland, 1010<br>New Zealand';
						break;

						case 'uk':
						addr = '<b>Dorchester House</b><br/Station Road<br>Letchworth,<br>SG6 3AW<br>United Kingdom';
						break;

						case 'au':
						addr = '<b>Level 5</b><br/>11 Queens Road<br/>Melbourne<br/>VIC 3004';
						break;

						case 'hk':
						addr = '<b>32/F Tower 1</b><br/>Millennium City 1<br/>388 Kwun Tong Road<br/>Kwun Tong<br/>Kowloon';
						break;

						default:
						addr = '<i>Please select your country first</i>';
						break;
					}

					if(addr)
					{
						$('#rmaaddr').html(addr);
					}
				}

				function draw_cl(select)
				{
					document.write(\"<option value=''></option>\");
					var selected = '';
					for(var i in rmacountrylist)
					{
						selected = select == i?'SELECTED':'';
						document.write('<option value='+i+' '+selected+'>'+rmacountrylist[i][1]+'</option>');
					}
				}
				";

				echo $js;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	function print_invoice($so_no = "")
	{
		$client_id = $_SESSION["client"]["id"];
		if(!$so_obj = $this->so_model->so_service->get(array("so_no"=>$so_no, "client_id"=>$client_id)))
		{
			show_404();
		}
		$html = $this->so_service->get_print_invoice_content(array($so_no), 1, get_lang_id());
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/MSIE/i',$u_agent))
		{
			// Instead of opening the PDF in browser, prompt user to download file if it's IE.
			$att_file = $this->pdf_rendering_service->convert_html_to_pdf($html, null, "D", "en");
		}
		else
		{
			$att_file = $this->pdf_rendering_service->convert_html_to_pdf($html, null, "I", "en");
		}
	}
}

/* End of file myaccount.php */
/* Location: ./app/public_controllers/myaccount.php */