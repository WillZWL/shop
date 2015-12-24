<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_product_identifier_service extends Vb_data_transfer_service
{

	public function __construct($debug = 0)
	{
		parent::__construct($debug);

		include_once(APPPATH . 'libraries/dao/Product_identifier_dao.php');
		$this->product_identifier_dao = new Product_identifier_dao();

        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");
		$this->sku_mapping_service = new Sku_mapping_service();

		include_once(APPPATH . 'libraries/service/Product_identifier_service.php');
		$this->product_identifier_service = new Product_identifier_service();
	}

	public function get_dao()
	{
		return $this->product_identifier_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->product_identifier_dao = $dao;
	}

	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
	public function process_vb_data ($feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<product_identifiers task_id="' . $task_id . '">';

		$c = count($xml_vb->product_identifier);
		foreach($xml_vb->product_identifier as $product)
		{
			$c--;

			//check if the sku is mapped in atomv2
			$master_sku = $pc->master_sku;

			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
			if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

            //if the sku is mapped, we get the atomv prod_gro_id
            $master_prod_grp_id = "";
            if ($fail_reason == "")
            	$master_prod_grp_id = $this->product_identifier_service->get_prod_grp_cd_by_sku($sku);

			if(!$pc_obj_atomv2 = $this->get_dao()->get(array("prod_grp_cd"=>$master_prod_grp_id, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id)))
			{
				$fail_reason .= "Product identifier not specified, ";
			}

			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data
					$where = array("prod_grp_cd"=>$master_prod_grp_id, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id);

					$new_prod_obj = array();

					$new_prod_obj["ean"] = $product->ean;
					$new_prod_obj["mpn"] = $product->mpn;
					$new_prod_obj["upc"] = $product->upc;
					$new_prod_obj["status"] = $product->status;

					$this->get_dao()->q_update($where, $new_prod_obj);

					$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '</product_identifier>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//the identifier doesnt exist, but the sku is mapped in atomv2
					//insert the product identifier
					$new_prod_obj = $this->get_dao()->get();

					$new_prod_obj->set_prod_grp_cd($master_prod_grp_id);
					$new_prod_obj->set_colour_id($product->colour_id);
					$new_prod_obj->set_country_id($product->country_id);
					$new_prod_obj->set_ean($product->ean);
					$new_prod_obj->set_mpn($product->mpn);
					$new_prod_obj->set_upc($product->upc);
					$new_prod_obj->set_status($product->status);

					$this->get_dao()->insert($new_prod_obj);

					$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '</product_identifier>';
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that prod_grp_id in an xml string to send it to VB

					$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '</product_identifier>';
				}
				else
				{
					$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '</product_identifier>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>4</status>'; //error
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '</product_identifier>';
			}
		 }

		$xml[] = '</product_identifiers>';

		$return_feed = implode("", $xml);

		return $return_feed;
	}
}