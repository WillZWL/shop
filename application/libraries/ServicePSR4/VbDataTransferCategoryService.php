<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CategoryDao;

class VbDataTransferCategoryService extends VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();
		$this->setDao(new CategoryDao);
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

            //if ($id == "" || $id == null)
			try
			{
				if($cat_obj = $this->getDao()->get(array("id"=>$category->id)))
				{
					//Update the AtomV2 category data
					$cat_obj->setId($category->id);
					$cat_obj->setName($category->name);
					$cat_obj->setDescription($category->description);
					$cat_obj->setParentCatId($category->parent_cat_id);
					$cat_obj->setLevel($category->level);
					$cat_obj->setAddColourName($category->add_colour_name);
					$cat_obj->setPriority($category->priority);
					$cat_obj->setBundleDiscount($category->bundle_discount);
					$cat_obj->setMinDisplayQty($category->min_display_qty);
					$cat_obj->setStatus($category->status);

					$this->getDao()->update($cat_obj);

					/*$new_cat_obj = array();

					$new_cat_obj["name"] = $category->name;
					$new_cat_obj["description"] = $category->description;
					$new_cat_obj["parent_cat_id"] = $category->parent_cat_id;
					$new_cat_obj["level"] = $category->level;
					$new_cat_obj["add_colour_name"] = $category->add_colour_name;
					$new_cat_obj["priority"] = $category->priority;
					$new_cat_obj["bundle_discount"] = $category->bundle_discount;
					$new_cat_obj["min_display_qty"] = $category->min_display_qty;
					$new_cat_obj["status"] = $category->status;

					$this->getDao()->qUpdate($where, $new_cat_obj);*/

					$xml[] = '<category>';
					$xml[] = '<id>' . $category->id . '</id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $category->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</category>';
				}
				else
				{
					//insert category and mapping
					$new_cat_obj = array();

					$new_cat_obj = $this->getDao()->get();
					$new_cat_obj->setId($category->id);
					$new_cat_obj->setName($category->name);
					$new_cat_obj->setDescription($category->description);
					$new_cat_obj->setParentCatId($category->parent_cat_id);
					$new_cat_obj->setLevel($category->level);
					$new_cat_obj->setAddColourName($category->add_colour_name);
					$new_cat_obj->setPriority($category->priority);
					$new_cat_obj->setBundleDiscount($category->bundle_discount);
					$new_cat_obj->setMinDisplayQty($category->min_display_qty);
					$new_cat_obj->setSponsored(0);
					$new_cat_obj->setStatus($category->status);

					$this->getDao()->insert($new_cat_obj);

					$xml[] = '<category>';
					$xml[] = '<id>' . $category->id . '</id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $category->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</category>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<category>';
				$xml[] = '<id>' . $category->id . '</id>';
				$xml[] = '<is_error>' . $category->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</category>';
			}
		 }

		$xml[] = '</categories>';


		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}