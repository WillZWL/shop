<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SupplierProdDao;
use ESG\Panther\Dao\SupplierDao;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferSupplierProductService extends VbDataTransferService
{

	private $supplierdao;

	public function __construct()
	{
		parent::__construct();
		$this->setDao(new SupplierProdDao);
        $this->setSupplierDao(new SupplierDao);
        $this->skuMappingService = new SkuMappingService;
	}

	public function getSupplierDao()
    {
        return $this->supplierdao;
    }

    public function setSupplierDao($dao)
    {
        $this->supplierdao = $dao;
    }


	/**************************************************************************
	*	processVbData, get the VB data to save it in the supplier prod table
	***************************************************************************/
	public function processVbData ($feed)
	{
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
			$sku = $this->skuMappingService->getLocalSku($master_sku);

			$fail_reason = "";
			$id = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

			if(!$sup_obj_atomv2 = $this->getSupplierDao()->get(array("name"=>$pc->supplier_name, "id"=>$pc->supplier_id)))
			{
				$fail_reason .= "Supplier not exists, ";
				$id = "";
			}
			else
			{
				if(!$pc_obj_atomv2 = $this->getDao()->get(array("supplier_id"=>$pc->supplier_id, "prod_sku"=>$sku)))
				{
					$fail_reason .= "ID/SKU not specified, ";
				}
				else
				{
					$id = $pc->supplier_id;
				}
			}

			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data
					$where = array("supplier_id"=>$pc->supplier_id, "prod_sku"=>$pc->prod_sku);

					$new_pc_obj = array();

					$new_pc_obj["currency_id"] = $pc->currency_id;
					$new_pc_obj["cost"] = $pc->cost;
					$new_pc_obj["pricehkd"] = $pc->pricehkd;
					$new_pc_obj["lead_day"]  = $pc->lead_day;
					$new_pc_obj["moq"] = $pc->moq;
					$new_pc_obj["location"] = $pc->location;
					$new_pc_obj["region"] = $pc->region;
					$new_pc_obj["order_default"] = $pc->order_default;
					$new_pc_obj["region_default"] = $pc->region_default;
					$new_pc_obj["supplier_status"] = $pc->supplier_status;
					$new_pc_obj["comments"] = $pc->comments;

					$this->getDao()->qUpdate($where, $new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</product>';
				}
				elseif ($id != "" && $id != null)
				{
					//insert
					$new_pc_obj = array();

					$new_pc_obj = $this->getDao()->get();
					$new_pc_obj->setProdSku($sku);
					$new_pc_obj->setSupplierId($pc->supplier_id);
					$new_pc_obj->setCurrencyId($pc->currency_id);
					$new_pc_obj->setCost($pc->cost);
					$new_pc_obj->setPricehkd($pc->pricehkd);
					$new_pc_obj->setLeadDay($pc->lead_day);
					$new_pc_obj->setMoq($pc->moq);
					$new_pc_obj->setLocation($pc->location);
					$new_pc_obj->setRegion($pc->region);
					$new_pc_obj->setOrderDefault($pc->order_default);
					$new_pc_obj->setRegionDefault($pc->region_default);
					$new_pc_obj->setSupplierStatus($pc->supplier_status);
					$new_pc_obj->setComments($pc->comments);

					$this->getDao()->insert($new_pc_obj);

					//return result
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';
					$xml[] = '</product>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
				$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</product>';
			}
		 }
		$xml[] = '</products>';

		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}