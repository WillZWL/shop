<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\BrandDao;

class VbDataTransferBrandService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->BrandDao;
	}

	public function setDao(BaseDao $dao)
	{
		$this->BrandDao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the brand table
	***********************************************************************/
	public function processVbData ($feed)
	{		
		
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<brands task_id="' . $task_id . '">';
					
		$c = count($xml_vb->brand);
		foreach($xml_vb->brand as $brand)
		{
			$c--;			
				
			$id = $brand->id;
			
			try
			{			
				if($this->getDao()->get(array("id"=>$brand->id)))
				{
					//update					
					$where = array("id"=>$id);
					
					$new_brand_obj = array();
					
					$new_brand_obj["brand_name"] = $brand->brand_name;
					$new_brand_obj["description"] = $brand->description;					
					$new_brand_obj["status"] = $brand->status;	
					
					$this->getDao()->qUpdate($where, $new_brand_obj);

					$xml[] = '<brand>';
					$xml[] = '<id>' . $brand->id . '</id>';			
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $brand->is_error . '</is_error>';
					$xml[] = '</brand>';	
				}
				else
				{
					//insert				
					$new_brand_obj = array();
					
					$new_brand_obj = $this->getDao()->get();
					$new_brand_obj->setId($brand->id);
					$new_brand_obj->setBrandName($brand->brand_name);
					$new_brand_obj->setDescription($brand->description);
					$new_brand_obj->setStatus($brand->status);
					
					$this->getDao()->insert($new_brand_obj);

					$xml[] = '<brand>';
					$xml[] = '<id>' . $brand->id . '</id>';			
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $brand->is_error . '</is_error>';
					$xml[] = '</brand>';					
				}  
			}	
			catch(Exception $e)
			{
				$xml[] = '<brand>';
				$xml[] = '<id>' . $brand->id . '</id>';			
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $brand->is_error . '</is_error>';
				$xml[] = '</brand>';
			}           
		 }
		 
		$xml[] = '</brands>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}