<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_product_content_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Product_content_dao.php');
		$this->pc_dao = new Product_content_dao();
		
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
		print $feed;
		exit;
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
			$lang_id = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			if(!$pc_obj_atomv2 = $this->get_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$pc->lang_id)))
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
					
					$new_pc_obj["prod_name"] = $this->replace_special_chars($pc->prod_name); 
					$new_pc_obj["prod_name_original"] = $this->replace_special_chars($pc->prod_name_original);	
					$new_pc_obj["short_desc"] = $pc->short_desc;	
					$new_pc_obj["contents"]  = $this->replace_special_chars($pc->contents);	  
					$new_pc_obj["contents_original"] = $this->replace_special_chars($pc->contents_original);
					$new_pc_obj["series"] = $pc->series;
					$new_pc_obj["keywords"] = $this->replace_special_chars($pc->keywords);
					$new_pc_obj["keywords_original"] = $this->replace_special_chars($pc->keywords_original);	
					$new_pc_obj["model_1"] = $pc->model_1;
					$new_pc_obj["model_2"] = $pc->model_2;
					$new_pc_obj["model_3"] = $pc->model_3;
					$new_pc_obj["model_4"] = $pc->model_4;
					$new_pc_obj["model_5"] = $pc->model_5;
					$new_pc_obj["detail_desc"] = $this->replace_special_chars($pc->detail_desc);
					$new_pc_obj["detail_desc_original"] = $this->replace_special_chars($pc->detail_desc_original);
					$new_pc_obj["extra_info"] = $pc->extra_info;				
					$new_pc_obj["website_status_long_text"] = $pc->website_status_long_text;
					$new_pc_obj["website_status_short_text"] = $pc->website_status_short_text;
					$new_pc_obj["youtube_id_1"] = $pc->youtube_id_1;
					$new_pc_obj["youtube_id_2"] = $pc->youtube_id_2;
					$new_pc_obj["youtube_caption_1"] = $this->replace_special_chars($pc->youtube_caption_1);			
					$new_pc_obj["youtube_caption_2"] = $this->replace_special_chars($pc->youtube_caption_2);
					
					$this->get_dao()->q_update($where, $new_pc_obj);
					
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
					
					$new_pc_obj = $this->get_dao()->get();
					$new_pc_obj->set_prod_sku($sku);
					$new_pc_obj->set_lang_id($pc->lang_id);
					$new_pc_obj->set_prod_name($this->replace_special_chars($pc->prod_name));
					$new_pc_obj->set_prod_name_original($this->replace_special_chars($pc->prod_name_original));
					$new_pc_obj->set_short_desc($pc->short_desc);	
					$new_pc_obj->set_contents($this->replace_special_chars($pc->contents));	  
					$new_pc_obj->set_contents_original($this->replace_special_chars($pc->contents_original));
					$new_pc_obj->set_series($pc->series);
					$new_pc_obj->set_keywords($this->replace_special_chars($pc->keywords));
					$new_pc_obj->set_keywords_original($this->replace_special_chars($pc->keywords_original));	
					$new_pc_obj->set_model_1($pc->model_1);
					$new_pc_obj->set_model_2($pc->model_2);
					$new_pc_obj->set_model_3($pc->model_3);
					$new_pc_obj->set_model_4($pc->model_4);
					$new_pc_obj->set_model_5($pc->model_5);
					$new_pc_obj->set_detail_desc($this->replace_special_chars($pc->detail_desc));
					$new_pc_obj->set_detail_desc_original($this->replace_special_chars($pc->detail_desc_original));
					$new_pc_obj->set_extra_info($pc->extra_info);				
					$new_pc_obj->set_website_status_long_text($pc->website_status_long_text);
					$new_pc_obj->set_website_status_short_text($pc->website_status_short_text);
					$new_pc_obj->set_youtube_id_1($pc->youtube_id_1);
					$new_pc_obj->set_youtube_id_2($pc->youtube_id_2);
					$new_pc_obj->set_youtube_caption_1($this->replace_special_chars($pc->youtube_caption_1));			
					$new_pc_obj->set_youtube_caption_2($this->replace_special_chars($pc->youtube_caption_2));
					
					$this->get_dao()->insert($new_pc_obj);	
					
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