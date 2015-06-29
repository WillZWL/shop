<?php
include_once "pricing_tool_model.php";

class Pricing_tool_ebay_model extends Pricing_tool_model
{

    public function __construct()
    {
        parent::Pricing_tool_model("EBAY");
    }

    public function obsolete_print_pricing_tool_js()
    {
        // this function is no longer used, we will reuse the one found in pricing_tool_model.php
        // all it should do is to query for the values via AJAX and fill them in
        // pricing formula should be contained only within price_service.php

        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "var listing_fee = " . json_encode($this->price_service->get_listing_fee()) . ";
                var ar_commission = " . json_encode($this->price_service->get_ar_commission()) . ";
                var paypal_fee_adj = " . $this->price_service->get_paypal_fee_adj() . ";

                function rePrice(platform)
                {
                    var obj;

                    obj = document.getElementById('sp['+platform+']');
                    obj.value = document.getElementById('sp['+platform+']').value;

                    var price = document.getElementById('sp['+platform+']').value * 1;
                    var cur_price = price;
                    price = price.toFixed(2);
                    var logistic_cost = document.getElementById('logistic_cost['+platform+']').innerHTML * 1;
                    logistic_cost = logistic_cost.toFixed(2);
                    var ddc = document.getElementById('default_delivery_charge['+platform+']').value*1;
                    var delivery_charge = document.getElementById('delivery_charge['+platform+']').innerHTML * 1;
                    var declared_pcent = document.getElementById('declared_rate['+platform+']').value * 1 ;

                    var declared = price * 1 * declared_pcent * 1/ 100 ;
                    var country_id = document.getElementById('country_id['+platform+']').value;

                    var total = price *1 + delivery_charge * 1;
                    total = total.toFixed(2);

                    if(country_id == 'AU' && declared > 950)
                    {
                        declared = 950;
                    }
                    else if(country_id == 'SG' && declared > 350)
                    {
                        declared = 350;
                    }
                    else if(country_id == 'NZ')
                    {
                        if(total < 400)
                        {
                            declared_pcent = 100;
                            declared = total * declared_pcent * 1/ 100 ;
                        }
                        else
                        {
                            declared_pcent = 80;
                            declared = total * declared_pcent * 1/ 100 ;
                        }
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

                    declared = declared.toFixed(2);
                    if (country_id != 'AU' || (country_id == 'AU' && price * 1 > 1000))
                    {
                        var vat_pcent = document.getElementById('vat_percent['+platform+']').value * 1;
                        var vat = (declared * 1) * vat_pcent /100;
                        vat = vat.toFixed(2);
                    }
                    else
                    {
                        vat = 0;
                        vat = vat.toFixed(2);
                    }

                    if(country_id == 'NZ')
                    {
                        if(price > 400)
                        {
                            vat_pcent = 15;
                            var vat = declared * 1 * vat_pcent /100 + 38.07;
                        }
                        else
                        {
                            vat_pcent = 0;
                            var vat = declared * 1 * vat_pcent /100;
                        }
                        vat = vat.toFixed(2);
                    }

                    var duty = document.getElementById('duty_percent['+platform+']').value / 100 * declared * 1;
                    duty = duty.toFixed(2);
                    var payment = document.getElementById('payment_charge_rate['+platform+']').value / 100 * cur_price * 1;
                    payment = (payment + paypal_fee_adj).toFixed(2);

                    var supplier_cost = document.getElementById('scost['+platform+']').value * 1;
                    supplier_cost = supplier_cost.toFixed(2);
                    var fdl = document.getElementById('free_delivery_limit['+platform+']').value*1;
                    var delivery_charge = (fdl > 0 && price > fdl?0:(ddc*1));
                    //var delivery_charge = document.getElementById('delivery_charge['+platform+']').innerHTML * 1;
                    delivery_charge = delivery_charge.toFixed(2);

                    var total = price *1 + delivery_charge * 1;
                    total = total.toFixed(2);
                    var cost =  vat *1+ duty *1+ payment *1+ logistic_cost*1+ supplier_cost *1+commission * 1 + cur_listing_fee * 1;
                    cost = cost.toFixed(2);
                    var profit = price *1+ delivery_charge *1- cost*1;
                    profit = profit.toFixed(2);
                    var margin = profit *1/ (price *1) * 100;
                    margin = margin.toFixed(2);

                    var color = '';
                    if(profit > 0)
                    {
                        color = '#ddffdd';
                    }
                    else
                    {
                        color = '#ffdddd';
                    }
                    var rowo = document.getElementById('row['+platform+']');
                    rowo.style.backgroundColor = color;

                    var declo = document.getElementById('declare['+platform+']');
                    if (declo != null) declo.innerHTML = declared;
                    var vato = document.getElementById('vat['+platform+']');
                    if (vato != null) vato.innerHTML = vat;

                    var dutyo = document.getElementById('duty['+platform+']');
                    dutyo.innerHTML = duty;
                    var pmo = document.getElementById('pc['+platform+']');
                    pmo.innerHTML = payment;
                    var lso = document.getElementById('listing_fee['+platform+']');
                    lso.innerHTML = cur_listing_fee;
                    var dco = document.getElementById('delivery_charge['+platform+']');
                    dco.innerHTML = delivery_charge;
                    var comm = document.getElementById('comm['+platform+']');
                    comm.innerHTML = commission;
                    var tco = document.getElementById('total_cost['+platform+']');
                    tco.innerHTML = cost;
                    var ttlo = document.getElementById('total['+platform+']');
                    ttlo.innerHTML = total;
                    var pfto = document.getElementById('profit['+platform+']');
                    pfto.innerHTML = profit;
                    var mgno = document.getElementById('margin['+platform+']');
                    mgno.innerHTML = margin + '%';

                }";
        echo $js;
    }

}
/* End of file pricing_tool_ebay_model.php */
/* Location: ./system/application/models/pricing_tool_ebay_model.php */
