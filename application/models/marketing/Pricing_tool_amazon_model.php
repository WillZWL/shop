<?php
include_once "pricing_tool_model.php";

class Pricing_tool_amazon_model extends Pricing_tool_model
{

    public function __construct()
    {
        parent::Pricing_tool_model("AMAZON");
        $this->load->library('service/product_condition_service');
        $this->load->library('service/subject_domain_service');
        $this->load->library('service/ixten_reprice_rule_service');

    }

    public function get_amazon_condition_list()
    {
        return $this->product_condition_service->get_list(array("type" => "AMAZON"), array("limit" => -1));
    }

    public function get_list_w_subject($where = array(), $option = array())
    {
        return $this->subject_domain_service->get_list_w_subject($where, $option);
    }

    public function get_ixten_reprice_rule_list($where = array(), $option = array())
    {
        return $this->ixten_reprice_rule_service->get_ixten_reprice_rule_list($where, $option);
    }

    public function print_pricing_tool_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "  function rePrice(platform,typeInput)
                {
                    var obj;
                    var type;
                    for(var i =0; i <shiptype.length;i++)
                    {
                        obj = document.getElementById('sp['+platform+']['+shiptype[i]+']');
                        obj.value = document.getElementById('sp['+platform+']['+typeInput+']').value;

                        type = shiptype[i];

                        var price = document.getElementById('sp['+platform+']['+type+']').value * 1;
                        price = price.toFixed(2);
                        var freight_cost = document.getElementById('freight_cost['+platform+']['+type+']').innerHTML * 1;
                        freight_cost = freight_cost.toFixed(2);
                        var whtrans_cost = document.getElementById('default_freight_cost['+platform+']['+type+']').value * 1

                        if(type == 4)
                        {
                            var efn_cost;
                            var fc_id = document.getElementById('price_ext['+platform+'][fulfillment_centre_id]').value;
                            if(fc_id == 'AMAZON_EU' && platform == 'AMFR')
                            {
                                efn_cost = 2.4;
                            }
                            else if(fc_id == 'AMAZON_EU' && platform == 'AMDE')
                            {
                                efn_cost = 2.6;
                            }
                            else
                            {
                                efn_cost = 0;
                            }
                            whtrans_cost = document.getElementById('default_freight_cost['+platform+']['+type+']').value * 1 + efn_cost;
                        }
                        whtrans_cost = whtrans_cost.toFixed(2);

                        var delivery_cost = document.getElementById('delivery_cost['+platform+']['+type+']').innerHTML * 1;
                        delivery_cost = delivery_cost.toFixed(2);

                        if(type == 4)
                        {
                            var unit_cost;
                            var weight_cost;
                            var fba_cost;

                            var prod_weight = document.getElementById('prod_weight['+platform+']['+type+']').value;
                            switch(platform)
                            {
                                case 'AMUK':
                                    if(price < 300)
                                    {
                                        unit_cost = 0.25 + 0.6;
                                        if(prod_weight <= 2)
                                        {
                                            weight_cost = Math.ceil(prod_weight/0.1)*0.1;
                                        }
                                        else
                                        {
                                            weight_cost = Math.ceil((prod_weight - 2)/0.1)*0.02;
                                        }
                                        fba_cost = unit_cost + weight_cost;
                                    }
                                    else
                                    {
                                        fba_cost = 0;
                                    }
                                    break;
                                case 'AMFR':
                                    if(price < 350)
                                    {
                                        unit_cost = 1 + 0.75;
                                        if(prod_weight < 0.25)
                                        {
                                            weight_cost = 0.5;
                                        }
                                        else if((prod_weight >= 0.25) && (prod_weight < 0.5))
                                        {
                                            weight_cost = 1;
                                        }
                                        else
                                        {
                                            weight_cost = 1 + Math.ceil((prod_weight - 0.5)/1)*1.25;
                                        }
                                        fba_cost = unit_cost + weight_cost;
                                    }
                                    else
                                    {
                                        fba_cost = 0;
                                    }
                                    break;
                                case 'AMDE':
                                    if(price < 350)
                                    {
                                        unit_cost = 1 + 0.7;
                                        switch(true)
                                        {
                                            case (prod_weight < 0.1):
                                                weight_cost = 0;
                                                break;
                                            case (prod_weight >= 0.1 && prod_weight < 0.5):
                                                weight_cost = 0.5;
                                                break;
                                            case (prod_weight >= 0.5 && prod_weight < 1):
                                                weight_cost = 0.6;
                                                break;
                                            case (prod_weight >= 1 && prod_weight < 2):
                                                weight_cost = 1.8;
                                                break;
                                            case (prod_weight >= 2 && prod_weight < 5):
                                                weight_cost = 2.3;
                                                break;
                                            case (prod_weight >= 5 && prod_weight < 10):
                                                weight_cost = 3.3;
                                                break;
                                            case (prod_weight >= 10):
                                                weight_cost = 6.3;
                                                break;
                                        }
                                        fba_cost = unit_cost + weight_cost;
                                    }
                                    else
                                    {
                                        fba_cost = 0;
                                    }
                                    break;
                                case 'AMUS':
                                    var weight_in_ounce;
                                    var weight_in_pound;
                                    var kg_to_ounce_rate = 35.274;

                                    if(price < 25)
                                    {
                                        unit_cost = 1 + 0.75;

                                        weight_in_ounce = Math.round(prod_weight * kg_to_ounce_rate);
                                        weight_in_pound = weight_in_ounce/16;
                                        weight_cost = weight_in_pound*0.4;

                                        fba_cost = unit_cost + weight_cost;
                                    }
                                    else if(price >= 25 && price < 300)
                                    {
                                        unit_cost = 1 + 1;

                                        weight_in_ounce = Math.round(prod_weight * kg_to_ounce_rate);
                                        weight_in_pound = weight_in_ounce/16;
                                        weight_cost = weight_in_pound*0.4;

                                        fba_cost = unit_cost + weight_cost;
                                    }
                                    else
                                    {
                                        fba_cost = 0;
                                    }
                                    break;
                                default:
                            }

                            fba_cost = fba_cost.toFixed(2);
                        }

                        var ddc = document.getElementById('default_delivery_charge['+platform+']['+type+']').value*1;
                        var delivery_charge = (fdl > 0 && price > fdl?0:(ddc*1));
                        var declared_pcent = document.getElementById('declared_rate['+platform+']['+type+']').value * 1 ;

                        var declared = price * 1 * declared_pcent * 1/ 100 ;
                        var country_id = document.getElementById('country_id['+platform+']['+type+']').value;
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

                        declared = declared.toFixed(2);
                        var comm_rate = document.getElementById('commrate['+platform+']['+type+']').value;
                        var commission = (price * 1+ delivery_charge * 1) * comm_rate * 1/ 100;
                        commission = commission.toFixed(2)
                        var vat_pcent = document.getElementById('vat_percent['+platform+']['+type+']').value * 1;
                        var vat = (declared * 1 + freight_cost * 1 + whtrans_cost * 1 + delivery_cost * 1) * vat_pcent /100;
                        vat = vat.toFixed(2);
                        var duty = document.getElementById('duty_percent['+platform+']['+type+']').value / 100 * declared * 1;
                        duty = duty.toFixed(2);
                        var payment = document.getElementById('payment_charge_rate['+platform+']['+type+']').value / 100 * price * 1;
                        payment = payment.toFixed(2);
                        var admin_fee = document.getElementById('admin_fee['+platform+']['+type+']').innerHTML * 1;
                        admin_fee = admin_fee.toFixed(2);

                        var supplier_cost = document.getElementById('scost['+platform+']['+type+']').value * 1;
                        supplier_cost = supplier_cost.toFixed(2);
                        var fdl = document.getElementById('free_delivery_limit['+platform+']['+type+']').value*1;
                        delivery_charge = delivery_charge.toFixed(2);

                        var total = price *1 + delivery_charge * 1;
                        total = total.toFixed(2);
                        var cost =  vat *1+ duty *1+ payment *1+ admin_fee *1+ freight_cost *1+ delivery_cost *1+ whtrans_cost*1+ supplier_cost *1+commission * 1;
                        cost = cost.toFixed(2);
                        var profit = total *1 - cost * 1 ;
                        profit = profit.toFixed(2);
                        var margin = 100 * profit * 1 / (price*1 - vat*1) ;
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
                        var rowo = document.getElementById('row['+platform+']['+type+']');
                        rowo.style.backgroundColor = color;

                        var declo = document.getElementById('declare['+platform+']['+type+']');
                        if (declo != null) declo.innerHTML = declared;
                        var vato = document.getElementById('vat['+platform+']['+type+']');
                        if (vato != null) vato.innerHTML = vat;

                        var dutyo = document.getElementById('duty['+platform+']['+type+']');
                        dutyo.innerHTML = duty;
                        var pmo = document.getElementById('pc['+platform+']['+type+']');
                        pmo.innerHTML = payment;
                        var dco = document.getElementById('delivery_charge['+platform+']['+type+']');
                        dco.innerHTML = delivery_charge;
                        var comm = document.getElementById('comm['+platform+']['+type+']');
                        comm.innerHTML = commission;
                        var tco = document.getElementById('total_cost['+platform+']['+type+']');
                        tco.innerHTML = cost;
                        var ttlo = document.getElementById('total['+platform+']['+type+']');
                        ttlo.innerHTML = total;
                        var pfto = document.getElementById('profit['+platform+']['+type+']');
                        pfto.innerHTML = profit;
                        var mgno = document.getElementById('margin['+platform+']['+type+']');
                        mgno.innerHTML = margin + '%';

                        if(type == 4)
                        {
                            var dcoo = document.getElementById('delivery_cost['+platform+']['+type+']');
                            dcoo.innerHTML = fba_cost;
                            var whco = document.getElementById('whtrans_cost['+platform+']['+type+']');
                            whco.innerHTML = whtrans_cost;
                        }
                    }
                }";
        echo $js;
    }
}
/* End of file pricing_tool_amazon_model.php */
/* Location: ./system/application/models/pricing_tool_amazon_model.php */
