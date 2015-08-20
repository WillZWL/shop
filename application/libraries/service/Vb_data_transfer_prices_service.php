<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/Service/Vb_data_transfer_service.php");

class Vb_data_transfer_prices_service extends Vb_data_transfer_service
{
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Price_dao.php');
		$this->price_dao = new Price_dao();
		include_once APPPATH . "libraries/Service/PriceService.php";
        $this->price_service = new PriceService();
		
        include_once(APPPATH . "libraries/Service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();
	}
	
	public function get_dao()
	{
		return $this->price_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->price_dao = $dao;
	}
		
	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
	public function process_vb_data ($feed)
	{		
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes();
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_prices task_id="' . $task_id . '">';
		
		$error_nodes = array();	
		$error_nodes[] = '<errors task_id="' . $task_id . '">';		
				
		$c = count($xml_vb->price);
		foreach($xml_vb->price as $price)
		{
			$c--;
				
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $price->master_sku;
			$platform_id=  $price->platform_id;
			$required_selling_price=  $price->prod_price;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
			
			$fail_reason = "";
			if ($platform_id == "" || $platform_id == null) $fail_reason .= "No platform specified, ";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
            if ($required_selling_price == "" || $required_selling_price == null || $required_selling_price < 0) $fail_reason .= "Your required selling price $required_selling_price is not acceptable, ";

			if ($fail_reason == "")
			{
				$commit = false;
				// we only commit at the last update
				if ($c <= 0) $commit = true;
			
				$affected = $this->price_service->update_sku_price($platform_id, $sku, $required_selling_price, $commit);
			}
			elseif ($sku == "" || $sku == null)
			{				
				//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
				$xml[] = '<price>';
				$xml[] = '<sku>' . $price->sku . '</sku>';
				$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';				
				$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';		
				$xml[] = '</price>';
			}
			else
			{
				$error_nodes[] = '<error>';
				$error_nodes[] = '<sku>' . $price->sku . '</sku>';
				$error_nodes[] = '<description>' . $fail_reason . '</description>';				
				$error_nodes[] = '</error>';				
			}
		 }
		 
		$error_nodes[] = '</errors>';
		$xml[] = '</no_updated_prices>';
		
		array_merge($xml, $error_nodes);
		
		$return_feed = implode("\n", $xml);
		//print $return_feed;
		//exit;
	
		return $return_feed;
	}
}