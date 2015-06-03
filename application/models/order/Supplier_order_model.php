<?php

class Supplier_order_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/purchase_order_service');
		$this->load->library('service/product_service');
		$this->load->library('service/po_item_service');
		$this->load->library('service/po_item_shipment_service');
		$this->load->library('service/warehouse_service');
		$this->load->library('service/supplier_shipment_service');
		$this->load->library('service/inv_movement_service');
	}

	public function get_supplier_order_list_index($where=array(),$order=array())
	{
		$data["purchase_order_list"] =  $this->purchase_order_service->get_dao()->get_list_index($where,$order,"Po_supplier_name_dto");
		$data["total"] = $this->purchase_order_service->get_dao()->get_list_index($where,array("num_rows"=>1));

		return $data;
	}

	public function get_product_list($where=array(), $option=array())
	{
		return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
	}

	public function get_product_list_total($where=array(), $option=array())
	{
		$option["num_rows"] = 1;
		return $this->product_service->get_dao()->get_list_w_name($where, $option);
	}

	public function get_po($po_number="")
	{
		if($po_number == "")
		{
			return $this->purchase_order_service->get_dao()->get();
		}
		else
		{
			return $this->purchase_order_service->get_dao()->get(array("po_number"=>$po_number));
		}
	}

	public function get_po_item($where=array())
	{
		return $this->po_item_service->get_dao()->get($where);
	}

	public function get_po_item_list($po_number)
	{
		if($po_number == "")
		{
			return $this->get_po_item();
		}
		else
		{
			return $this->po_item_service->get_dao()->get_list(array("po_number"=>$ponumber,"status"=>"A"),array("order by"=>"line_number asc","limit"=>"-1"));
		}
	}

	public function get_order_item($po_number)
	{
		return $this->po_item_service->get_dao()->get_item_w_name($po_number,"Po_item_prodname_dto");
	}

	public function insert_po($obj)
	{
		return $this->purchase_order_service->get_dao()->insert($obj);
	}

	public function update_po($obj)
	{
		return $this->purchase_order_service->get_dao()->update($obj);
	}

	public function insert_po_item($obj)
	{
		return $this->po_item_service->get_dao()->insert($obj);
	}

	public function update_po_item($obj, $where=array())
	{
		return $this->po_item_service->get_dao()->update($obj, $where);
	}

	public function delete_po_item($po_number="",$line_number="")
	{
		if($po_number == "")
		{
			return FALSE;
		}
		else
		{
			echo $po_number." ".$line_number;
			return $this->po_item_service->get_dao()->q_delete(array("po_number"=>$po_number,"line_number"=>$line_number));
		}
	}
	public function start_transaction()
	{
		$this->purchase_order_service->get_dao()->trans_start();
	}

	public function update_po_item_qty($qty,$where=array())
	{
		if(empty($where))
		{
			return FALSE;
		}
		else
		{
			return $this->po_item_service->update_qty($qty,$where);
		}
	}

	public function end_transaction()
	{
		$this->purchase_order_service->get_dao()->trans_complete();
	}

	public function seq_next_val()
	{
		return $this->purchase_order_service->get_dao()->seq_next_val();
	}

	public function ss_seq_next_val()
	{
		return $this->supplier_shipment_service->get_dao()->seq_next_val();
	}

	public function get_shipment_info($po_number)
	{
		return $this->supplier_shipment_service->get_dao()->get_shipment_information($po_number);
	}

	public function get_supplier_shipment_record($po_number)
	{
		if($po_number == "")
		{
			return FALSE;
		}
		else
		{
			return $this->po_item_shipment_service->get_supplier_shipment_record($po_number);
		}
	}

	public function get_purchase_order_item_shipment()
	{
		return $this->po_item_shipment_service->get_dao()->get();
	}

	public function update_seq($new_value)
	{
		return $this->purchase_order_service->get_dao()->update_seq($new_value);
	}

	public function ss_update_seq($new_value)
	{
		return $this->supplier_shipment_service->get_dao()->update_seq($new_value);
	}

	public function get_max_line_number($po_number)
	{
		if($po_number <> "")
		{
			return $this->po_item_service->get_dao()->get_max_line_number($po_number);
		}
		else
		{
			return FALSE;
		}
	}

	public function get_warehouse_list()
	{
		return $this->warehouse_service->get_dao()->get_list();
	}

	public function insert_shipment_item($obj)
	{
		return $this->po_item_shipment_service->get_dao()->insert($obj);
	}

	public function check_shipment_status($po_number)
	{
		return $this->po_item_service->get_dao()->check_shipment_status($po_number);
	}

	public function update_po_status($where=array(),$update="")
	{
		return $this->purchase_order_service->check_status($where,$update);
	}

	public function get_supplier_shipment_obj($sid="")
	{
		if($sid == "")
		{
			return $this->supplier_shipment_service->get_dao()->get();
		}
		else
		{
			return $this->supplier_shipment_service->get_dao()->get(array("shipment_id"=>$sid));
		}
	}

	public function insert_shipment($obj)
	{
		return $this->supplier_shipment_service->get_dao()->insert($obj);
	}

	public function get_inv_movement_obj($id="")
	{
		if($id == "")
		{
			return $this->inv_movement_service->get_dao()->get();
		}
		else
		{
			return $this->inv_movement_service->get_dao()->get(array("trans_id"=>$id));
		}
	}

	public function trans_rollback()
	{
		$this->purchase_order_service->get_dao()->trans_rollback();
	}

	public function insert_inv_movement($obj)
	{
		return $this->inv_movement_service->get_dao()->insert($obj);
	}

	public function get_confirm_list($wh,$where,$option,$status="")
	{
		if($wh == "")
		{
			return false;
		}
		else
		{
			return $this->inv_movement_service->get_outstanding_w_imvo($wh,$where,$option,$status);
		}
	}


	public function check_overing($input,$po_number, $line_number)
	{
		return $this->po_item_service->check_outstanding($input,$po_number,$line_number);
	}

	public function get_confirm_list2($wh,$where,$option,$status="")
	{
		if($wh == "")
		{
			return false;
		}
		else
		{
			return $this->inv_movement_service->get_outstanding_w_imvo2($wh,$where,$option,$status);
		}
	}

	public function get_sh_list($wh,$where,$option,$status="")
	{
		return $this->inv_movement_service->get_imvo($wh,$where,$option,$status);
	}

	public function get_order_shipped_qty($po_number = "")
	{
		return $this->po_item_service->get_dao()->compare_qty($po_number);
	}

	public function get_error_message()
	{
		return $this->db->_error_message();
	}

	public function update_ss($obj,$where)
	{
		return $this->supplier_shipment_service->get_dao()->update($obj,$where);
	}

	public function update_im($obj,$where)
	{
		return $this->inv_movement_service->get_dao()->update($obj,$where);
	}

	public function update_pois($obj,$where)
	{
		return $this->po_item_shipment_service->get_dao()->update($obj,$where);
	}

	public function get_pm()
	{
		return $this->purchase_order_service->get_pm_dao()->get();
	}

	public function insert_pm($obj)
	{
		return $this->purchase_order_service->get_pm_dao()->insert($obj);
	}

	public function get_shipment_count($ponumber = "")
	{
		return $this->po_item_shipment_service->get_shipment_count($ponumber);
	}

	public function __autoload_imvo()
	{
		$this->inv_movement_service->include_vo();
	}

	public function __autoload_ssvo()
	{
		$this->supplier_shipment_service->include_vo();
	}

	public function __autoload_po_vo()
	{
		$this->purchase_order_service->include_vo();
	}

	public function __autoload_po_item_vo()
	{
		$this->po_item_service->include_vo();
	}

	public function __autoload_pois_vo()
	{
		$this->po_item_shipment_service->include_vo();
	}
}


?>