<?php
include_once "Product_overview_model.php";

class Product_overview_ebay_model extends Product_overview_model
{

	public function __construct()
	{
		parent::__construct("EBAY", 'marketing/pricing_tool_website');
	}

	public function obsolete_print_overview_js()
	{
		// this function is no longer used, we will reuse the one found in product_overview_model.php
		// all it should do is to query for the values via AJAX and fill them in
		// pricing formula should be contained only within price_service.php

		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		$offset = 60 * 60 * 24;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);
		$js = "
			var listing_fee = ". json_encode($this->price_service->get_listing_fee()) .";
			var ar_commission = ". json_encode($this->price_service->get_ar_commission()) .";
			var paypal_fee_adj = ". $this->price_service->get_paypal_fee_adj() .";

			function CalcProfit(platform,sku, price)
			{
				var cur_price = price;
				price = (price*1).toFixed(2);
				var declared = prod[platform][sku]['declared_rate'] * price / 100;
				declared = declared.toFixed(2)*1;

				if(country_id == 'AU' && declared > 800)
				{
					declared = 800;
				}
				else if(country_id == 'NZ' && declared > 300)
				{
					declared = 300;
				}
				else if(country_id == 'SG' && declared > 315)
				{
					declared = 315;
				}

				var logistic_cost = prod[platform][sku]['logistic_cost']*1;
				var duty = prod[platform][sku]['duty_percent'] / 100 * declared;
				duty = duty.toFixed(2)*1;
				var payment = prod[platform][sku]['payment_charge_rate'] / 100 * price;
				payment = (payment + paypal_fee_adj).toFixed(2)*1;
				var delivery_cost = prod[platform][sku]['delivery_cost']*1;
				var supplier_cost = prod[platform][sku]['supplier_cost']*1;
				var whtrans_cost = prod[platform][sku]['whtrans_cost']*1;
				var fdl = prod[platform][sku]['free_delivery_limit']*1;
				var delivery_charge = prod[platform][sku]['default_delivery_charge']*1;
				delivery_charge = delivery_charge.toFixed(2);
				if(price > fdl)
				{
					delivery_charge = 0.00;
				}

				var cur_listing_fee = 0;
				var total_bound = 0;
				var last_bound = 0;

				if (typeof listing_fee[country_id] != 'undefined')
				{
					var total_bound = listing_fee[country_id]['bound'].length;
					var last_bound = total_bound - 1;

					if (cur_price<=listing_fee[country_id]['bound'][0])
					{
						cur_listing_fee = listing_fee[country_id]['fee'][0];
					}
					else if(cur_price > listing_fee[country_id]['bound'][last_bound])
					{
						cur_listing_fee = listing_fee[country_id]['fee'][last_bound+1];
					}
					else
					{
						for(j=1; j<total_bound; j++)
						{
							if (cur_price<=listing_fee[country_id]['bound'][j])
							{
								cur_listing_fee = listing_fee[country_id]['fee'][j];
								break;
							}
						}
					}
				}
				cur_listing_fee = cur_listing_fee.toFixed(2);

				var commission = 0;
				var total_bound = 0;
				var last_bound = 0;

				if (typeof ar_commission[country_id] != 'undefined')
				{
					var total_bound = ar_commission[country_id]['bound'].length;
					var last_bound = total_bound - 1;

					if (cur_price<=ar_commission[country_id]['bound'][0])
					{
						commission = cur_price*ar_commission[country_id]['pcent'][0], 2;
					}
					else if(cur_price > ar_commission[country_id]['bound'][last_bound])
					{
						commission = ar_commission[country_id]['adj'][last_bound-1] + (cur_price - ar_commission[country_id]['bound'][last_bound-1]) * ar_commission[country_id]['pcent'][last_bound];
					}
					else
					{
						for(j=1; j<total_bound; j++)
						{
							if (cur_price<=ar_commission[country_id]['bound'][j])
							{
								commission = ar_commission[country_id]['adj'][j-1] + (cur_price - ar_commission[country_id]['bound'][j-1]) * ar_commission[country_id]['pcent'][j];
								break;
							}
						}
					}

					if (ar_commission[country_id]['max_charge'] != -1)
					{
						commission = Math.min(commission, ar_commission[country_id]['max_charge']);
					}
				}
				commission = commission.toFixed(2);

				if (country_id != 'AU' || (country_id == 'AU' && price * 1 > 1000))
				{
					vat_pcent = prod[platform][sku]['vat_percent']*1;
					var vat = declared * vat_pcent  / 100;
					vat = vat.toFixed(2)*1;
				}
				else
				{
					vat = 0.00;
				}

				var total = price + delivery_charge;

				total = total * 1;
				var cost =  vat*1 + duty*1 + payment*1 + logistic_cost*1 + supplier_cost*1 + commission*1 + cur_listing_fee * 1;
				cost = cost.toFixed(2);
				var profit = price*1 + delivery_charge*1 - cost*1;
				profit = profit.toFixed(2);
				var margin = profit*1 / price*1 * 100;
				margin = margin.toFixed(2);

				document.fm_edit.elements['cost['+ sku +']'].value = cost;
				document.getElementById('profit['+platform+']['+ sku + ']').innerHTML = profit;
				document.getElementById('margin['+platform+']['+ sku + ']').innerHTML = margin+'%';
				if (profit*1 < 0)
				{
					AddClassName(document.getElementById('profit['+platform+']['+ sku + ']'), 'warn', true);
					AddClassName(document.getElementById('margin['+platform+']['+ sku + ']'), 'warn', true);
				}
				else
				{
					RemoveClassName(document.getElementById('profit['+platform+']['+ sku + ']'), 'warn', true);
					RemoveClassName(document.getElementById('margin['+platform+']['+ sku + ']'), 'warn', true);
				}
			}";
		echo $js;
	}

}

/* End of file product_overview_ebay_model.php */
/* Location: ./system/application/models/product_overview_ebay_model.php */
