<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_product_custom_class_service extends Vb_data_transfer_service
{

	public function __construct($debug = 0)
	{
		parent::__construct($debug);

		include_once(APPPATH . 'libraries/dao/Product_custom_classification_dao.php');
		$this->pc_dao = new Product_custom_classification_dao();

        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");
		$this->sku_mapping_service = new Sku_mapping_service();
	}

	public function get_dao()
	{
		return $this->pc_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->pc_dao = $dao;
	}

	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
	public function process_vb_data ($feed)
	{
		// print $feed;
		// exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<products task_id="' . $task_id . '">';

		$c = count($xml_vb->product);
		foreach($xml_vb->product as $pc)
		{
			$c--;

			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $pc->master_sku;

			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);

			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

			if(!$pc_obj_atomv2 = $this->get_dao()->get(array("sku"=>$sku, "country_id"=>$pc->country_id)))
			{
				$fail_reason .= "SKU/Country not specified, ";
			}

			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data
					$where = array("sku"=>$sku, "country_id"=>$pc->country_id);

					$new_pc_obj = array();

					$new_pc_obj["code"] = $pc->code;
					$new_pc_obj["description"] = $this->replace_special_chars($pc->description);
					$new_pc_obj["duty_pcent"] = $pc->duty_pcent;

					$this->get_dao()->q_update($where, $new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//insert
					$new_pc_obj = $this->get_dao()->get();
					$new_pc_obj->set_sku($sku);
					$new_pc_obj->set_country_id($pc->country_id);
					$new_pc_obj->set_code($pc->code);
					$new_pc_obj->set_description($this->replace_special_chars($pc->description));
					$new_pc_obj->set_duty_pcent($pc->duty_pcent);

					$this->get_dao()->insert($new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}

			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<platform_id>' . $pc->country_id . '</platform_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '</product>';
			}
		 }

		$xml[] = '</products>';

		$return_feed = implode("", $xml);

		return $return_feed;
	}
}