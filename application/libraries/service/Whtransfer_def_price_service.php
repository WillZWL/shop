<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_price_service.php";

class Whtransfer_def_price_service extends Base_price_service
{
	private $supplier_service;
	private $supplier_region;
	private $supplier_fc;
	private $customer_fc;
	private $ccountry;
	private $weight_cat;

	public function Whtransfer_def_price_service($supplier_region="",$supplier_fc="",$customer_fc="",$ccountry="",$weight_cat="")
	{
		parent::Base_price_service();
		include_once APPPATH."libraries/dto/product_cost_dto.php";
		$this->set_dto(new Product_cost_dto());
		if($supplier_region != "")
		{
			$this->supplier_region = $supplier_region;
		}
		if($supplier_fc != "")
		{
			$this->supplier_fc = $supplier_fc;
		}

		if($customer_fc != "")
		{
			$this->customer_fc = $customer_fc;
		}

		if($ccountry != "")
		{
			$this->ccountry = $ccountry;
		}

		if($weight_cat != "")
		{
			$this->weight_cat = $weight_cat;
		}
		include_once APPPATH."libraries/service/Supplier_service.php";
		$this->set_supplier_service(new Supplier_service());
	}

	public function get_supp_to_fc_cost($freight_cat = "", $supplier="")
	{
		if($this->supplier_fc == "" || $this->supplier_region == "")
		{
			if($supplier != "" || $freight_cat == "")
			{
				$supplier_obj = $this->get_supplier_service()->get_dao()->get(array("id"=>$supplier));
				//$sfc = @call_user_func(array($supplier_obj, "get_warehouse_id"));
				$sfc = @call_user_func(array($supplier_obj, "get_fc_id"));
				$sr = @call_user_func(array($supplier_obj, "get_supplier_region"));
			}
			else
			{
				//no actual supplier provided
				return FALSE;
			}
		}
		else
		{
			$sfc = $this->supplier_fc;
			$sr = $this->supplier_region;
		}

		list($loc,$tmp) = explode('_',$sfc);
		$courier_id = 'FR_'.$loc.'FC';

		$charge = $this->get_fcc_service()->get_fcc_dao()->get(array("fcat_id"=>$this->weight_cat,"region_id"=>$sr, "courier_id"=>$courier_id));

		if($charge)
		{
			return array("amount"=>$charge->get_amount(),"currency"=>$charge->get_currency_id());
		}
		else
		{
			return array("amount"=>0, "currency"=>"");
		}
	}

	public function get_wh_fc_cost($freight_cat = "",$from_wh="", $to_wh="")
	{
		if($this->supplier_fc == "" || $this->customer_fc == "")
		{
			if($from_wh == "" || $to_wh == "" || $freight_cat == "")
			{
				return FALSE;
			}

			//$fwh_obj = $this->get_warehouse_service()->get_dao()->get(array("id"=>$from_wh));
			//$twh_obj = $this->get_warehouse_service()->get_dao()->get(array("id"=>$to_wh));

			//$sfc = substr(0,2,$fwh_obj->get_warehouse_id());
			//$cfc = substr(0,2,$twh_obj->get_warehouse_id());
			$sfc = substr(0,2,$from_wh);
			$cfc = substr(0,2,$to_wh);

		}
		else
		{
			$tf1 = explode("_",$this->supplier_fc);
			$cf1 = explode("_",$this->customer_fc);
			$sfc = $tf1[0];
			$cfc = $cf1[0];
			unset($tf1);
			unset($cf1);
		}

		$weight_cat = $this->weight_cat;
		$courier_id = "BULK_".$sfc."_".$cfc;
		$charge = $this->get_fcc_service()->get_fcc_dao()->get(array("courier_id"=>$courier_id,"fcat_id"=>$weight_cat));

		if($charge)
		{
			return array("amount"=>$charge->get_amount(),"currency"=>$charge->get_currency_id());
		}
		else
		{
			return array("amount"=>0,"currency"=>"");
		}
	}

	public function get_fc_to_customer_cost($fc="", $ccountry="")
	{
		if($this->ccountry == "" || $this->customer_fc == "")
		{
			if($weight_cat == "" || $fc == "" || $cregion == "")
			{
				return FALSE;
			}

		}
		else
		{
			$ccountry = $this->ccountry;
			$fc = $this->customer_fc;
		}

		$weight_cat = $this->get_wcc_service()->get_wc_from_fc($this->weight_cat);
		list($fcloc,$t) = explode("_",$fc);
		$courier_id = "DHL_".$fcloc;
		$dregion = $this->get_region_service()->get_dao()->get_dregion($courier_id,$ccountry);
		$cost = $this->get_wcc_service()->get_wcc_dao()->get(array("courier_id"=>$courier_id,"wcat_id"=>$weight_cat,"region_id"=>$dregion,'type'=>'CO'));
		$charge = $this->get_wcc_service()->get_wcc_dao()->get(array("courier_id"=>$courier_id,"wcat_id"=>$weight_cat,"region_id"=>$dregion,'type'=>'CH'));
		$ret = array("coamount"=>0, "cocurrency"=>"","chamount"=>0, "chcurrency"=>"");
		if($cost)
		{
			$ret["coamount"] = $cost->get_amount();
			$ret["cocurrency"] = $cost->get_currency_id();
		}
		if($charge)
		{
			$ret["chamount"] = $charge->get_amount();
			$ret["chcurrency"] = $charge->get_currency_id();
		}
		return $ret;
	}

	public function get_supplier_service()
	{
		return $this->supplier_service;
	}

	public function set_supplier_service(Base_service $svc)
	{
		$this->supplier_service = $svc;
		return $this;
	}
}
?>