<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductContentDao;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferProductContentService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->ProductContentDao;
	}
		
	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
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
			$sku = $this->SkuMappingService->getLocalSku($master_sku);
						
			$fail_reason = "";
			$lang_id = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			if(!$pc_obj_atomv2 = $this->getDao()->get(array("prod_sku"=>$sku, "lang_id"=>$pc->lang_id)))
			{
				$fail_reason .= "SKU/Lang not specified, ";
				$lang_id = "";
			}
			
			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data 					
					$where = array("prod_sku"=>$sku, "lang_id"=>$pc->lang_id);
					
					$new_pc_obj = array();
					
					$new_pc_obj["prod_name"] = $pc->prod_name; 
					$new_pc_obj["prod_name_original"] = $pc->prod_name_original;	
					$new_pc_obj["short_desc"] = $pc->short_desc;	
					$new_pc_obj["contents"]  = $pc->contents;	  
					$new_pc_obj["contents_original"] = $pc->contents_original;
					$new_pc_obj["series"] = $pc->series;
					$new_pc_obj["keywords"] = $pc->keywords;
					$new_pc_obj["keywords_original"] = $pc->keywords_original;	
					$new_pc_obj["model_1"] = $pc->model_1;
					$new_pc_obj["model_2"] = $pc->model_2;
					$new_pc_obj["model_3"] = $pc->model_3;
					$new_pc_obj["model_4"] = $pc->model_4;
					$new_pc_obj["model_5"] = $pc->model_5;
					$new_pc_obj["detail_desc"] = $pc->detail_desc;
					$new_pc_obj["detail_desc_original"] = $pc->detail_desc_original;
					$new_pc_obj["extra_info"] = $pc->extra_info;				
					$new_pc_obj["website_status_long_text"] = $pc->website_status_long_text;
					$new_pc_obj["website_status_short_text"] = $pc->website_status_short_text;
					$new_pc_obj["youtube_id_1"] = $pc->youtube_id_1;
					$new_pc_obj["youtube_id_2"] = $pc->youtube_id_2;
					$new_pc_obj["youtube_caption_1"] = $pc->youtube_caption_1;			
					$new_pc_obj["youtube_caption_2"] = $pc->youtube_caption_2;
					
					$this->getDao()->qUpdate($where, $new_pc_obj);
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku != "" && $sku != null)
				{
					//insert				
					$new_pc_obj = array();
					
					$new_pc_obj = $this->getDao()->get();
					$new_pc_obj->setProdSku($sku);
					$new_pc_obj->setLangId($pc->lang_id);
					$new_pc_obj->setProdName($pc->prod_name);
					$new_pc_obj->setProdNameOriginal($pc->prod_name_original);
					$new_pc_obj->setShortDesc($pc->short_desc);	
					$new_pc_obj->setContents($pc->contents);	  
					$new_pc_obj->setContentsOriginal($pc->contents_original);
					$new_pc_obj->setSeries($pc->series);
					$new_pc_obj->setKeywords($pc->keywords);
					$new_pc_obj->setKeywordsOriginal($pc->keywords_original);	
					$new_pc_obj->setModel1($pc->model_1);
					$new_pc_obj->setModel2($pc->model_2);
					$new_pc_obj->setModel3($pc->model_3);
					$new_pc_obj->setModel4($pc->model_4);
					$new_pc_obj->setModel5($pc->model_5);
					$new_pc_obj->setDetailDesc($pc->detail_desc);
					$new_pc_obj->setDetailDescOriginal($pc->detail_desc_original);
					$new_pc_obj->setExtraInfo($pc->extra_info);				
					$new_pc_obj->setWebsiteStatusLongText($pc->website_status_long_text);
					$new_pc_obj->setWebsiteStatusShortText($pc->website_status_short_text);
					$new_pc_obj->setYoutubeId1($pc->youtube_id_1);
					$new_pc_obj->setYoutubeId2($pc->youtube_id_2);
					$new_pc_obj->setYoutubeCaption1($pc->youtube_caption_1);			
					$new_pc_obj->setYoutubeCaption2($pc->youtube_caption_2);
					
					$this->getDao()->insert($new_pc_obj);	
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{				
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
					$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';				
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->prod_sku . '</sku>';
				$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';					
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '</product>';	
			}
		 }
		$xml[] = '</products>';
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}