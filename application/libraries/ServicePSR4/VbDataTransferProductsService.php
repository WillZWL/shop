<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Dao\SkuMappingDao;
use ESG\Panther\Service\SkuMappingService;
use ESG\Panther\Service\ProductIdentifierService;

class VbDataTransferProductsService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->product_dao;
	}
		
	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
	{		
		//print $feed; exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
		$is_error_task = $xml_vb->attributes()->is_error_task;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<products task_id="' . $task_id . '" is_error_task="' . $is_error_task . '">';	
				
		$c = count($xml_vb->product);
		foreach($xml_vb->product as $product)
		{
			$c--;
				
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $product->master_sku;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->SkuMappingService->getLocalSku($master_sku);
			
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

            //if the sku is mapped, we get the atomv prod_gro_id
            $master_prod_grp_id = "";
            if ($fail_reason == "")
            	$master_prod_grp_id = $this->ProductIdentifierService->getProdGrpCdBySku($sku);
			
			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data 					
					$where = array("sku"=>$sku);
					
					$new_prod_obj = array();
					
					$new_prod_obj["prod_grp_cd"] = $master_prod_grp_id;//$product->prod_grp_cd;
					$new_prod_obj["colour_id"] = $product->colour_id; //FK colour
					$new_prod_obj["version_id"] = $product->version_id;	
					$new_prod_obj["name"] = $product->name;				
					$new_prod_obj["freight_cat_id"] = $product->freight_cat_id; //FK freight_category
					$new_prod_obj["cat_id"] = $product->cat_id; //FK category
					$new_prod_obj["sub_cat_id"] = $product->sub_cat_id; //FK category
					$new_prod_obj["sub_sub_cat_id"] = $product->sub_sub_cat_id; //FK category
					$new_prod_obj["brand_id"] = $product->brand_id; //FK brand
					$new_prod_obj["clearance"] = $product->clearance;
					$new_prod_obj["surplus_quantity"] = $product->surplus_quantity; // NOT EXIST IN ATOMV2
					$new_prod_obj["slow_move_7_days"] = $product->slow_move_7_days; // NOT EXIST IN ATOMV2
					$new_prod_obj["quantity"] = $product->quantity;
					$new_prod_obj["display_quantity"] = $product->display_quantity;
					$new_prod_obj["website_quantity"] = $product->website_quantity;
					$new_prod_obj["china_oem"] = $product->china_oem; // NOT EXIST IN ATOMV2
					$new_prod_obj["ex_demo"] = $product->ex_demo;
					$new_prod_obj["rrp"] = $product->rrp;
					$new_prod_obj["image"] = $product->image;
					$new_prod_obj["flash"] = $product->flash;
					$new_prod_obj["youtube_id"] = $product->youtube_id;
					$new_prod_obj["ean"] = $product->ean;
					$new_prod_obj["mpn"] = $product->mpn;
					$new_prod_obj["upc"] = $product->upc;
					$new_prod_obj["discount"] = $product->discount;
					$new_prod_obj["proc_status"] = $product->proc_status;
					$new_prod_obj["website_status"] = $product->website_status;
					$new_prod_obj["sourcing_status"] = $product->sourcing_status;
					$new_prod_obj["expected_delivery_date"] = $product->expected_delivery_date;
					$new_prod_obj["warranty_in_month"] = $product->warranty_in_month;
					$new_prod_obj["cat_upselling"] = $product->cat_upselling;
					$new_prod_obj["lang_restricted"] = $product->lang_restricted;
					$new_prod_obj["shipment_restricted_type"] = $product->shipment_restricted_type; 
					//$new_prod_obj["comments"] = $product->comments; 	
					$new_prod_obj["status"] = $product->status; 		
					
					$this->getDao()->qUpdate($where, $new_prod_obj);				
					
					$xml[] = '<product>';
					$xml[] = '<sku>' . $product->sku . '</sku>';
					$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
					$xml[] = '<status>5</status>';	 //updated				
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';		
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{	
					//TO DO --> Create product function
					
					//insert									
					$new_prod_obj = $this->getDao()->get();
					
					$sku = $this->getDao()->getNewSku();
					$prod_grp_cd = $this->getDao()->getNewProductGroup();
					if ($sku != "" && $sku != null && $prod_grp_cd != "" && $prod_grp_cd != null)
					{
						//insert sku mapping
						$new_sku_map_obj = $this->SkuMappingDao()->get();
						
						$new_sku_map_obj->setExtSku($master_sku);
						$new_sku_map_obj->setExtSys("WMS");
						$new_sku_map_obj->setSku($sku);
						$new_sku_map_obj->setStatus(1);
						
						$this->sku_mapping_service->getDao()->insert($new_sku_map_obj);
						
						//TO DO --> create product identifier
						
						//insert the product
						$new_prod_obj->setSku($sku);
						$new_prod_obj->setProdGrpCd($prod_grp_cd);
						$new_prod_obj->setColourId($product->colour_id); //FK colour
						$new_prod_obj->setVersionId($product->version_id);	
						$new_prod_obj->setName($product->name);				
						$new_prod_obj->setFreightCatId($product->freight_cat_id); //FK freight_category
						$new_prod_obj->setCatId($product->cat_id); //FK category
						$new_prod_obj->setSubCatId($product->sub_cat_id); //FK category
						$new_prod_obj->setSubSubCatId($product->sub_sub_cat_id); //FK category
						$new_prod_obj->setBrandId($product->brand_id); //FK brand
						$new_prod_obj->setClearance($product->clearance);
						$new_prod_obj->setSurplusQuantity($product->surplus_quantity);
						$new_prod_obj->setSlowMove7Days($product->slow_move_7_days);
						$new_prod_obj->setQuantity($product->quantity);
						$new_prod_obj->setDisplayQuantity($product->display_quantity);
						$new_prod_obj->setWebsiteQuantity($product->website_quantity);
						$new_prod_obj->setChinaOem($product->china_oem); 
						$new_prod_obj->setExDemo($product->ex_demo);
						$new_prod_obj->setRrp($product->rrp);
						$new_prod_obj->setImage($product->image);
						$new_prod_obj->setFlash($product->flash);
						$new_prod_obj->setYoutubeId($product->youtube_id);
						$new_prod_obj->setEan($product->ean);
						$new_prod_obj->setMpn($product->mpn);
						$new_prod_obj->setUpc($product->upc);
						$new_prod_obj->setDiscount($product->discount);
						$new_prod_obj->setProcStatus($product->proc_status);
						$new_prod_obj->setWebsiteStatus($product->website_status);
						$new_prod_obj->setSourcingStatus($product->sourcing_status);
						$new_prod_obj->setExpectedDeliveryDate($product->expected_delivery_date);
						$new_prod_obj->setWarrantyInMonth($product->warranty_in_month);
						$new_prod_obj->setCatUpselling($product->cat_upselling);
						$new_prod_obj->setLangRestricted($product->lang_restricted);
						$new_prod_obj->setShipmentRestrictedType($product->shipment_restricted_type); 
						//$new_prod_obj->set_comments($product->comments); 	
						$new_prod_obj->set_status($product->status); 
						
						$this->getDao()->insert($new_prod_obj);
						
						//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
						$xml[] = '<product>';
						$xml[] = '<sku>' . $product->sku . '</sku>';
						$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';				
						$xml[] = '<status>5</status>'; //inserted	
						$xml[] = '<is_error>' . $product->is_error . '</is_error>';			
						$xml[] = '</product>';
					}
					else
					{
						$xml[] = '<product>';
						$xml[] = '<sku>' . $product->sku . '</sku>';
						$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
						$xml[] = '<status>2</status>'; //not found	
						$xml[] = '<is_error>' . $product->is_error . '</is_error>';
						$xml[] = '</product>';	
					}
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $product->sku . '</sku>';
					$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
					$xml[] = '<status>3</status>'; //not updated	
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '</product>';				
				}	
			}	
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $product->sku . '</sku>';
				$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
				$xml[] = '<status>4</status>';	//error		
				$xml[] = '<is_error>' . $product->is_error . '</is_error>';
				$xml[] = '</product>';	
			}
		 }
		 
		$xml[] = '</products>';
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}