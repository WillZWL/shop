<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductIdentifierDao;
use ESG\Panther\Service\SkuMappingService;
use ESG\Panther\Service\ProductIdentifierService;

class VbDataTransferProductIdentifierService extends VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();
		$this->setDao(new ProductIdentifierDao);
        $this->skuMappingService = new SkuMappingService;
        $this->productIdentifierService = new ProductIdentifierService;
	}

	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
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
			$sku = $this->skuMappingService->getLocalSku($master_sku);
			if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

            //if the sku is mapped, we get the atomv prod_gro_id
            $master_prod_grp_id = "";
            if ($fail_reason == "")
            	$master_prod_grp_id = $this->productIdentifierService->getProdGrpCdBySku($sku);

			if(!$pc_obj_atomv2 = $this->getDao()->get(array("prod_grp_cd"=>$master_prod_grp_id, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id)))
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

					$this->getDao()->qUpdate($where, $new_prod_obj);

					$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</product_identifier>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//the identifier doesnt exist, but the sku is mapped in atomv2
					//insert the product identifier
					$new_prod_obj = $this->getDao()->get();

					$new_prod_obj->setProdGrpCd($master_prod_grp_id);
					$new_prod_obj->setColourId($product->colour_id);
					$new_prod_obj->setCountryId($product->country_id);
					$new_prod_obj->setEan($product->ean);
					$new_prod_obj->setMpn($product->mpn);
					$new_prod_obj->setUpc($product->upc);
					$new_prod_obj->setStatus($product->status);

					$this->getDao()->insert($new_prod_obj);

					$xml[] = '<product_identifier>';
					$xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
					$xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
					$xml[] = '<country_id>' . $product->country_id . '</country_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
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
					$xml[] = '<reason>' . $fail_reason . '</reason>';
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
					$xml[] = '<reason>' . $fail_reason . '</reason>';
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
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</product_identifier>';
			}
		 }

		$xml[] = '</product_identifiers>';

		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}