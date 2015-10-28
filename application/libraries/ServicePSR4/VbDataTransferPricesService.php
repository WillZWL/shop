<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PriceDao;
use ESG\Panther\Dao\ProductDao;
use ESG\Panther\Service\PriceService;
use ESG\Panther\Service\PricingRulesService;
use ESG\Panther\Service\SkuMappingService;

class VbDataTransferPricesService extends VbDataTransferService
{

    private $prodDao;

	public function __construct()
	{
		parent::__construct();
        $this->setDao(new PriceDao);
        $this->setProductDao(new ProductDao);
        $this->skuMappingService = new SkuMappingService;
        $this->priceService = new PriceService;
        $this->pricingRulesService = new PricingRulesService;
	}


    public function getProductDao()
    {
        return $this->prodDao;
    }

    public function setProductDao($dao)
    {
        $this->prodDao = $dao;
    }
	/*******************************************************************
	*	processVbData, get the VB data to save it in the price table
	********************************************************************/
	public function processVbData ($feed)
	{
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
			$required_selling_price=  floatval($price->prod_price);
			$VB_price=  floatval($price->prod_price); //update the VB price field in the atomv2 price table

			$master_sku = strtoupper($master_sku);
			$sku = $this->skuMappingService->getLocalSku($master_sku);

			$fail_reason = "";
			if ($platform_id == "" || $platform_id == null) $fail_reason .= "No platform specified, ";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
            if ($required_selling_price == "" || $required_selling_price == null || $required_selling_price < 0) $fail_reason .= "Your required selling price $required_selling_price is not acceptable, ";

            $old_atomv2_price = 0;
			if(!$old_atomv2_obj = $this->getDao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
			{
				$fail_reason .= "Price doesnt exist, ";
			}
			else
			{
				$old_atomv2_price = $old_atomv2_obj->getPrice();
			}

			try
			{
				if ($fail_reason == "")
				{
					$commit = false;
					// we only commit at the last update
					if ($c <= 0) $commit = true;

					//get pricing rules
					$where = [];

					$where["pbv.selling_platform_id"] = $platform_id;

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

					$data = $this->pricingRulesService->getPricingRulesByPlatform($where);

					//if exist, apply the pricing rules to the VB price
					//if dont exist rules for the VB price, we use directly the VB price
					$price_change = "";

					/*$json = $this->priceService->getProfitMarginJson($platform_id, $sku, $old_atomv2_price);
		            //file_put_contents("/var/log/vb-json", "{$soidObj->get_so_no()} || $json", FILE_APPEND);
		            $jj = json_decode($json, true);
		            $old_marging = round($jj["get_margin"], 2);
		            $price_change .=   " old price " . $old_atomv2_price . " old margin " . $old_marging;*/


					$min_margin = "";
					if (!empty($data["pricingrules"]))
					{
						$rule_type = $data["pricingrules"][0]->getMarkUpType();
						$rule_markup = $data["pricingrules"][0]->getMarkUpValue();
						$min_margin = $data["pricingrules"][0]->getMinMargin();

						$required_selling_price = floatval($required_selling_price);

						$price_change .= " type " . $rule_type . " markup " . $rule_markup . " min margin " . $min_margin . " initial price " . $required_selling_price;

						if ($rule_type == "A")
							$required_selling_price += floatval($rule_markup * 1.0);
						elseif ($rule_type == "P")
							$required_selling_price = $required_selling_price + ($required_selling_price * $rule_markup);
					}

					$price_change .=  " final price " . $required_selling_price;

					//var_dump($required_selling_price);

					//get product data (clearance)
					$clearance = false;
					$bupdate_price = true;
					if($prod_obj = $this->getProductDao()->get(array("sku"=> $sku)))
					{
						$clearance = $prod_obj->getClearance();
					}

					//if clearance, we dont need to check the minimun margin
					if (!$clearance)
					{
						$json = $this->priceService->getProfitMarginJson($platform_id, $sku, $required_selling_price);
			            //file_put_contents("/var/log/vb-json", "{$soidObj->get_so_no()} || $json", FILE_APPEND);
			            $jj = json_decode($json, true);

			            $new_marging = round($jj["get_margin"], 2);

			            $price_change .=  " new margin " . $new_marging;

			            //check minimun margin
			            if ($min_margin != "" && $new_marging <= $min_margin)
			            	$bupdate_price = false;
		        	}

					if ($bupdate_price)
					{
						$affected = $this->priceService->updateSkuPrice($platform_id, $sku, $required_selling_price, $commit);

						if ($affected)
						{
							if($price_obj = $this->getDao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
							{
								$price_obj->setVbPrice($VB_price);
								if (!$this->priceService->getDao('Price')->update($price_obj))
								{

									$xml[] = '<price>';
									$xml[] = '<sku>' . $price->sku . '</sku>';
									$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
									$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
									$xml[] = '<status>4</status>'; //updated
									$xml[] = '<is_error>' . $price->is_error . '</is_error>';
									$xml[] = '<reason>VB price update error - Affected:' . $price_change . '</reason>';
									$xml[] = '</price>';
								}
								else
								{
									$xml[] = '<price>';
									$xml[] = '<sku>' . $price->sku . '</sku>';
									$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
									$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
									$xml[] = '<status>5</status>'; //updated
									$xml[] = '<is_error>' . $price->is_error . '</is_error>';
									$xml[] = '<reason>' . $price_change . '</reason>';
									$xml[] = '</price>';
								}
							}
							else
							{
								$xml[] = '<price>';
								$xml[] = '<sku>' . $price->sku . '</sku>';
								$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
								$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
								$xml[] = '<status>4</status>'; //not found
								$xml[] = '<is_error>' . $price->is_error . '</is_error>';
								$xml[] = '<reason>Not found - Affected:' . $price_change . '</reason>';
								$xml[] = '</price>';
							}
						}
						else
						{
							$xml[] = '<price>';
							$xml[] = '<sku>' . $price->sku . '</sku>';
							$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
							$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
							$xml[] = '<status>4</status>'; //not updated
							$xml[] = '<is_error>' . $price->is_error . '</is_error>';
							$xml[] = '<reason>Affected:' . $price_change . '</reason>';
							$xml[] = '</price>';
						}
					}
					else
					{
						$xml[] = '<price>';
						$xml[] = '<sku>' . $price->sku . '</sku>';
						$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
						$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
						$xml[] = '<status>6</status>'; //error in margin
						$xml[] = '<is_error>' . $price->is_error . '</is_error>';
						$xml[] = '<reason>Affected:' . $price_change . '</reason>';
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
					$xml[] = '<reason>' . $fail_reason . '</reason>';
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
					$xml[] = '<reason>' . $fail_reason . '</reason>';
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
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</price>';
			}
		 }

		$xml[] = '</prices>';

		$return_feed = implode("\n", $xml);


		return $return_feed;
	}
}