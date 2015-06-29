<?php

class Product_overview_model extends CI_Model
{
    private $tool_path;

    public function Product_overview_model($platform_type = NULL, $tool_path = 'marketing/pricing_tool')
    {
        parent::__construct();
        $this->tool_path = $tool_path;
        $this->load->library('service/product_service');
        $this->load->library('service/price_extend_service');
        if (is_null($platform_type) && defined('PLATFORM_TYPE')) {
            $platform_type = PLATFORM_TYPE;
        }
        $this->init_price_service($platform_type);
    }

    public function init_price_service($platform_type)
    {
        if (is_null($platform_type)) {
            include_once APPPATH . "libraries/service/Price_service.php";
            $this->price_service = new Price_service();
        } else {
            $filename = "price_" . strtolower($platform_type) . "_service";
            $classname = ucfirst($filename);
            include_once APPPATH . "libraries/service/{$filename}.php";
            $this->price_service = new $classname();
        }
    }

    public function get_product_list($where = array(), $option = array(), $lang = array())
    {
        return $this->price_service->get_product_overview_tr($where, $option, "Product_cost_dto", $lang);
    }

    public function get_product_list_v2($where = array(), $option = array(), $lang = array())
    {
        // optimized
        return $this->price_service->get_product_overview_tr_v2($where, $option, "Product_cost_dto", $lang);
    }

    public function get_product_list_total($where = array(), $option = array())
    {
        return $this->price_service->get_product_overview($where, array_merge($option, array("num_rows" => 1)));
    }

    public function get_product_list_total_v2($where = array(), $option = array())
    {
        // optimized
        return $this->price_service->get_product_overview_v2($where, array_merge($option, array("num_rows" => 1)));
    }

    public function get_product_overview($where = array(), $option = array())
    {
        return $this->price_service->get_product_overview($where, array_merge($option, array("limit" => -1)));
    }

    public function get_product_overview_v2($where = array(), $option = array())
    {
        return $this->price_service->get_product_overview_v2($where, array_merge($option, array("limit" => -1)));
    }

    public function get_list($service, $where = array(), $option = array())
    {
        $service = $service . "_service";
        return $this->$service->get_list($where, $option);
    }

    public function get($service, $where = array())
    {
        $service = $service . "_service";
        return $this->$service->get($where);
    }

    public function get_price($where = array())
    {
        return $this->price_service->get($where);
    }

    public function get_price_ext($where = array())
    {
        return $this->price_extend_service->get($where);
    }

    public function update($service, $obj)
    {
        $service = $service . "_service";
        return $this->$service->update($obj);
    }

    public function update_price($obj)
    {
        return $this->price_service->update($obj);
    }

    public function update_price_ext($obj)
    {
        return $this->price_extend_service->update($obj);
    }

    public function include_vo($service)
    {
        $service = $service . "_service";
        return $this->$service->include_vo();
    }

    public function include_dto($service, $dto)
    {
        $service = $service . "_service";
        return $this->$service->include_dto($dto);
    }

    public function add($service, $obj)
    {
        $service = $service . "_service";
        return $this->$service->insert($obj);
    }

    public function add_price($obj)
    {
        return $this->price_service->insert($obj);
    }

    public function add_price_ext($obj)
    {
        return $this->price_extend_service->insert($obj);
    }

    public function print_overview_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "
            function CalcProfit(platform, sku, price)
            {
                var remote_url;

                if (platform.substring(0, 3).toUpperCase() == 'WEB')
                    remote_url = '/marketing/pricing_tool_website/get_profit_margin_json/' + platform + '/' + sku +'/' + price;

                if (platform.substring(0, 4).toUpperCase() == 'EBAY')
                    remote_url = '/marketing/pricing_tool_ebay/get_profit_margin_json/' + platform + '/' + sku +'/' + price;

                if (platform.substring(0, 5).toUpperCase() == 'QOO10')
                    remote_url = '/marketing/pricing_tool_qoo10/get_profit_margin_json/' + platform + '/' + sku +'/' + price;

                if (platform.substring(0, 4).toUpperCase() == 'FNAC')
                    remote_url = '/marketing/pricing_tool_fnac/get_profit_margin_json/' + platform + '/' + sku +'/' + price;

                if (platform.substring(0, 4).toUpperCase() == 'RAKU')
                    remote_url = '/marketing/pricing_tool_rakuten/get_profit_margin_json/' + platform + '/' + sku +'/' + price;


                if (document.getElementById('auto_price_cb['+platform+']['+ sku + ']') != null)
                {
                    if(document.getElementById('auto_price_cb['+platform+']['+ sku + ']').checked)
                    {
                        document.getElementById('sp['+platform+']['+sku+']').readOnly = true;
                    }
                }

                $.ajax({
                    type: 'POST',
                    // async: false,
                    url: remote_url,
                    // data: '{param1ID:'+ param1Val+'}',
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(msg)
                    {
                        // as multiple calls will be fired to via AJAX, make sure we use only the matched return
                        if (document.getElementById('auto_price_cb['+platform+']['+ sku + ']') != null)
                        {
                            if(document.getElementById('auto_price_cb['+platform+']['+ sku + ']').checked)
                            {
                                document.getElementById('sp['+platform+']['+msg.local_sku+']').readOnly = false;
                                document.getElementById('sp['+platform+']['+msg.local_sku+']').value = msg.get_price;
                                document.getElementById('sp['+platform+']['+msg.local_sku+']').readOnly = true;
                            }
                        }
                        else if(document.getElementById('price[' +platform+ ']['+sku+'][auto_price]') != null)
                        {
                            // for new product overview
                            var autoprice_ele = document.getElementById('price[' +platform+ ']['+sku+'][auto_price]');
                            var autoprice_val =  autoprice_ele.options[autoprice_ele.selectedIndex].value;
                            if(autoprice_val == 'Y')
                            {
                                document.getElementById('sp['+platform+']['+msg.local_sku+']').readOnly = false;
                                document.getElementById('sp['+platform+']['+msg.local_sku+']').value = msg.get_price;
                                document.getElementById('sp['+platform+']['+msg.local_sku+']').readOnly = true;
                            }
                        }
                        else
                        {
                            sp = document.getElementById('sp['+platform+']['+msg.local_sku+']').value;

                            a = parseFloat(sp);
                            b = parseFloat(msg.based_on);
                            if (a != b)
                            {
                                return; // if input price is different, ignore this response
                            }
                        }

                        cost = msg.get_cost;
                        profit = msg.get_profit;
                        margin = msg.get_margin;

                        document.fm_edit.elements['cost['+ sku +']'].value = cost;
                        document.getElementById('profit['+platform+']['+ sku + ']').innerHTML = profit;
                        document.getElementById('margin['+platform+']['+ sku + ']').innerHTML = margin+'%';

                        var hidden_profit = document.getElementById('hidden_profit['+platform+']['+sku+']');
                        if(hidden_profit)
                            hidden_profit.value = profit;
                        var hidden_margin = document.getElementById('hidden_margin['+platform+']['+sku+']');
                        if(hidden_margin)
                            hidden_margin.value = margin;

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

                        return;
                    },
                    error: function(err) {
                        alert('AJAX GET not working, ' + remote_url);
                        if (err.status == 200) {
                            // ParseResult(err);
                        }
                        // else { alert('Error:' + err.responseText + '  Status: ' + err.status); }
                    }
                });
                return true;
            }

            function CheckMargin(platform,sku)
            {
                console.log(prod);
                var sub_cat_margin = prod[platform][sku]['sub_cat_margin'];
                var auto_calc_price = prod[platform][sku]['auto_calc_price'];
                var origin_price = prod[platform][sku]['origin_price'];
                if(document.getElementById('auto_price_cb['+platform+']['+ sku + ']').checked)
                {
                    if(sub_cat_margin > 0)
                    {
                        CalcProfit(platform, sku, 0);
                        document.getElementById('sp['+platform+']['+ sku + ']').readOnly = true;
                        // document.getElementById('sp['+platform+']['+ sku + ']').value = auto_calc_price;
                    }
                    else
                    {
                        alert('Please set the Sub Cat Margin before auto pricing');
                        document.getElementById('auto_price_cb['+platform+']['+ sku + ']').checked = false;
                    }
                }
                else
                {
                    document.getElementById('sp['+platform+']['+ sku + ']').readOnly = false;
                    document.getElementById('sp['+platform+']['+ sku + ']').value = origin_price;
                    CalcProfit(platform,sku, origin_price);
                }
            }

            function CheckMargin_v2(platform,sku)
            {
                var autoprice_ele = document.getElementById('price[' +platform+ ']['+sku+'][auto_price]');
                var autoprice_val =  autoprice_ele.options[autoprice_ele.selectedIndex].value;
                var sub_cat_margin = prod[platform][sku]['sub_cat_margin'];
                var auto_calc_price = prod[platform][sku]['auto_calc_price'];
                var origin_price = prod[platform][sku]['origin_price'];

                if(autoprice_val == 'Y')
                {
                    if(sub_cat_margin > 0)
                    {
                        CalcProfit(platform, sku, 0);
                        document.getElementById('sp['+platform+']['+ sku + ']').readOnly = true;
                        // document.getElementById('sp['+platform+']['+ sku + ']').value = auto_calc_price;
                    }
                    else
                    {
                        alert('Please set the Sub Cat Margin before auto pricing');
                        document.getElementById('auto_price_cb['+platform+']['+ sku + ']').checked = false;
                    }
                }
                else
                {
                    document.getElementById('sp['+platform+']['+ sku + ']').readOnly = false;
                    document.getElementById('sp['+platform+']['+ sku + ']').value = origin_price;
                    CalcProfit(platform,sku, origin_price);
                }
            }
            ";
        echo $js;
    }
}

/* End of file brand_model.php */
/* Location: ./system/application/models/brand_model.php */
?>