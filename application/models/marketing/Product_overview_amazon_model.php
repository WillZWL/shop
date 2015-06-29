<?php
include_once "product_overview_model.php";

class Product_overview_amazon_model extends Product_overview_model
{

    public function __construct()
    {
        parent::Product_overview_model("AMAZON", 'marketing/pricing_tool_website');
    }

    public function print_overview_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "function CalcProfit(platform,sku, price)
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

                var freight_cost = prod[platform][sku]['freight_cost']*1;
                var duty = prod[platform][sku]['duty_percent'] / 100 * declared;
                duty = duty.toFixed(2)*1;
                var payment = prod[platform][sku]['payment_charge_rate'] / 100 * price;
                var admin_fee = prod[platform][sku]['admin_fee'].toFixed(2);
                var delivery_cost = prod[platform][sku]['delivery_cost']*1;
                var supplier_cost = prod[platform][sku]['supplier_cost']*1;
                var whtrans_cost = prod[platform][sku]['whtrans_cost']*1;
                var fdl = prod[platform][sku]['free_delivery_limit']*1;
                var delivery_charge = prod[platform][sku]['default_delivery_charge']*1;
                delivery_charge = delivery_charge.toFixed(2);

                var commission = 0;
                var commrate = prod[platform][sku]['commrate'];
                var commission = (price * 1+ delivery_charge * 1) * commrate * 1/ 100;
                commission = commission.toFixed(2);

                if (country_id != 'AU' || (country_id == 'AU' && price * 1 > 1000))
                {
                    vat_pcent = prod[platform][sku]['vat_percent']*1;
                    var vat = (declared + delivery_cost*1 + whtrans_cost*1) * vat_pcent  / 100;
                    vat = vat.toFixed(2)*1;
                }
                else
                {
                    vat = 0.00;
                }

                var total = price + delivery_charge;

                total = total * 1;
                var cost =  vat*1 + duty*1 + payment*1 + admin_fee*1 + freight_cost*1 + delivery_cost*1 + supplier_cost*1 + commission*1;
                cost = cost.toFixed(2);
                var profit = price*1 + delivery_charge*1 - cost*1;
                profit = profit.toFixed(2);
                var margin = profit*1 / (price-vat) * 100;
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

/* End of file product_overview_amazon_model.php */
/* Location: ./system/application/models/product_overview_amazon_model.php */
