<?php
class Inventory_adjustment extends MY_Controller
{

	private $app_id="SUP0004";
	private $lang_id="en";


	public function __construct()
	{
		parent::__construct();
		$this->load->model('supply/inventory_adjustment_model');
		$this->load->helper(array('url', 'notice', 'object', 'operator'));
		$this->load->library('service/pagination_service');
	}

	public function index()
	{
		$sub_app_id = $this->_get_app_id()."00";

		$_SESSION["LISTPAGE"] = base_url()."supply/inventory_adjustment/?".$_SERVER['QUERY_STRING'];

		if ($this->input->post("posted"))
		{
			unset($_SESSION["NOTICE"]);

			if ($this->input->post("cmd") == "add")
			{
				if (isset($_SESSION["inv_mov_vo"]))
				{
					$this->inv_movement_service->include_vo();
					$inv_mov_obj = unserialize($_SESSION["inv_mov_vo"]);
					$prod_sku = $_POST["sku"];
					$inv_mov_obj->set_sku($prod_sku);
					$proc = $this->inventory_service->get(array("prod_sku"=>$prod_sku, "warehouse_id"=>$_POST["to_location"]));
					if (!empty($proc))
					{
						$_SESSION["NOTICE"] = "prod_sku_exists";
					}
					else
					{
						if (!$this->product_service->get(array("sku"=>$_POST["sku"])))
						{
							$_SESSION["NOTICE"] = "sku_not_exists";
						}
					}

					if(empty($_SESSION["NOTICE"]))
					{
						set_value($inv_mov_obj, $_POST);
						$inv_mov_obj->set_type("W");
						$inv_mov_obj->set_status("AI");
						$inv_mov_obj->set_sku(strtoupper($inv_mov_obj->get_sku()));
						if ($this->inv_movement_service->insert($inv_mov_obj))
						{
							unset($_SESSION["courier_obj"]);
							redirect(base_url()."supply/inventory_adjustment/?prod_sku={$prod_sku}&warehouse_id={$_POST["to_location"]}&search=1");
						}
						else
						{
							$_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
						}
					}
				}
			}
			else
			{
				$shownotice = 0;
				if (isset($_SESSION["inv_mov_vo"]) && $_POST["check"])
				{
					$this->inv_movement_service->include_vo();
					$inv_mov_vo = unserialize($_SESSION["inv_mov_vo"]);
					foreach ($_POST["check"] as $prod_sku=>$whlist)
					{
						foreach ($whlist as $warehouse_id=>$value)
						{
							$success = 1;
							$inv_mov_obj = clone $inv_mov_vo;
							$inv_mov_obj->set_type('W');
							$inv_mov_obj->set_sku($prod_sku);
							if ($_POST["adj"][$prod_sku][$warehouse_id] == "I")
							{
								$inv_mov_obj->set_to_location($warehouse_id);
								$inv_mov_obj->set_status("AI");
							}
							else
							{
								$inv_mov_obj->set_from_location($warehouse_id);
								$inv_mov_obj->set_status("AO");
							}
							$inv_mov_obj->set_qty($_POST["qty"][$prod_sku][$warehouse_id]);
							$inv_mov_obj->set_reason($_POST["reason"]);
							if (!$this->inv_movement_service->insert($inv_mov_obj))
							{
								$shownotice = 1;
								$success = 0;
								$error = __LINE__.$this->db->_error_message();
							}
							$rsresult .= "{$prod_sku}({$warehouse_id}) -> {$success}".($success?"":" (error:{$error})")."\\n";
						}
					}
				}
				if ($shownotice)
				{
					$_SESSION["NOTICE"] = $rsresult;
				}
				redirect(current_url()."?".$_SERVER['QUERY_STRING']);
			}
		}

		$where = array();
		$option = array();

		if ($this->input->get("prod_sku") != "")
		{
			$where["prod_sku LIKE "] = "%".$this->input->get("prod_sku")."%";
		}
		if ($this->input->get("warehouse_id") != "")
		{
			$where["warehouse_id"] = $this->input->get("warehouse_id");
		}
		if ($this->input->get("git") != "")
		{
			fetch_operator($where, "git", $this->input->get("git"));
		}
		if ($this->input->get("inventory") != "")
		{
			fetch_operator($where, "inventory", $this->input->get("inventory"));
		}

		$sort = $this->input->get("sort");
		$order = $this->input->get("order");

		$limit = '20';

		$pconfig['base_url'] = $_SESSION["LISTPAGE"];
		$option["limit"] = $pconfig['per_page'] = $limit;
		if ($option["limit"])
		{
			$option["offset"] = $this->input->get("per_page");
		}

		if (empty($sort))
		{
			$sort = "sku";
		}

		if (empty($order))
		{
			$order = "asc";
		}

		$option["orderby"] = $sort." ".$order;

		if ($this->input->get("search"))
		{
			$data["objlist"] = $this->inventory_service->get_dao()->get_list_w_prod_name($where, $option);
			$data["total"] = $this->inventory_service->get_num_rows($where);
		}
		$data["whlist"] = $this->warehouse_service->get_list(array(), array("limit"=>-1));

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		$pconfig['total_rows'] = $data['total'];
		$this->pagination_service->set_show_count_tag(TRUE);
		$this->pagination_service->initialize($pconfig);

		$data["notice"] = notice($lang);

		$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
		$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
		$data["searchdisplay"] = "";


		if (empty($_SESSION["inv_mov_vo"]))
		{
			if (($inv_mov_vo = $this->inv_movement_service->get()) === FALSE)
			{
				$_SESSION["NOTICE"] = $this->db->_error_message();
			}
			else
			{
				$_SESSION["inv_mov_vo"] = serialize($inv_mov_vo);
			}
		}

		$this->load->view('supply/inventory_adjustment/inventory_adjustment_index_v', $data);
	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}

/* End of file inventory_adjustment.php */
/* Location: ./system/application/controllers/inventory_adjustment.php */