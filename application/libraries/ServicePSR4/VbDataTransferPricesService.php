<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PriceDao;
use ESG\Panther\Service\PriceService;
use ESG\Panther\Service\PricingRules;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferPricesService extends VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();

		include_once APPPATH . "libraries/service/Price_service.php";
        $this->price_service = new Price_service();
	}

	public function getDao()
	{
		return $this->PriceDao;
	}

	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
	{
		print "hola"; exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes();

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<prices task_id="' . $task_id . '">';

		$c = count($xml_vb->price);
		foreach($xml_vb->price as $price)
		{
			$c--;

			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $price->master_sku;
			$platform_id=  $price->platform_id;
			$required_selling_price=  $price->prod_price;

			$master_sku = strtoupper($master_sku);
			$sku = $this->SkuMappingService->getLocalSku($master_sku);

			$fail_reason = "";
			if ($platform_id == "" || $platform_id == null) $fail_reason .= "No platform specified, ";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
            if ($required_selling_price == "" || $required_selling_price == null || $required_selling_price < 0) $fail_reason .= "Your required selling price $required_selling_price is not acceptable, ";

			try
			{
				if ($fail_reason == "")
				{
					$commit = false;
					// we only commit at the last update
					if ($c <= 0) $commit = true;

					//get pricing rules
					$where = [];

					$where["pbv.platform_id"] = $platform_id;

					$dayofweek = date('w');

					switch ($dayofweek)
					{
						case 0: //sunday
							$where["sunday"] = "1";
							break;
						case 1:
							$where["monday"] = "1";
							break;
						case 2:
							$where["tuesday"] = "1";
							break;
						case 3:
							$where["wednesday"] = "1";
							break;
						case 4:
							$where["thursday"] = "1";
							break;
						case 5:
							$where["friday"] = "1";
							break;
						case 6:
							$where["saturday"] = "1";
							break;
					}

					$where[$required_selling_price . " between pr.range_min and pr.range_max"] = null;

					$data = $this->PricingRules->getPricingRulesByPlatform($where);
					var_dump($data);exit;

					$affected = $this->price_service->update_sku_price($platform_id, $sku, $required_selling_price, $commit);

					if ($affected)
					{
						$xml[] = '<price>';
						$xml[] = '<sku>' . $price->sku . '</sku>';
						$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
						$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $price->is_error . '</is_error>';
						$xml[] = '</price>';
					}
					else
					{
						$xml[] = '<price>';
						$xml[] = '<sku>' . $price->sku . '</sku>';
						$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
						$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
						$xml[] = '<status>3</status>'; //not updated
						$xml[] = '<is_error>' . $price->is_error . '</is_error>';
						$xml[] = '</price>';
					}
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<price>';
					$xml[] = '<sku>' . $price->sku . '</sku>';
					$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
					$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $price->is_error . '</is_error>';
					$xml[] = '</price>';
				}
				else
				{
					$xml[] = '<price>';
					$xml[] = '<sku>' . $price->sku . '</sku>';
					$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
					$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $price->is_error . '</is_error>';
					$xml[] = '</price>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<price>';
				$xml[] = '<sku>' . $price->sku . '</sku>';
				$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
				$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $price->is_error . '</is_error>';
				$xml[] = '</price>';
			}
		 }

		$xml[] = '</prices>';

		$return_feed = implode("\n", $xml);
		//print $return_feed;
		//exit;

		return $return_feed;
	}
}