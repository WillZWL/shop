<?php

class Pricing_tool_model extends CI_Model
{

    public function Pricing_tool_model($platform_type = NULL)
    {
        parent::__construct();
        $this->load->library('service/product_service');
        $this->load->library('service/inventory_service');
        $this->load->library('service/shiptype_service');
        $this->load->library('service/warehouse_service');
        $this->load->library('service/currency_service');
        $this->load->library('service/so_service');
        $this->load->library('service/product_note_service');
        $this->load->library('service/freight_cat_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/supplier_service');
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
            include_once APPPATH . "libraries/service/{$classname}.php";
            $this->price_service = new $classname();
        }
    }

    public function __autoload()
    {
        $this->price_service->get_dao()->include_vo();
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where = array(), $option = array())
    {
        $option["num_rows"] = 1;
        return $this->product_service->get_dao()->get_list_w_name($where, $option);
    }

    public function get_prod($sku = "")
    {
        if ($sku != "") {
            return $this->product_service->get_dao()->get(array("sku" => $sku));
        } else {
            return $this->product_service->get_dao()->get();
        }
    }

    public function __autoload_product_vo()
    {
        $this->product_service->get_dao()->include_vo();
    }

    public function add($obj)
    {
        return $this->price_service->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->price_service->get_dao()->update($obj);
    }

    public function get_price_obj($where = array())
    {
        if (empty($where)) {
            return $this->price_service->get_dao()->get();
        } else {
            return $this->price_service->get_dao()->get($where);
        }
    }

    public function get_mapping_obj($where = array())
    {
        return $this->product_service->get_master_sku($where);
    }

    public function get_shiptype_list($platform_type = 'WEBSITE')
    {
        return $this->shiptype_service->get_dao()->get_platform_shiptype_list($platform_type);
    }

    public function get_product_cost_dto($sku, $platform)
    {
        return $this->price_service->get_dao()->get_price_cost_dto($sku, $platform);
    }

    public function get_currency($platform_id = "")
    {
        $tmp = $this->platform_biz_var_service->get_dao()->get(array("selling_platform_id" => $platform_id));
        $ret = $this->get_currency_detail($tmp->get_platform_currency_id());
        unset($tmp);
        return $ret;
    }

    public function get_currency_detail($id = "")
    {
        return $this->currency_service->get_dao()->get(array("id" => $id));
    }

    public function update_product($obj)
    {
        return $this->product_service->get_dao()->update($obj);
    }

    public function get_note($sku = "", $type = "")
    {
        if ($sku == "") {
            return $this->product_note_service->get_dao()->get();
        } else {
            return $this->product_note_service->get_dao()->get_note_with_author_name($type == "M" ? "WSGB" : "", $sku, $type);
        }
    }

    public function add_note($obj)
    {
        return $this->product_note_service->get_dao()->insert($obj);
    }

    public function get_inventory($where = array())
    {
        return $this->inventory_service->get_inventory($where);
    }

    public function get_quantity_in_orders($sku = "")
    {
        $ret[7] = $this->so_service->get_dao()->get_quantity_in_orders($sku, 7);
        $ret[30] = $this->so_service->get_dao()->get_quantity_in_orders($sku, 30);
        return $ret;
    }

    public function get_current_supplier($sku = "")
    {
        return $this->product_service->get_dao()->get_current_supplier($sku);
    }

    public function get_total_default_supplier($sku = "")
    {
        return $this->product_service->get_dao()->get_total_default_supplier($sku);
    }

    public function get_freight_cat($id = "")
    {
        return $this->freight_cat_service->get_dao()->get(array("id" => $id));
    }

    public function get_pricing_data($platform_id = "", $prod = "", $app_id = null)
    {
        return $this->price_service->get_pricing_tool_info($platform_id, $prod, $app_id);
    }

    public function get_pricing_tool_info($platform_id = "", $prod = "", $app_id = null)
    {
        return $this->price_service->get_pricing_tool_info($platform_id, $prod, $app_id);
    }

    public function print_pricing_tool_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "
        var result_recv = false;

        function rePrice(platform, sku)
                {
                    var remote_url;
                    var sp = document.getElementById('sp['+platform+']').value * 1;


                    if (platform.substring(0, 3).toUpperCase() == 'WEB')
                    {
                        var auto_price = document.getElementById('auto_price_cb['+platform+']');
                        var selected_auto_price = auto_price.options[auto_price.selectedIndex].value;

                        if (selected_auto_price != null)
                        {
                            if (selected_auto_price == 'Y')
                            {
                                document.getElementById('sp['+platform+']').value = 0;
                                sp = 0;
                            }
                        }

                        remote_url = '/marketing/pricing_tool_website/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;
                    }
                    else
                    {
                        if (document.getElementById('auto_price_cb['+platform+']') != null)
                        {
                            if (document.getElementById('auto_price_cb['+platform+']').checked == true)
                            {
                                document.getElementById('sp['+platform+']').value = 0;
                                sp = 0;
                            }
                        }

                    }

                    if (platform.substring(0, 4).toUpperCase() == 'EBAY')
                        remote_url = '/marketing/pricing_tool_ebay/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;

                    if (platform.substring(0, 5).toUpperCase() == 'QOO10')
                        remote_url = '/marketing/pricing_tool_qoo10/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;

                    if (platform.substring(0, 4).toUpperCase() == 'RAKU')
                        remote_url = '/marketing/pricing_tool_rakuten/get_profit_margin_json/' + platform + '/' + sku +'/' + sp;

                    result_recv = false;

                    $.ajax({
                        // async: false,
                        // data: '{param1ID:'+ param1Val+'}',

                        type: 'POST',
                        url: remote_url,
                        contentType: 'application/json; charset=utf-8',
                        dataType: 'json',
                        success: function(msg)
                        {
                            declared = msg.get_declared_value;
                            vat = msg.get_vat;

                            duty = msg.get_duty;
                            payment = msg.get_payment_charge;
                            forex_fee = msg.get_forex_fee;
                            delivery_charge = msg.get_delivery_cost;
                            commission = msg.get_sales_commission;

                            cost = msg.get_cost;
                            total = msg.get_price;
                            profit = msg.get_profit;
                            margin = msg.get_margin;
                            if (!result_recv)
                            {
                                sp = document.getElementById('sp['+platform+']').value * 1;
                                a = parseFloat(sp);
                                b = parseFloat(msg.based_on);

                                if (a != b)
                                {
                                    var pfto = document.getElementById('profit['+platform+']');
                                    pfto.innerHTML = 'Wait...';
                                    var mgno = document.getElementById('margin['+platform+']');
                                    mgno.innerHTML = '-';

                                    var hidden_profit = document.getElementById('hidden_profit['+platform+']');
                                    hidden_profit.value = profit;
                                    var hidden_margin = document.getElementById('hidden_margin['+platform+']');
                                    hidden_margin.value = margin;

                                    return; // if input price is different, ignore this response
                                }
                            }
                            else
                                return; // we already received the result, return

                            result_recv = true;

                            if (platform.substring(0, 3).toUpperCase() == 'WEB')
                            {
                                if (selected_auto_price != null)
                                {
                                    if(selected_auto_price == 'Y')
                                    {
                                        document.getElementById('sp['+platform+']').readOnly = false;
                                        document.getElementById('sp['+platform+']').value = total;
                                        document.getElementById('sp['+platform+']').readOnly = true;
                                    }
                                }
                            }
                            else
                            {
                                if (document.getElementById('auto_price_cb['+platform+']') != null)
                                {
                                    if(document.getElementById('auto_price_cb['+platform+']').checked == true)
                                    {
                                        document.getElementById('sp['+platform+']').readOnly = false;
                                        document.getElementById('sp['+platform+']').value = total;
                                        document.getElementById('sp['+platform+']').readOnly = true;
                                    }
                                }
                            }

                            if(margin > 0)
                                color = '#ddffdd';
                            else
                                color = '#ffdddd';

                            if(platform.substring(0, 5).toUpperCase() == 'QOO10')
                            {
                                if(margin < 5)
                                {
                                    alert('Warning: The profit margin at the selected selling price is less than 5%. Please check and confirm.');
                                }
                                if(sp==0 || sp =='')
                                {
                                    // disable_element('update_pricing_tool');
                                    alert('Warning: Selling Price cannot be empty or zero. Please amend.')
                                }
                                else
                                {
                                    enable_element('update_pricing_tool');
                                }
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
                            var ffo = document.getElementById('forex_fee['+platform+']');
                            ffo.innerHTML = forex_fee;
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

                            var hidden_profit = document.getElementById('hidden_profit['+platform+']');
                            hidden_profit.value = profit;
                            var hidden_margin = document.getElementById('hidden_margin['+platform+']');
                            hidden_margin.value = margin;
                        },
                        error: function(err) {
                            // alert('AJAX GET not working, ' + remote_url);
                            if (err.status == 200) {
                                // ParseResult(err);
                            }
                            // else { alert('Error:' + err.responseText + '  Status: ' + err.status); }
                        }
                    });
                    return true;

                }";
        echo $js;
    }

    public function get_profit_margin_json($platform_id, $sku, $required_selling_price, $required_cost_price = -1)
    {
        return $this->price_service->get_profit_margin_json($platform_id, $sku, $required_selling_price, $required_cost_price);
    }

    public function check_valid_supplier_cost($sku)
    {
        return $this->supplier_service->check_valid_supplier_cost($sku);
    }
}