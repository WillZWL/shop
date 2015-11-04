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

		//get pricing rules filter
		$where = [];

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

			if (empty($sku)) {
                $xml[] = '<price>';
				$xml[] = '<sku>' . $price->sku . '</sku>';
				$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
				$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
				$xml[] = '<status>2</status>'; //no sku mapping
				$xml[] = '<is_error>' . $price->is_error . '</is_error>';
				$xml[] = '<reason>No SKU mapping</reason>';
				$xml[] = '</price>';
                continue;
			}
			else
			{
				try
				{
					if(!$price_obj = $this->getDao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
					{
						$commit = false;
						// we only commit at the last update
						if ($c <= 0) $commit = true;

						$price_change = "";

						$where["pbv.selling_platform_id"] = $platform_id;
						$where[$required_selling_price . " between pr.range_min and pr.range_max"] = null;

						$data = $this->pricingRulesService->getPricingRulesByPlatform($where);

						$min_margin = "";
						//if exist, apply the pricing rules to the VB price
						//if dont exist rules for the VB price, we use directly the VB price
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
							$clearance = $prod_obj->getClearance();

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
							$affected = $this->priceService->updateSkuPrice($platform_id, $sku, $required_selling_price, $VB_price, $commit);

							if ($affected)
							{
								$reason = "update";
								$result_status = 5;
							}
							else
							{
								$reason = "Not updated";
								$result_status = 3;
							}
						}
						else
						{
							$reason = "Error in margin";
							$result_status = 6;
						}
					}
					else
					{

						$reason = "Not found";
						$result_status = 3;
					}

					$xml[] = '<price>';
					$xml[] = '<sku>' . $price->sku . '</sku>';
					$xml[] = '<master_sku>' . $price->master_sku . '</master_sku>';
					$xml[] = '<platform_id>' . $price->platform_id . '</platform_id>';
					$xml[] = '<status>' . $result_status . '</status>'; //updated
					$xml[] = '<is_error>' . $price->is_error . '</is_error>';
					$xml[] = '<reason>' . $reason . ' - ' . $price_change . '</reason>';
					$xml[] = '</price>';

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
		}

		$xml[] = '</prices>';

		$return_feed = implode("\n", $xml);


		return $return_feed;
	}
}