<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CategoryDao;
use ESG\Panther\Dao\CategoryExtendDao;


class VbDataTransferCategoryExtendService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->CategoryExtendDao;
	}
	
	public function getCatDao()
	{
		return $this->CategoryDao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the category table
	***********************************************************************/
	public function processVbData ($feed)
	{		
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<categories task_id="' . $task_id . '">';
					
		$c = count($xml_vb->category);
		foreach($xml_vb->category as $category)
		{
			$c--;			
						
			//$id = $this->category_id_mapping_service->get_local_id($ext_id);
			
			//We look if the category exists
			//$id = $category->cat_id;
			
			try
			{
				if($cat_atomv2 = $this->getCatDao->get(array("id"=>$category->cat_id)))
				{
					$id = $cat_atomv2->getId();				
				}
				else
				{
					$id = "";
				}
				
				if ($id != "" && $id != null)
				{
					//category exists
					$lang_id = "";
					
					if($cat_ext_atomv2 = $this->getDao()->get(array("cat_id"=>$id, "lang_id"=>$category->lang_id)))
					{
						$lang_id .= $cat_ext_atomv2->getLangId();
					}				
					//if extend content exists, update
					if ($lang_id != "" && $lang_id != null)
					{
						/*//Update the AtomV2 category extend data 					
						$where = array("cat_id"=>$id, "lang_id"=>$lang_id);
						
						$new_cat_obj = array();
						
						$new_cat_obj["name"] = $category->name;
						
						$this->getDao()->qUpdate($where, $new_cat_obj);*/				
						
						//return result
						$xml[] = '<category>';
						$xml[] = '<id>' . $category->cat_id . '</id>';
						$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
						$xml[] = '<status>2</status>'; //we dont update the display name of the category (only insert) --> not updated
						$xml[] = '<is_error>' . $category->is_error . '</is_error>';
						$xml[] = '</category>';		
					}
					//if not exists, insert
					else
					{
						//insert				
						$new_cat_obj = array();
						
						$new_cat_obj = $this->getDao()->get();
						$new_cat_obj->setCatId($category->cat_id);
						$new_cat_obj->setLangId($category->lang_id);
						$new_cat_obj->setName($category->name);
						$this->getDao()->insert($new_cat_obj);		

						$xml[] = '<category>';
						$xml[] = '<id>' . $category->cat_id . '</id>';
						$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $category->is_error . '</is_error>';
						$xml[] = '</category>';						
					}
				}
				elseif ($id == "" || $id == null)
				{
					//if the ext_id is not changed in atomv2, we store it in an xml string to send it to VB
					$xml[] = '<category>';
					$xml[] = '<id>' . $category->cat_id . '</id>';
					$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
					$xml[] = '<status>2</status>'; //category not found
					$xml[] = '<is_error>' . $category->is_error . '</is_error>';
					$xml[] = '</category>';
				}	
			}	
			catch(Exception $e)
			{
				$xml[] = '<category>';
				$xml[] = '<id>' . $category->cat_id . '</id>';
				$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $category->is_error . '</is_error>';
				$xml[] = '</category>';
			}
		 }
		 
		$xml[] = '</categories>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}