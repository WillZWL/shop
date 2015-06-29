<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Price_service extends Base_service
{
    private $supplier_service;
    private $country_service;
    private $factory_service;
    private $product_service;
    private $platform_biz_var_service;
    private $exchange_rate_service;
    private $shiptype_service;
    private $price_margin_service;
    private $fc_service;
    private $config;
    private $price_ext_dao;
    private $sub_domain_srv;
    private $wms_wh_srv;
    private $ip_country_srv;
    private $price_margin_srv;
    private $product_update_followup_service;
    private $ca_dao;

    private $tool_path;

    private $dto;
    private $ip_country = NULL;

    private $notification_email = "itsupport@eservicesgroup.net";


    public function __construct($tool_path = 'marketing/pricing_tool')
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Price_dao.php");
        $this->set_dao(new Price_dao());
        include_once(APPPATH."libraries/dao/Price_extend_dao.php");
        $this->set_price_ext_dao(new Price_extend_dao());
        include_once(APPPATH."libraries/dao/Product_complementary_acc_dao.php");
        $this->set_ca_dao(new Product_complementary_acc_dao());
        include_once(APPPATH."libraries/service/Class_factory_service.php");
        $this->set_factory_service(new Class_factory_service());
        include_once(APPPATH."libraries/service/Country_service.php");
        $this->set_country_service(new Country_service());
        include_once(APPPATH."libraries/service/Supplier_service.php");
        $this->set_supplier_service(new Supplier_service());
        include_once(APPPATH."libraries/service/Product_service.php");
        $this->set_product_service(new Product_service());
        include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
        $this->set_platform_biz_var_service(new Platform_biz_var_service());
        include_once(APPPATH."libraries/service/Exchange_rate_service.php");
        $this->set_exchange_rate_service(new Exchange_rate_service());
        include_once(APPPATH."libraries/service/Shiptype_service.php");
        $this->set_shiptype_service(new Shiptype_service());
        include_once(APPPATH."libraries/service/Price_margin_service.php");
        $this->set_price_margin_service(new Price_margin_service());
        include_once(APPPATH."libraries/service/Weight_cat_service.php");
        $this->set_wc_srv(new Weight_cat_service());
        include_once(APPPATH."libraries/service/Freight_cat_service.php");
        $this->set_fc_srv(new Freight_cat_service());
        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        $this->set_tool_path($tool_path);
        include_once APPPATH."libraries/service/Subject_domain_service.php";
        $this->set_sub_domain_srv(new Subject_domain_service());
        include_once(APPPATH."libraries/service/Wms_warehouse_service.php");
        $this->set_wms_wh_srv(new Wms_warehouse_service());
        include_once(APPPATH."libraries/service/Ip2country_service.php");
        $this->set_ip_country_srv(new Ip2country_service());
        include_once(APPPATH."libraries/service/Price_margin_service.php");
        $this->set_price_margin_srv(new Price_margin_service());
        include_once(APPPATH."libraries/service/Product_update_followup_service.php");
        $this->set_product_update_followup_service(new Product_update_followup_service());
    }

    public function get_ip_country_srv()
    {
        return $this->ip_country_srv;
    }

    public function set_ip_country_srv(Base_service $svc)
    {
        $this->ip_country_srv = $svc;
    }

    public function get_wms_wh_srv()
    {
        return $this->wms_wh_srv;
    }

    public function set_wms_wh_srv(Base_service $svc)
    {
        $this->wms_wh_srv = $svc;
    }

    public function get_website_price($sku, $platform_id)
    {
        return $this->get_dao()->get(array("sku"=>$sku,"platform_id"=>$platform_id));
    }

    public function get_website_display_price($sku, $platform_id)
    {
        return $this->get_dao()->get_website_display_price($sku, $platform_id);
    }

    public function get_price_service($type="", $set = array())
    {
        if($type == "")
        {
            return FALSE;
        }
        else
        {
            return $this->get_factory_service()->get_price_service($type, $set);
        }
    }

    public function get_price_margin_srv()
    {
        return $this->price_margin_srv;
    }

    public function set_price_margin_srv(Base_service $svc)
    {
        $this->price_margin_srv = $svc;
    }

    public function get_product_update_followup_service()
    {
        return $this->product_update_followup_service;
    }

    public function set_product_update_followup_service(Base_service $svc)
    {
        $this->product_update_followup_service = $svc;
    }

    public function get_price_service_from_dto(&$dto, $price = "")
    {
        $country_obj = $this->get_country_service()->get_dao()->get(array("id"=>$dto->get_platform_country_id()));
        $supplier_obj = $this->get_supplier_service()->get_dao()->get(array("id"=>$dto->get_supplier_id()));
    //  $set = array("supplier_region"=>@call_user_func(array($supplier_obj, "get_supplier_reg")),"supplier_fc"=>@call_user_func(array($supplier_obj, "get_warehouse_id")),"customer_fc"=>$country_obj->get_warehouse_id(),"ccountry"=>$country_obj->get_id(),"weight_cat"=>$dto->get_freight_cat_id());
        $set = array("supplier_region"=>@call_user_func(array($supplier_obj, "get_supplier_reg")),"supplier_fc"=>@call_user_func(array($supplier_obj, "get_fc_id")),"customer_fc"=>@call_user_func(array($country_obj, "get_fc_id")),"ccountry"=>@call_user_func(array($country_obj, "get_id")),"weight_cat"=>$dto->get_freight_cat_id(),"price"=>$price);

        return $this->get_factory_service()->get_price_service($dto->get_shiptype_name(), $set);
    }

    public function get_pricing_tool_info($platform_id = "", $sku = "", $app_id = null)
    // public function get_pricing_tool_info($platform_id = "", $sku = "")
    {
        if($platform_id != "" && $sku != "")
        {
            $ret = array();
            if (!(($pbv_obj = $this->get_platform_biz_var_service()->get(array("selling_platform_id"=>$platform_id)))
                &&
                ($country_obj = $this->get_country_service()->get(array("id"=>$pbv_obj->get_platform_country_id())))
                &&
                ($prod_obj = $this->get_product_service()->get(array("sku"=>$sku)))
                ))
            {
                return FALSE;
            }

            $pcurr = $pbv_obj->get_platform_currency_id();
            $supplier_obj = $this->get_supplier_service()->get_dao()->get_supplier($sku);

            $price_obj = $this->get_dao()->get(array("platform_id"=>$platform_id, "sku"=>$sku));

            $tmp = $this->get_dao()->get_price_cost_dto($sku, $platform_id);

            // we must set the declare_pcent correctly here in order for the javascript to calculate correctly

            // if ($tmp->get_platform_id() == "EBAYSG")

            if (!$price_obj)
            {
                $tmp->set_price($this->get_price($tmp));
                $tmp->set_current_platform_price(NULL);
                $tmp->set_default_platform_converted_price($tmp->get_price());
            }
            else
            {
                $tmp->set_current_platform_price($tmp->get_price());
            }

            $this->check_dto_price($tmp);
            $this->calc_logistic_cost($tmp);

            $this->calculate_profit($tmp);

            if ($tmp->get_platform_country_id() == "GB")
                $tmp->set_declared_pcent(30);
            else
            {
                $price = $tmp->get_price();
                switch ($tmp->get_platform_country_id())
                {
                    case "AU":
                        // if ($price < 950)
                            $declared_pcent = 100;
                        // else
                            // $max_declared_value = 950;
                        break;

                    case "SG":
                        // if ($price < 350)
                            $declared_pcent = 100;
                        // else
                            // $max_declared_value = 350;
                        break;

                    case "NZ":
                        if ($price < 400)
                            $declared_pcent = 100;
                        else
                            $declared_pcent = 80;
                        break;
                    default:
                        $declared_pcent = 10;
                        break;
                }
                $tmp->set_declared_pcent($declared_pcent);
            }
            // { echo "price_service, line 141<br>"; var_dump($tmp); die(); }

            $header = $this->draw_table_header_row($tmp, $app_id);
            $ret["header"] = $header;

            $tmp->set_listing_status($price_obj?$price_obj->get_listing_status():"N");
            $ret["dst"] = $tmp;

            $content = $this->draw_table_row_for_pricing_tool($tmp, $app_id);
            $ret["content"] = $content;
            unset($tmp);
            unset($p_srv);

            return $ret;
        }
        else
        {
            return FALSE;
        }
    }

    public function check_dto_price($dto = NULL)
    {
        $this->init_dto($dto);

        if ($dto->get_price())
        {
            $price = $dto->get_price();
        }
        else
        {
            $price_obj = $this->get(array("sku"=>$dto->get_sku(), "platform_id"=>$dto->get_platform_id()));
            if($price_obj)
            {
                $price = $price_obj->get_price();
                $dto->set_current_platform_price($price);
                if (!($price*1))
                {
                    if ($default_obj = $this->get_dao()->get_default_converted_price(array("sku"=>$dto->get_sku(), "platform_id"=>$dto->get_platform_id())))
                    {
                        $default_platform_converted_price = $default_obj->get_default_platform_converted_price();
                        $price = $default_platform_converted_price;
                        $dto->set_default_platform_converted_price($price);
                    }
                }
                $dto->set_price($price);
            }
            else
            {
                $dto->set_price(0);
                $price = 0;
            }
        }
    }

    public function calculate_profit($dto = null)
    {
        $this->init_dto($dto);
        $this->check_dto_price();
        $this->calc_delivery_charge();
        $this->calc_cost();

        $price = $dto->get_price();

        $this->perform_business_logic($dto, 5, $price);
        return;

        $profit = $price + $dto->get_delivery_charge() - $dto->get_cost();

        if($price > 0)
        {
            $margin = number_format($profit / ($price + $dto->get_delivery_charge()) * 100, 2, ".", "");
        }
        else
        {
            $margin = 0;
        }
        $dto->set_profit(number_format($profit, 2, ".", ""));
        $dto->set_margin($margin);
    }

    public function calc_dto_data($dto = NULL)
    {
        $this->init_dto($dto);
        $this->calc_declared_value();
        $this->calc_commission();
        $this->calc_duty();
        $this->calc_payment_charge();
        $this->calc_forex_fee();
        $this->calc_vat();
        $this->calc_auto_price_value();
    }

    public function calc_cost($dto = NULL)
    {
        $this->init_dto($dto);
        $this->calc_dto_data();
        $dto->set_cost(number_format($dto->get_vat()
                        + $dto->get_supplier_cost()
                        + $dto->get_admin_fee()
                        + $dto->get_logistic_cost()
                        + $dto->get_payment_charge()
                        + $dto->get_forex_fee()
                        + $dto->get_sales_commission()
                        + $dto->get_listing_fee()
                        + $dto->get_duty(), 2, ".", ""));
    }

    public function calc_commission($dto = NULL)
    {
        $this->init_dto($dto);
        $dto->set_sales_commission(number_format(($dto->get_price() + $dto->get_delivery_charge()) * $dto->get_platform_commission()/100, 2, ".", ""));
    }

    public function calc_payment_charge($dto = NULL)
    {
        $this->init_dto($dto);
        $dto->set_payment_charge(number_format(($dto->get_price() + $dto->get_delivery_charge()) * $dto->get_payment_charge_percent()/100, 2, ".", ""));
    }

    public function calc_forex_fee($dto = NULL)
    {
        $this->init_dto($dto);
        $dto->set_forex_fee(number_format(($dto->get_price() + $dto->get_delivery_charge()) * $dto->get_forex_fee_percent()/100, 2, ".", ""));
    }
/*
    public function calc_delivery_charge($dto = NULL)
    {
        $this->init_dto($dto);

        if ($this->product_service->get_pt_dao()->get_num_rows(array("sku"=>$dto->get_sku(), "type_id"=>"VIRTUAL")))
        {
            $delivery_charge = 0;
        }
        else
        {
            $delivery_charge = $this->get_wc_srv()->get_wcc_dao()->get_country_weight_charge_by_platform($dto->get_platform_id(), $dto->get_prod_weight(), $this->get_config()->value_of("default_delivery_type"));
        }
        $dto->set_default_delivery_charge($delivery_charge);

        $fdl = $dto->get_free_delivery_limit();
        if($fdl && $dto->get_price() > $fdl)
        {
            $dto->set_delivery_charge(0);
        }
        else
        {
            $dto->set_delivery_charge($delivery_charge);
        }
    }
*/
    public function calc_delivery_charge($dto = NULL)
    {
        $this->init_dto($dto);

        if ($this->product_service->get_pt_dao()->get_num_rows(array("sku"=>$dto->get_sku(), "type_id"=>"VIRTUAL")))
        {
            $delivery_charge = 0;
        }
        else
        {
            $delivery_charge = $this->get_wc_srv()->get_wcc_dao()->get_country_weight_charge_by_platform($dto->get_platform_id(), $dto->get_prod_weight(), $this->get_config()->value_of("default_delivery_type"));
        }
        $dto->set_default_delivery_charge($delivery_charge);
        $fdl = $dto->get_free_delivery_limit();
        if($dto->get_price() > $fdl*1)
        {
            $dto->set_delivery_charge(0);
        }
        else
        {
            $dto->set_delivery_charge($delivery_charge);
        }
    }

    public function calc_declared_value($dto = NULL)
    {
        $this->init_dto($dto);

        $value = $dto->get_price() + $dto->get_delivery_charge();

        $country_id = $dto->get_platform_country_id();
        $temp = $this->calculate_declared_value($dto, $country_id, $value);

        #[4:36:06 PM] Jesslyn:
        #1.  Products >= 400NZD, declare % = 100 AND 15% GST
        #2.  Products < 400NZD, declare % = 80 AND 0% GST
        // if($dto->get_platform_country_id() == 'NZ') if ($value >= 400) $dto->set_vat_percent(15);

        $dto->set_declared_value($temp);
        return;

        ##### the buck stops here #####

        if($dto->get_platform_country_id() == 'NZ')
        {
            if($value > 750 && 800 > $value)
            {
                $dto->set_declared_pcent(100);
                $dto->set_vat_percent(15);
                $declared = 400;
            }
            elseif($value >= 800)
            {
                $dto->set_declared_pcent(100);
                $dto->set_vat_percent(15);
                $declared = $value / 2;
            }
            else
            {
                $declared = $value * $dto->get_declared_pcent() / 100;
            }
        }
        elseif($obj = $this->get_sub_domain_srv()->get(array("subject"=>"MAX_DECLARE_VALUE.{$dto->get_platform_country_id()}")))
        {
            $dto->set_declared_pcent(100);
            $max_value = $obj->get_value();
            $declared = min($max_value, $value);
        }
        else
        {
            $declared = $value * $dto->get_declared_pcent() / 100;
        }
        $dto->set_declared_value($declared);
    }

    public function calc_duty($dto = NULL)
    {
        $this->init_dto($dto);
        $duty = number_format($dto->get_declared_value() * $dto->get_duty_pcent() / 100, 2, ".", "");
        $dto->set_duty($duty);
    }

    public function calc_vat($dto = NULL)
    {
        $this->init_dto($dto);

        if($dto->get_platform_country_id() == "NZ")
        {
            $value = $dto->get_price();# + $dto->get_delivery_charge();

            if ($value > 400)
            {
                # SBF#3250 - remove 15% GST
                // $dto->set_vat_percent(15);
                $dto->set_vat_percent(0);

                // $dto->set_vat(number_format(($dto->get_declared_value() * $dto->get_vat_percent() / 100) + 38.07, 2, ".", ""));
                #SBF#2525 - removal of 38.07
                $dto->set_vat(number_format(($dto->get_declared_value() * $dto->get_vat_percent() / 100), 2, ".", ""));
                // var_dump("my $value's VAT% is " . $dto->get_vat_percent());
            }
            else
            {
                $dto->set_vat_percent(0);
                $dto->set_vat(0.00);
            }

            # overwrite by SBF#2201 - louis
            if (1==0)
            {
                if($dto->get_declared_value() > 400)
                {
                    $dto->set_vat(number_format((($dto->get_declared_value()) * $dto->get_vat_percent() / 100) + 38.07, 2, ".", ""));
                }
                else
                    $dto->set_vat(0.00);
            }
        }
        else
        {
            $dto->set_vat(number_format(($dto->get_declared_value()) * $dto->get_vat_percent() / 100, 2, ".", ""));
        }
    }

    public function init_dto(&$dto)
    {
        if (is_null($dto))
        {
            $dto = $this->get_dto();
        }
        else
        {
            $this->set_dto($dto);
        }
    }

    public function draw_table_header_row($dto = NULL, $app_id = null)
    // public function draw_table_header_row($dto = NULL)
    {
        $this->init_dto($dto);

        $hasVatPermission = false;
        if ($app_id != null)
        {
            if (check_app_feature_access_right($app_id, "MKT004400_display_decl_vat")) $hasVatPermission = true;
        }

        $decl_vat = "";
        if ($hasVatPermission)
        {
            $decl_vat = "
                <td>\$lang[declared]</td>
                <td>\$lang[vat]<br>(".$dto->get_vat_percent()."%)</td>
            ";
        }

        $header .= "\$header = \"<tr class='header'>
                        <td>&nbsp;</td>
                        <td>\$lang[selling_price]</td>
                        <td>\$lang[delivery]</td>
                        $decl_vat
                        <td>\$lang[platform_commission]<br>(".$dto->get_platform_commission()."%)</td>
                        <td>\$lang[duty]<br>(".$dto->get_duty_pcent()."%)</td>
                        <td>\$lang[pmgw]<br>(".$dto->get_payment_charge_percent()."%)</td>
                        <td>\$lang[forex]<br>(".$dto->get_forex_fee_percent()."%)</td>
                        <td>\$lang[listing_fee]</td>
                        <td>\$lang[logistic_cost]</td>
                        <td>\$lang[ca_cost]</td>
                        <td>\$lang[cost]</td>
                        <td>\$lang[total_cost]</td>
                        <td>\$lang[total]</td>
                        <td>\$lang[profit]</td>
                        <td>\$lang[gpm]</td>
                        <td>&nbsp;</td>
                    </tr>\";";
        return $header;
    }

    public function draw_table_row_for_pricing_tool($dto = NULL, $app_id = null)
    {
        // var_dump($dto->get_declared_pcent()); die();
        $this->init_dto($dto);

        $delivery = $dto->get_delivery_charge();

        $total = $dto->get_price() + $dto->get_delivery_charge();

        $bgcolor = $total > $dto->get_cost()?"#ddffdd":"#ffdddd";
        $platform = $dto->get_platform_id();
        $country_id = $dto->get_platform_country_id();
        $auto_calc_price = ($dto->get_auto_total_charge() - $dto->get_default_delivery_charge() > $dto->get_free_delivery_limit())? $dto->get_auto_total_charge():$dto->get_auto_total_charge() - $dto->get_default_delivery_charge();

        $hasVatPermission = false;
        if ($app_id != null)
        {
            if (check_app_feature_access_right($app_id, "MKT004400_display_decl_vat")) $hasVatPermission = true;
        }

        $decl_vat = "";
        if ($hasVatPermission)
        {
            $decl_vat = '
                <td id="declare['.$platform.']">'.number_format($dto->get_declared_value(),2, ".", "").'</td>
                <td id="vat['.$platform.']">'.number_format($dto->get_vat(),2, ".", "").'</td>
            ';
        }

        $table_row .='<tr id="row['.$platform.']" style="background-color:'.$bgcolor.'">
                        <td></td>
                        <td><input type="text" name="selling_price['.$platform.']" value="'.($dto->get_current_platform_price()*1).'" id="sp['.$platform.']" onKeyup="rePrice(\''.$platform.'\',\''.$dto->get_sku().'\')" style="width:80px;" notEmpty></td>
                        <td id="delivery_charge['.$platform.']">'.number_format($delivery,2, ".", "").'</td>
                        '.$decl_vat.'
                        <td id="comm['.$platform.']">'.number_format($dto->get_sales_commission(),2, ".", "").'</td>
                        <td id="duty['.$platform.']">'.number_format($dto->get_duty(),2, ".", "").'</td>
                        <td id="pc['.$platform.']">'.number_format($dto->get_payment_charge(),2, ".", "").'</td>
                        <td id="forex_fee['.$platform.']">'.number_format($dto->get_forex_fee(),2, ".", "").'</td>
                        <td id="listing_fee['.$platform.']">'.number_format($dto->get_listing_fee(),2, ".", "").'</td>
                        <td id="logistic_cost['.$platform.']">'.number_format($dto->get_logistic_cost(),2, ".", "").'</td>
                        <td id="complementary_acc_cost['.$platform.']">'.number_format($dto->get_complementary_acc_cost(),2, ".", "").'</td>
                        <td id="supplier_cost['.$platform.']">'.number_format($dto->get_supplier_cost(),2, ".", "").'</td>
                        <td id="total_cost['.$platform.']">'.number_format($dto->get_cost(),2, ".", "").'</td>
                        <td id="total['.$platform.']">'.number_format(($dto->get_price() + $delivery),2, ".", "").'</td>
                        <td id="profit['.$platform.']">'.number_format($dto->get_profit(),2, ".", "").'</td>
                        <td id="margin['.$platform.']">'.number_format($dto->get_margin(),2, ".", "").'%</td>
                        <input type="hidden" id="hidden_profit['.$platform.']" name="hidden_profit['.$platform.']" value="'.number_format($dto->get_profit(),2, ".", "").'">
                        <input type="hidden" id="hidden_margin['.$platform.']" name="hidden_margin['.$platform.']" value="'.number_format($dto->get_margin(),2, ".", "").'">
                        <td>
                            <input type="hidden" id="declared_rate['.$platform.']" value="'.$dto->get_declared_pcent().'">
                            <input type="hidden" id="payment_charge_rate['.$platform.']" value="'.$dto->get_payment_charge_percent().'">
                            <input type="hidden" id="vat_percent['.$platform.']" value="'.$dto->get_vat_percent().'">
                            <input type="hidden" id="duty_percent['.$platform.']" value="'.$dto->get_duty_pcent().'">
                            <input type="hidden" id="forex_fee_percent['.$platform.']" value="'.$dto->get_forex_fee_percent().'">
                            <input type="hidden" id="free_delivery_limit['.$platform.']" value="'.$dto->get_free_delivery_limit().'">
                            <input type="hidden" id="default_delivery_charge['.$platform.']" value="'.$dto->get_default_delivery_charge().'">
                            <input type="hidden" id="scost['.$platform.']" value="'.$dto->get_supplier_cost().'">
                            <input type="hidden" id="commrate['.$platform.']" value="'.$dto->get_platform_commission().'">
                            <input type="hidden" id="country_id['.$platform.']" value="'.$country_id.'">
                            <input type="hidden" id="prod_weight['.$platform.']" value="'.$dto->get_prod_weight().'">
                            <input type="hidden" id="default_freight_cost['.$platform.']" value="'.($dto->get_whfc_cost()-$dto->get_amazon_efn_cost()*1).'">
                            <input type="hidden" id="sub_cat_margin['.$platform.']" value="'.$dto->get_sub_cat_margin().'">
                            <input type="hidden" id="auto_calc_price['.$platform.']" value="'.number_format($auto_calc_price,2, ".", "").'">
                            <input type="hidden" id="origin_price['.$platform.']" value="'.number_format($dto->get_price(),2, ".", "").'">
                        </td>
                     </tr>
                    '."\n";
        return $table_row;
    }

    public function get_product_overview_tr($where=array(), $option=array(), $classname="Product_cost_dto", $lang=array())
    {
        $option["active_supplier"] = 1;
        $option["master_sku"] = 1;
        $option["wms_inventory"] = 1;

        if($option["refresh_margin"])
        {
            $this->get_price_margin_service()->refresh_margin($where["platform_id"]);
        }

        $this->get_product_service()->get_dao()->db->save_queries = false;
        if ($objlist = $this->get_product_service()->get_dao()->get_product_overview($where, $option, $classname))
        {
            include_once BASEPATH."helpers/url_helper.php";
            $list_where["status"] = 1;
            if ($where["platform_id"])
            {
                $list_where["id"] = $where["platform_id"];
            }
            elseif (defined(PLATFORM_TYPE))
            {
                $list_where["type"] = PLATFORM_TYPE;
            }

            $this->get_platform_biz_var_service()->selling_platform_dao->db->save_queries = false;
            $platform_list = $this->get_platform_biz_var_service()->selling_platform_dao->get_list($list_where,array("limit"=>-1));
            $data["tr"] = $data["js"] = "";
            foreach($platform_list as $platform_obj)
            {
                $ca[] = "'".$platform_obj->get_id()."':new Array()";
            }

            $data["js"] .= "
                            <script>
                                var prod = new Array();
                                prod = {".implode(",",$ca)."};
                            ";
            unset($ca);
            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $ar_ls_status = array("N"=>$lang["not_listed"], "L"=>$lang["listed"]);
            $cr_array = array($lang["no"], $lang["yes"]);
            $this->get_wms_wh_srv()->db->save_queries = false;
            $wms_wh_list = $this->get_wms_wh_srv()->get_list(array('status'=>1), array('limit'=>-1, 'orderby'=>'warehouse_id'));
            $i=0;
            foreach ($objlist as $obj)
            {
                //$this->set_dto($obj);
                $supplier_obj = $this->get_supplier_service()->get(array("id"=>$obj->get_supplier_id()));
                if($supplier_obj = $this->get_supplier_service()->get(array("id"=>$obj->get_supplier_id())))
                {
                    $supplier_prod_obj = $this->get_supplier_service()->get_sp_dao()->get(array("supplier_id"=>$supplier_obj->get_id(), "prod_sku"=>$obj->get_sku()));
                    $supplier_status = $supplier_prod_obj->get_supplier_status();
                }
                else
                {
                    $supplier_status = "";
                }
                $country_obj = $this->get_country_service()->get(array("id"=>$obj->get_platform_country_id()));
                $this->calc_logistic_cost($obj);
                $this->calculate_profit($obj);

                unset($supplier_obj);
                unset($country_obj);
                $cur_row = $i%2;
                $selected_ws = array();
                $sws_status = "";
                $selected_ws[$obj->get_website_status()] = "SELECTED";
                foreach ($ar_ws_status as $rskey=>$rsvalue)
                {
                    $sws_status .= "<option value='{$rskey}' {$selected_ws[$rskey]}>{$rsvalue}";
                }
                $sls_status = "";
                $selected_ls = array();
                $selected_ls[$obj->get_listing_status()] = " SELECTED";
                foreach($ar_ls_status as $rskey=>$rsvalue)
                {
                    $sls_status .= "<option value='{$rskey}'{$selected_ls[$rskey]}>{$rsvalue}</option>";
                }
                $sku = $obj->get_sku();
                $platform = $obj->get_platform_id();
                $price = $obj->get_price()*1;
                $cost = $obj->get_cost();

                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $updated = substr($obj->get_purchaser_updated_date(),0,10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000)?" class='warn'":"";
                $pclass = ($profit<0)?" class='warn'":"";

                $wms_inv = array();
                if (!is_null($obj->get_wms_inv()))
                {
                    $tmp_array = explode('|', $obj->get_wms_inv());
                    foreach ($tmp_array as $inv)
                    {
                        list($wh, $inv, $git) = explode(',', $inv);
                        $wms_inv[$wh]['inv'] = $inv;
                        $wms_inv[$wh]['git'] = $git;
                    }
                }

                $wms_inv_td = '';
                $wms_inv_td_count = 0;
                if (!empty($wms_wh_list))
                {
                    foreach ($wms_wh_list as $wms_wh)
                    {
                        if (array_key_exists($wms_wh->get_warehouse_id(), $wms_inv))
                        {
                            $wms_inv_td .= '<td align="right">' . $wms_inv[$wms_wh->get_warehouse_id()]['inv'] . '</td>';
                        }
                        else
                        {
                            $wms_inv_td .= '<td align="right">0</td>';
                        }

                        $wms_inv_td_count++;
                    }
                }

                if($obj->get_ext_item_id())
                {
                    $action_selector = <<<start
                                        <select name="action[{$platform}][{$sku}]" style="width:130px;" onChange="needToConfirm=true;if(this.value == 'E') { document.getElementById('end_reason[{$platform}][{$sku}]').style.display = '' } else { document.getElementById('end_reason[{$platform}][{$sku}]').style.display = 'none' }">
                                            <option></option>
                                            <option value="R">{$lang["re-additem"]}</option>
                                            <option value="RE">{$lang["revise"]}</option>
                                            <option value="E">{$lang["enditem"]}</option>
                                        </select>
                                        <select id="end_reason[{$platform}][{$sku}]" name="reason[{$platform}][{$sku}]" style="width:130px;display:none;">
                                            <option value="NotAvailable">NotAvailable</option>
                                            <option value="Incorrect">Incorrect</option>
                                            <option value="LostOrBroken">LostOrBroken</option>
                                            <option value="OtherListingError">OtherListingError</option>
                                            <option value="SellToHighBidder">SellToHighBidder</option>
                                            <option value="Sold">Sold</option>
                                        </select>
start;
                }
                else
                {
                    $action_selector = "";
                }

                if(!$obj->get_handling_time())
                {
                    switch(strtoupper($platform))
                    {
                        case 'EBAYAU':
                        case 'EBAYSG':
                        case 'EBAYUK': $default_handling_time = 10; break;
                        case 'EBAYUS': $default_handling_time = 1; break;
                        case 'QOO10SG':
                        case 'RAKUES': $default_handling_time = 0; break;
                        default: $default_handling_time = 10; break;
                    }
                    $obj->set_handling_time($default_handling_time);
                }

                $handling_time_option = "";
                foreach(array(0,1,2,3,4,5,10,15,20) as $h)
                {
                    if($obj->get_handling_time() == $h)
                        $selected = "selected";
                    else
                        $selected = "";

                    $handling_time_option .= "<option value={$h} {$selected}>{$h}</option>";
                }

                $handling_time = <<<start
                    <select name="handling_time[$platform][$sku]" style="width:40px;" >
                        {$handling_time_option}
                    </select>
start;

                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td><a href='".base_url().$this->get_tool_path()."/view/{$sku}?target=overview' target='".str_replace("-", "_", $sku)."' onClick=\"Pop('".base_url()."marketing/pricing_tool/view/{$sku}?target=overview','".str_replace("-", "_", $sku)."');\">{$sku}</a></td>
                                    <td>{$obj->get_prod_name()}</td>
                                    <td>{$cr_array[$obj->get_clearance()]}</td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                    </td>
                                    <td>{$obj->get_inventory()}</td>
                                    <td><input name='product[{$platform}][{$sku}][ext_qty]' value='{$obj->get_ext_qty()}' style='width:70px' isNumber min='0' onChange='needToConfirm=true'>" . $obj->get_ext_qty() . "</td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select>
                                    </td>
                                    <td>{$handling_time}</td>

                                    {$wms_inv_td}
                                    <td>{$ar_src_status[$supplier_status]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input class='read' name='cost[{$sku}]' value='".number_format($cost,2)."' style='width:55px; text-align:right'></td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input name='price[{$platform}][{$sku}][price]' id='sp[{$platform}][{$sku}]' value='{$obj->get_current_platform_price()}' style='width:70px' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>".(!($obj->get_current_platform_price()*1) && $obj->get_default_platform_converted_price()*1?"<span class='special'>" . $obj->get_platform_currency_id() . " {$obj->get_default_platform_converted_price()}</span>":"")."</td>
                                    <td id='profit[{$platform}][{$sku}]' align='right'{$pclass}>".number_format($profit,2)."</td>
                                    <td id='margin[{$platform}][{$sku}]' align='right'{$pclass}>".number_format($margin,2)."%</td>
                                    <td id='action[{$platform}][{$sku}][action]'>".$action_selector."</td>
                                    <td><input type='checkbox' name='check[]' value='{$platform}||{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $obj->set_freight_cost(round($obj->get_logistic_cost() , 2));
                $obj->set_supplier_cost(round($obj->get_supplier_cost(), 2));
                $declared_pcent = !$obj->get_declared_pcent()?0:$obj->get_declared_pcent();
                $comm = ($obj->get_price() + $obj->get_delivery_charge()) * $obj->get_platform_commission() / 100;
                $admin_fee = !$obj->get_admin_fee()?0:$obj->get_admin_fee();
                $default_delivery_charge = $obj->get_default_delivery_charge()*1;
                $delivery_charge = $obj->get_delivery_charge()*1;
                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$default_delivery_charge},'admin_fee':{$admin_fee},'logistic_cost':{$obj->get_logistic_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'delivery_charge':{$delivery_charge}, 'comm':{$comm},'commrate':".($obj->get_platform_commission()*1)."};
                                ";
                $i++;
            }
            if ($i>0)
            {
                $colspan = 17 + $wms_inv_td_count;
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='{$colspan}'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
                $data["js"] .= "var country_id = '{$obj->get_platform_country_id()}';
                </script>";
            }
            else
            {
                $data["tr"] = $data["js"] = "";
            }
        }

        return $data;
    }

    public function get_product_overview_tr_v2($where=array(), $option=array(), $classname="Product_cost_dto", $lang=array())
    {
        $option["active_supplier"] = 1;
        $option["master_sku"] = 1;
        $option["wms_inventory"] = 1;
        $option["delivery_time"] = 1;
        $option["refresh_margin"] = 1;
        # sbf #4906 - running cron job to refresh so it wouldn't be slow
        // if($option["refresh_margin"])
        // {
        //  // this portion updates db price_margin table with calculated margin so that search margin value will be correct
        //  set_time_limit(300);
        //  if($option["refresh_platform_list"])
        //  {
        //      foreach($option["refresh_platform_list"] as $platform_id)
        //      {
        //          $this->get_price_margin_service()->refresh_margin(trim($platform_id));
        //      }
        //  }
        // }
        $objlist = $this->get_product_service()->get_dao()->get_product_overview_v2($where, $option, $classname);
        if ($objlist = $this->get_product_service()->get_dao()->get_product_overview_v2($where, $option, $classname))
        {
            include_once BASEPATH."helpers/url_helper.php";
            $list_where["status"] = 1;
            if ($where["platform_id"])
            {
                $list_where["id"] = $where["platform_id"];
            }
            elseif (defined(PLATFORM_TYPE))
            {
                $list_where["type"] = PLATFORM_TYPE;
            }

            $this->get_platform_biz_var_service()->selling_platform_dao->db->save_queries = false;
            $platform_list = $this->get_platform_biz_var_service()->selling_platform_dao->get_list($list_where,array("limit"=>-1));
            $data["tr"] = $data["js"] = "";
            foreach($platform_list as $platform_obj)
            {
                $ca[] = "'".$platform_obj->get_id()."':new Array()";
            }

            $data["js"] .= "
                            <script>
                                var prod = new Array();
                                prod = {".implode(",",$ca)."};
                            ";
            unset($ca);
            $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
            $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
            $ar_ls_status = array("N"=>$lang["not_listed"], "L"=>$lang["listed"]);
            $cr_array = array($lang["no"], $lang["yes"]);
            $this->get_wms_wh_srv()->db->save_queries = false;
            $wms_warehouse_where["status"] = 1;
            $wms_warehouse_where["type != 'W'"] = null;
            $wms_wh_list = $this->get_wms_wh_srv()->get_list($wms_warehouse_where, array('limit'=>-1, 'orderby'=>'warehouse_id'));
            $i=0;
            foreach ($objlist as $obj)
            {
                //$this->set_dto($obj);
                $supplier_obj = $this->get_supplier_service()->get(array("id"=>$obj->get_supplier_id()));
                if($supplier_obj = $this->get_supplier_service()->get(array("id"=>$obj->get_supplier_id())))
                {
                    $supplier_prod_obj = $this->get_supplier_service()->get_sp_dao()->get(array("supplier_id"=>$supplier_obj->get_id(), "prod_sku"=>$obj->get_sku()));
                    $supplier_status = $supplier_prod_obj->get_supplier_status();
                }
                else
                {
                    $supplier_status = "";
                }
                $country_obj = $this->get_country_service()->get(array("id"=>$obj->get_platform_country_id()));

                # we already calculate logistic cost in query and use price_margin table, so skip the slow calculate_profit()
                // $this->calc_logistic_cost($obj);
                // $this->calculate_profit($obj);
                $this->calc_delivery_charge($obj);
                $this->calc_cost($obj);

                unset($supplier_obj);
                unset($country_obj);
                $cur_row = $i%2;
                $selected_ws = array();
                $sws_status = "";
                $selected_ws[$obj->get_website_status()] = "SELECTED";
                foreach ($ar_ws_status as $rskey=>$rsvalue)
                {
                    $sws_status .= "<option value='{$rskey}' {$selected_ws[$rskey]}>{$rsvalue}";
                }
                $sls_status = "";
                $selected_ls = array();
                $selected_ls[$obj->get_listing_status()] = " SELECTED";
                foreach($ar_ls_status as $rskey=>$rsvalue)
                {
                    $sls_status .= "<option value='{$rskey}'{$selected_ls[$rskey]}>{$rsvalue}</option>";
                }
                $surplus_quantity = $obj->get_surplus_quantity();
                $sku = $obj->get_sku();
                $platform = $obj->get_platform_id();
                $price = $obj->get_price()*1;
                $cost = $obj->get_cost();

                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $updated = substr($obj->get_purchaser_updated_date(),0,10);
                $dclass = ((mktime() - strtotime($updated)) > 2592000)?" class='warn'":"";
                $pclass = ($profit<0)?" class='warn'":"";

                $format_profit = number_format($profit,2);
                $format_margin = number_format($margin,2);
                $hidden_profitmargin_html = <<<html
                    <input type="hidden" name="hidden_profit[$platform][$sku]" id="hidden_profit[$platform][$sku]" value = "$format_profit">
                    <input type="hidden" name="hidden_margin[$platform][$sku]" id="hidden_margin[$platform][$sku]" value = "$format_margin">
html;

                $wms_inv = array();
                if (!is_null($obj->get_wms_inv()))
                {
                    $tmp_array = explode('|', $obj->get_wms_inv());
                    foreach ($tmp_array as $inv)
                    {
                        list($wh, $inv, $git) = explode(',', $inv);
                        $wms_inv[$wh]['inv'] = $inv;
                        $wms_inv[$wh]['git'] = $git;
                    }
                }

                $wms_inv_td = '';
                $wms_inv_td_count = 0;
                if (!empty($wms_wh_list))
                {
                    foreach ($wms_wh_list as $wms_wh)
                    {
                        if (array_key_exists($wms_wh->get_warehouse_id(), $wms_inv))
                        {
                            $wms_inv_td .= '<td align="right">' . $wms_inv[$wms_wh->get_warehouse_id()]['inv'] . '</td>';
                        }
                        else
                        {
                            $wms_inv_td .= '<td align="right">0</td>';
                        }

                        $wms_inv_td_count++;
                    }
                }

                $action_selector = "";
                if($obj->get_ext_item_id())
                {
                    switch(strtoupper($platform))
                    {
                        case 'EBAYAU':
                        case 'EBAYSG':
                        case 'EBAYUK':
                        case 'EBAYUS':
                            $action_selector = <<<start
                                <select name="action[{$platform}][{$sku}]" dataname="act" style="width:130px;" onChange="needToConfirm=true;if(this.value == 'E') { document.getElementById('end_reason[{$platform}][{$sku}]').style.display = '' } else { document.getElementById('end_reason[{$platform}][{$sku}]').style.display = 'none' }">
                                    <option></option>
                                    <option value="R">{$lang["re-additem"]}</option>
                                    <option value="RE">{$lang["revise"]}</option>
                                    <option value="E">{$lang["enditem"]}</option>
                                </select>
                                <select id="end_reason[{$platform}][{$sku}]" name="reason[{$platform}][{$sku}]" style="width:130px;display:none;">
                                    <option value="NotAvailable">NotAvailable</option>
                                    <option value="Incorrect">Incorrect</option>
                                    <option value="LostOrBroken">LostOrBroken</option>
                                    <option value="OtherListingError">OtherListingError</option>
                                    <option value="SellToHighBidder">SellToHighBidder</option>
                                    <option value="Sold">Sold</option>
                                </select>
start;
                            break;
                        case 'QOO10SG':
                        case 'RAKUES':
                            $action_selector = <<<start
                                <select name="action[{$platform}][{$sku}]" dataname="act" style="width:130px;" onChange="needToConfirm=true;">
                                    <option></option>
                                    <option value="R">{$lang["re-additem"]}</option>
                                    <option value="RE">{$lang["revise"]}</option>
                                    <option value="E">{$lang["enditem"]}</option>
                                </select>
start;
                            break;
                        default:
                            break;
                    }
                }
                else
                {
                    $action_selector = "";
                }

                $ext_qty_min = " min='0' ";
                if($platform == "RAKUES")
                    $ext_qty_min = "";

                $show_handling_option = 1;
                if(!$obj->get_handling_time())
                {
                    switch(strtoupper($platform))
                    {
                        case 'EBAYAU':
                        case 'EBAYSG':
                        case 'EBAYUK': $default_handling_time = 10; break;
                        case 'EBAYUS': $default_handling_time = 1; break;
                        case 'QOO10SG':
                        case 'RAKUES': $default_handling_time = 0; $show_handling_option = 0; break;
                        default: $default_handling_time = 10; break;
                    }
                    $obj->set_handling_time($default_handling_time);
                }

                $handling_time_option = "";
                foreach(array(0,1,2,3,4,5,10,15,20) as $h)
                {
                    if($obj->get_handling_time() == $h)
                        $selected = "selected";
                    else
                        $selected = "";

                    $handling_time_option .= "<option value={$h} {$selected}>{$h}</option>";
                }

                $handling_time = "";
                if($show_handling_option)
                {
                    $handling_time = <<<start
                        <select name="handling_time[$platform][$sku]" style="width:40px;" >
                            {$handling_time_option}
                        </select>
start;
                }

                $data["tr"] .= "
                                <tr class='row{$cur_row}' onMouseOver=\"AddClassName(this, 'highlight')\" onMouseOut=\"RemoveClassName(this, 'highlight')\">
                                    <td></td>
                                    <td><a href='".base_url().$this->get_tool_path()."/view/{$sku}?target=overview' target='".str_replace("-", "_", $sku)."' onClick=\"Pop('".base_url()."marketing/pricing_tool/view/{$sku}?target=overview','".str_replace("-", "_", $sku)."');\">{$sku}</a></td>
                                    <td>{$obj->get_prod_name()}</td>
                                    <td>{$cr_array[$obj->get_clearance()]}</td>
                                    <td>
                                        <select name='price[{$platform}][{$sku}][listing_status]' class='input' onChange='needToConfirm=true'>
                                        {$sls_status}
                                        </select>
                                    </td>
                                    <td>{$obj->get_inventory()}</td>
                                    <td><input name='product[{$platform}][{$sku}][ext_qty]' value='{$obj->get_ext_qty()}' style='width:70px' isNumber {$ext_qty_min} onChange='needToConfirm=true'>" . $obj->get_ext_qty() . "</td>
                                    <td>
                                        <select name='product[{$platform}][{$sku}][website_status]' class='input' onChange='needToConfirm=true'>
                                            {$sws_status}
                                        </select>
                                    </td>
                                    <td>{$handling_time}</td>

                                    {$wms_inv_td}
                                    <td align='right'>$surplus_quantity</td>
                                    <td>{$ar_src_status[$supplier_status]}</td>
                                    <td{$dclass}>{$updated}</td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input class='read' name='cost[{$sku}]' value='".number_format($cost,2)."' style='width:55px; text-align:right'></td>
                                    <td align='right'>" . $obj->get_platform_currency_id() . " <input name='price[{$platform}][{$sku}][price]' id='sp[{$platform}][{$sku}]' value='{$obj->get_current_platform_price()}' style='width:70px' onKeyUp=\"CalcProfit('{$platform}','{$sku}', this.value)\" isNumber min='0' onChange='needToConfirm=true'>".(!($obj->get_current_platform_price()*1) && $obj->get_default_platform_converted_price()*1?"<span class='special'>" . $obj->get_platform_currency_id() . " {$obj->get_default_platform_converted_price()}</span>":"")."
                                    {$hidden_profitmargin_html}</td>
                                    <td id='profit[{$platform}][{$sku}]' align='right'{$pclass}>".number_format($profit,2)."</td>
                                    <td id='margin[{$platform}][{$sku}]' align='right'{$pclass}>".number_format($margin,2)."% </td>
                                    <td id='action[{$platform}][{$sku}][action]'>".$action_selector."</td>
                                    <td><input type='checkbox' name='check[]' value='{$platform}||{$sku}' onClick='Marked(this);'></td>
                                </tr>
                                ";
                $obj->set_freight_cost(round($obj->get_logistic_cost() , 2));
                $obj->set_supplier_cost(round($obj->get_supplier_cost(), 2));
                $declared_pcent = !$obj->get_declared_pcent()?0:$obj->get_declared_pcent();
                $comm = ($obj->get_price() + $obj->get_delivery_charge()) * $obj->get_platform_commission() / 100;
                $admin_fee = !$obj->get_admin_fee()?0:$obj->get_admin_fee();
                $default_delivery_charge = $obj->get_default_delivery_charge()*1;
                $delivery_charge = $obj->get_delivery_charge()*1;
                $data["js"] .= "prod['{$platform}']['{$sku}'] = {'clearance':{$obj->get_clearance()},'declared_rate':{$declared_pcent},'payment_charge_rate':{$obj->get_payment_charge_percent()},'vat_percent':{$obj->get_vat_percent()},'duty_percent':{$obj->get_duty_pcent()},'free_delivery_limit':{$obj->get_free_delivery_limit()},'default_delivery_charge':{$default_delivery_charge},'admin_fee':{$admin_fee},'logistic_cost':{$obj->get_logistic_cost()},'supplier_cost':{$obj->get_supplier_cost()}, 'delivery_charge':{$delivery_charge}, 'comm':{$comm},'commrate':".($obj->get_platform_commission()*1)."};
                                ";
                $i++;
            }
            if ($i>0)
            {
                $colspan = 17 + $wms_inv_td_count;
                $data["tr"] .= "<tr class='tb_brow' align='right'><td colspan='{$colspan}'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
                $data["js"] .= "var country_id = '{$obj->get_platform_country_id()}';
                </script>";
            }
            else
            {
                $data["tr"] = $data["js"] = "";
            }
        }
        return $data;
    }

    public function get_declared_value(&$dto)
    {
        $price = $dto->get_price();
        $fdl = $dto->get_free_delivery_limit();
        $delivery_fee = $price >= fdl?0:$dto->get_delivery_charge();

        if($dto->get_platform_country_id() == 'NZ')
        {
            if($value > 750 && 800 > $value)
            {
                $dto->set_declared_pcent(100);
                $declared = 400;
            }
            elseif($value >= 800)
            {
                $dto->set_declared_pcent(100);
                $declared = $value / 2;
            }
            else
            {
                $declared = $value * $dto->get_declared_pcent() / 100;
            }
        }
        elseif($obj = $this->get_sub_domain_srv()->get(array("subject"=>"MAX_DECLARE_VALUE.{$dto->get_platform_country_id()}")))
        {
            $dto->set_declared_pcent(100);
            $max_value = $obj->get_value();
            $declared = min($max_value, $price + $delivery_fee);
        }
        else
        {
            $declared = ($price + $delivery_fee) * $dto->get_declared_pcent() / 100;
        }

        $dto->set_declared_value(round($declared,2));
    }

    public function get_price(&$prod)
    {
        if($prod->get_price())
        {
            return $prod->get_price();
        }
        else
        {
            return $this->get_w_default_price($prod->get_sku(), $prod->get_platform_id());
        }
    }

    public function get_w_default_price($sku, $platform_id)
    {
        $price_obj = $this->get(array("sku"=>$sku, "platform_id"=>$platform_id));
        if (!$price_obj || !(call_user_func(array($price_obj, "get_price"))*1))
        {
            if (!($default_obj = $this->get_dao()->get_default_converted_price(array("sku"=>$sku, "platform_id"=>$platform_id))))
            {
                return 0;
            }
            $default_platform_converted_price = $default_obj->get_default_platform_converted_price();
        }
        return $default_platform_converted_price?$default_platform_converted_price:$price_obj->get_price();
    }

    public function calc_logistic_cost(&$dto)
    {
        if($lc = $this->get_fc_srv()->get_fcc_dao()->calc_logistic_cost($dto->get_platform_id(), $dto->get_sku()))
        {
            $dto->set_logistic_cost($lc['converted_amount']);
        }
        else
        {
            $dto->set_logistic_cost(0);
        }
    }

    public function calc_freight_cost(Product_cost_dto &$tmp, Base_service $p_srv, $pcurr)
    {
        $sc = $p_srv->get_supp_to_fc_cost();
        if($sc["amount"])
        {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id"=>$pcurr,"from_currency_id"=>$sc["currency"]));
            $tmp->set_suppfc_cost($ex_rate->get_rate()*$sc["amount"]);
        }
        else
        {
            $tmp->set_suppfc_cost(0);
        }

        $wc = $p_srv->get_wh_fc_cost();
        if($wc["amount"])
        {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id"=>$pcurr,"from_currency_id"=>$wc["currency"]));
            $tmp->set_whfc_cost(@call_user_func(array($ex_rate, "get_rate")) * $wc["amount"]);
        }
        else
        {
            $tmp->set_whfc_cost(0);
        }

        $dc = $p_srv->get_fc_to_customer_cost();
        if($dc["coamount"])
        {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id"=>$pcurr,"from_currency_id"=>$dc["cocurrency"]));
            $tmp->set_fccus_cost(@call_user_func(array($ex_rate, "get_rate")) * $dc["coamount"]);
        }
        else
        {
            $tmp->set_fccus_cost(0);
        }

        if($dc["chamount"])
        {
            $ex_rate = $this->get_exchange_rate_service()->get_dao()->get(array("to_currency_id"=>$pcurr,"from_currency_id"=>$dc["chcurrency"]));
            $tmp->set_delivery_charge(@call_user_func(array($ex_rate, "get_rate")) * $dc["chamount"]);
        }
        else
        {
            $tmp->set_delivery_charge(0);
        }
    }

    public function calc_complementary_acc_cost(&$dto)
    {
        // only obj those with "set_complementary_acc_cost" will execute below
        // to find complementary accessory mapping
        if(method_exists($dto, "set_complementary_acc_cost"))
        {
            $total_cost = 0;
            #sbf #3746 - check for WEB orders
            $check_platform_arr = array("WEB");
            $platform_id = $dto->get_platform_id();
            $platform_country_id = $dto->get_platform_country_id();

            # sbf 4324 - apply CA to website and marketplaces
            // if(in_array(substr($platform_id, 0, -2), $check_platform_arr))
            {
                $mainprod_sku = $dto->get_sku();
                $where["pca.dest_country_id"] = $platform_country_id;
                $where["pca.mainprod_sku"] = $mainprod_sku;
                $where["pca.status"] = 1;
                // check if any mapped complementary accessory
                if($mapped_ca_list = $this->get_ca_dao()->get_mapped_acc_list_w_name($where))
                {
                    // add up total supplier cost for all the mapped accessories for this main product
                    // cost of accessory is not affected by platform commission, etc
                    foreach ($mapped_ca_list as $caobj)
                    {
                        $cadto = $this->get_dao()->get_price_cost_dto($caobj->get_accessory_sku(), $dto->get_platform_id());
                        $total_cost += $cadto->get_supplier_cost();
                    }
                }
            }

            $dto->set_complementary_acc_cost($total_cost);
        }
    }

    public function get_product_list_w_profit($where=array(), $option=array(), $classname="Product_cost_dto")
    {
        if ($objlist = $this->get_product_overview($where, $option, $classname))
        {
            foreach ($objlist as $obj)
            {
                $this->calc_logistic_cost($obj);
                $this->calculate_profit($obj);
            }
            return $objlist;
        }
        return FALSE;
    }

    public function get_components_tr($where=array(), $option=array(), $classname="Product_cost_dto", $lang=array())
    {
        if($option["refresh_margin"] == 1)
        {
            $this->price_margin_service->refresh_latest_margin(array("v_prod_overview_w_update_time.platform_id"=>$where["platform_id"]));
        }

        if ($objlist = $this->product_service->get_dao()->get_components_w_name($where, $option, $classname))
        {
            $data = "";
            $main_qty = $i = 0;
            $outstock = array();
            foreach ($objlist as $obj)
            {
                if ($i == 0)
                {
                    $main_qty = $obj->get_inventory();
                }


                $cur_row = $i%2;
                $p_svc = $this->get_price_service_from_dto($obj);
                $this->calc_logistic_cost($obj);
                $this->calculate_profit($obj);
                $sku = $obj->get_sku();

                if ($obj->get_website_status() == "O")
                {
                    $outstock[] = $sku;
                }

                $price = $obj->get_price()*1;
                $cost = $obj->get_cost();
                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $updated = substr($obj->get_purchaser_updated_date(),0,10);
                $declared_pcent = $obj->get_declared_pcent()*1;
                $vat_pcent = $obj->get_vat_percent()*1;
                $duty_pcent = $obj->get_duty_pcent()*1;
                $delivery_charge = $obj->get_delivery_charge();
                $total_price = $price + $delivery_charge;
                $data .= "
                        <tr class='xrow{$cur_row}'>
                            <td>{$sku}</td>
                            <td>{$obj->get_prod_name()}</td>
                            <td align='right'>".number_format($price, 2)."</td>
                            <td align='right'>".number_format($obj->get_declared_value(), 2)."</td>
                            <td align='right'>".number_format($obj->get_vat(), 2)."</td>
                            <td align='right'>{$obj->get_duty()}</td>
                            <td align='right'>".number_format($obj->get_payment_charge(), 2)."</td>
                            <td align='right'>".number_format($obj->get_admin_fee(), 2)."</td>
                            <td align='right'>".number_format($obj->get_suppfc_cost(), 2)."</td>
                            <td align='right'>".number_format($obj->get_whfc_cost(), 2)."</td>
                            <td align='right'>".number_format($obj->get_fccus_cost(), 2)."</td>
                            <td align='right'>".number_format($obj->get_cost(), 2)."</td>
                            <td align='right'>".number_format($delivery_charge, 2)."</td>
                            <td align='right'>".number_format($total_price, 2)."</td>
                            <td align='right'>".number_format($profit, 2)."</td>
                            <td align='right'>".number_format($margin, 2)."%</td>
                        </tr>
                        ";
                $i++;
            }
            if ($i>0)
            {
                if ($outstock)
                {
                    $js_outstock = "document.fm.status.value = 'O';";
                }
                $data = "<tr class='header'>
                        <td>{$lang["sku"]}</td>
                        <td>{$lang["item"]}</td>
                        <td>{$lang["selling_price"]}</td>
                        <td>{$lang["decl"]} ({$declared_pcent}%)</td>
                        <td>{$lang["vat"]} ({$vat_pcent}%)</td>
                        <td>{$lang["duty"]} ({$duty_pcent}%)</td>
                        <td>{$lang["charge"]}</td>
                        <td>{$lang["admin_fee"]}</td>
                        <td>{$lang["freight"]}</td>
                        <td>{$lang["whtrans"]}</td>
                        <td>{$lang["postage"]}</td>
                        <td>{$lang["cost"]}</td>
                        <td>{$lang["delivery"]}</td>
                        <td>{$lang["total"]}</td><td>{$lang["profit"]}</td>
                        <td>{$lang["margin"]}</td>
                        </tr>"
                        .$data.
                        "<tr class='tb_row' align='right'><td colspan='16'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>"
                        ."<script>document.fm.inventory.value = {$main_qty};{$js_outstock}outstock = '".@implode(", ", $outstock)."'</script>";
            }
            else
            {
                $data = "";
            }
        }
        return $data;
    }

    public function get_ra_prod_tr($prod_sku="", $platform_id="", $classname="Product_cost_dto", $lang=array())
    {
        if ($objlist = $this->get_product_service()->get_dao()->get_ra_product_overview($prod_sku, $platform_id, $classname))
        {
//          $ra_prod_obj = $this->ra_prod_prod_service->get(array("sku"=>$prod_sku));
            $data = array();
            $i=1;
            foreach ($objlist as $obj)
            {
                $this->calc_logistic_cost($obj);
                $this->calculate_profit($obj);
                $sku = $obj->get_sku();
                $price = $obj->get_price()*1;
                $cost = $obj->get_cost();
                $profit = $obj->get_profit();
                $margin = $obj->get_margin();
                $updated = substr($obj->get_purchaser_updated_date(),0,10);
                $declared_pcent = $obj->get_declared_pcent()*1;
                $vat_pcent = $obj->get_vat_percent()*1;
                $duty_pcent = $obj->get_duty_pcent()*1;
                $delivery_charge = $obj->get_delivery_charge();
                $total_price = $price + $delivery_charge;

                if ($sku == $prod_sku)
                {
                    $prod_order = 0;
                }
                else
                {
/*                  for ($j=1; $j<9; $j++)
                    {
                        $func = "get_rcm_prod_id_".$j;
                        if ($sku == $ra_prod_obj->$func())
                        {
                            $prod_order = $j;
                        }
                    }*/
                    $prod_order = $i;
                }
                $prod_check = ($prod_order==0)?" onClick='return false;' CHECKED":"onClick='ChangeName()'";
                $data[$prod_order] = "
                            <td nowrap style='white-space: nowrap'>{$sku}</td>
                            <td>{$obj->get_prod_name()}</td>
                            <td align='right'>".number_format($price, 2)."</td>
                            <td align='right'>".number_format($obj->get_declared_value(), 2)."</td>
                            <td align='right'>".number_format($obj->get_vat(), 2)."</td>
                            <td align='right'>".number_format($obj->get_duty(), 2)."</td>
                            <td align='right'>".number_format($obj->get_payment_charge(), 2)."</td>
                            <td align='right'>".number_format($obj->get_admin_fee(), 2)."</td>
                            <td align='right'>".number_format($obj->get_logistic_cost(), 2)."</td>
                            <td align='right'>".number_format($obj->get_supplier_cost(), 2)."</td>
                            <td align='right'>".number_format($obj->get_cost(), 2)."</td>
                            <td align='right'>{$delivery_charge}</td>
                            <td align='right'>".number_format($total_price, 2)."</td>
                            <td align='right'>".number_format($profit, 2)."</td>
                            <td align='right'>".number_format($margin, 2)."%</td>
                            <td><input name='components[{$prod_order}]' type='checkbox' value='{$sku}::{$obj->get_prod_name()}'{$prod_check}></td>
                        </tr>
                        ";
                $i++;
            }
            if ($i>1)
            {
                ksort($data);
                $new_data = "";
                $k=0;
                foreach ($data as $prod_order=>$rsdata)
                {
                    $cur_row = $k%2;
                    $new_data .= "<tr class='xrow{$cur_row}'>".$rsdata;
                    $k++;
                }
                unset($data);
                $data = "<tr class='header'><td>{$lang["sku"]}</td><td>{$lang["item"]}</td><td>{$lang["selling_price"]}</td><td>{$lang["decl"]} ({$declared_pcent}%)</td><td>{$lang["vat"]} ({$vat_pcent}%)</td><td>{$lang["duty"]} ({$duty_pcent}%)</td><td>{$lang["charge"]}</td><td>{$lang["admin_fee"]}</td><td>{$lang["logistic_cost"]}</td><td>{$lang["supplier_cost"]}</td><td>{$lang["cost"]}</td><td>{$lang["delivery"]}</td><td>{$lang["total"]}</td><td>{$lang["profit"]}</td><td>{$lang["margin"]}</td><td></td></tr>"
                        .$new_data.
                        "<tr class='tb_row' align='right'><td colspan='16'>{$lang['currency']}: <b>{$obj->get_platform_currency_id()}<b></td></tr>";
            }
            else
            {
                $data = "";
            }
        }
        return $data;
    }

    public function get_item_price($obj)
    {
        if (method_exists($obj, "get_price") && method_exists($obj, "get_discount"))
        {
            include_once(APPPATH.'helpers/price_helper.php');
            $price = random_markup($obj->get_price());
            if (!is_null($price))
            {
                return number_format($price * (100 - $obj->get_discount()*1) / 100, 2, ".", "");
            }

        }
        return FALSE;
    }

    public function get_product_overview($where=array(), $option=array(), $classname="Product_cost_dto")
    {
        $option["active_supplier"] = 1;
        return $this->product_service->get_dao()->get_product_overview($where, $option, $classname);
    }

    public function get_product_overview_v2($where=array(), $option=array(), $classname="Product_cost_dto")
    {
        $option["active_supplier"] = 1;
        $option["delivery_time"] = 1;
        $option["refresh_margin"] = 1;
        return $this->product_service->get_dao()->get_product_overview_v2($where, $option, $classname);
    }

    public function get_factory_service()
    {
        return $this->factory_service;
    }

    public function set_factory_service(Base_service $svc)
    {
        $this->factory_service = $svc;
    }

    public function get_country_service()
    {
        return $this->country_service;
    }

    public function set_country_service(Base_service $svc)
    {
        $this->country_service = $svc;
    }

    public function get_supplier_service()
    {
        return $this->supplier_service;
    }

    public function set_supplier_service(Base_service $svc)
    {
        $this->supplier_service = $svc;
    }

    public function get_product_service()
    {
        return $this->product_service;
    }

    public function set_product_service(Base_service $svc)
    {
        $this->product_service = $svc;
    }

    public function get_platform_biz_var_service()
    {
        return $this->platform_biz_var_service;
    }

    public function set_platform_biz_var_service(Base_service $svc)
    {
        $this->platform_biz_var_service = $svc;
    }

    public function get_exchange_rate_service()
    {
        return $this->exchange_rate_service;
    }

    public function set_exchange_rate_service(Base_service $svc)
    {
        $this->exchange_rate_service = $svc;
    }

    public function get_shiptype_service()
    {
        return $this->shiptype_service;
    }

    public function set_shiptype_service(Base_service $svc)
    {
        $this->shiptype_service = $svc;
    }

    public function get_price_margin_service()
    {
        return $this->price_margin_service;
    }

    public function set_price_margin_service(Base_service $svc)
    {
        $this->price_margin_service = $svc;
        return $this;
    }

    public function get_tool_path()
    {
        return $this->tool_path;
    }

    public function set_tool_path($value)
    {
        $this->tool_path = $value;
        return $this;
    }

    public function get_wc_srv()
    {
        return $this->wc_srv;
    }

    public function set_wc_srv($value)
    {
        $this->wc_srv = $value;
    }

    public function get_fc_srv()
    {
        return $this->fc_srv;
    }

    public function set_fc_srv($value)
    {
        $this->fc_srv = $value;
    }

    public function get_price_ext_dao()
    {
        return $this->price_ext_dao;
    }

    public function set_price_ext_dao($value)
    {
        $this->price_ext_dao = $value;
    }

    public function get_ca_dao()
    {
        return $this->ca_dao;
    }

    public function set_ca_dao($value)
    {
        $this->ca_dao = $value;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function set_dto($dto)
    {
        $this->dto = $dto;
    }

    public function get_dto()
    {
        return $this->dto;
    }

    public function get_need_extend()
    {
        return $this->need_extend;
    }

    public function set_need_extend($value)
    {
        $this->need_extend = $value;
    }

    public function calc_website_product_rrp($price = 0, $fixed_rrp = 'Y', $rrp_factor = 1.18)
    {
        if($price > 0)
        {
            if ($fixed_rrp == 'Y')
                $markup = $price * 1.18;
            else
            {
                if ($rrp_factor < 10)
                    $markup = $price * $rrp_factor;
                else
                    return number_format($rrp_factor,2,".","");
            }

            $remainder = fmod($markup, 5);
            $add_to = 5-$remainder;
            $rrp =  number_format($markup-(-$add_to)-.01,2,'.','');
            return number_format($rrp,2,".","");
        }
        return 0;
    }

    public function get_listing_info($sku = "", $platform_id = "", $lang_id = 'en', $option = array())
    {
        include_once(APPPATH.'helpers/price_helper.php');

        $option['limit'] = 1;
        $obj = $this->get_dao()->get_listing_info($sku, $platform_id, $lang_id, $option);
        if($obj)
        {
            //$obj->set_rrp_price($this->calc_website_product_rrp($obj->get_price()));
            $obj->set_rrp_price(random_markup($this->calc_website_product_rrp($obj->get_price(), $obj->get_fixed_rrp(), $obj->get_rrp_factor())));
            $obj->set_price(random_markup($obj->get_price()));
            return $obj;
        }
        return FALSE;
    }

    public function get_listing_info_list($sku_arr = array(), $platform_id = "", $lang_id = 'en', $option = array())
    {
        if(!is_array($sku_arr) || empty($sku_arr))
        {
            return false;
        }
        else
        {
            foreach($sku_arr as $sku)
            {
                $sku_list[$sku] = false;
            }
        }

        if($result = $this->get_dao()->get_listing_info($sku_arr, $platform_id, $lang_id, $option))
        {
            include_once(APPPATH.'helpers/price_helper.php');

            if(is_array($result))
            {
                foreach($result as $obj)
                {
                    //$obj->set_rrp_price($this->calc_website_product_rrp($obj->get_price()));
                    $obj->set_rrp_price(random_markup($this->calc_website_product_rrp($obj->get_price(), $obj->get_fixed_rrp(), $obj->get_rrp_factor())));
                    $obj->set_price(random_markup($obj->get_price()));
                }
                $rs = $result;

                foreach($rs as $rs_obj)
                {
                    $sku_list[$rs_obj->get_sku()] = $rs_obj;
                }
            }
            else
            {
                //$result->set_rrp_price($this->calc_website_product_rrp($result->get_price()));
                $result->set_rrp_price(random_markup($this->calc_website_product_rrp($result->get_price(), $result->get_fixed_rrp(), $result->get_rrp_factor())));
                $result->set_price(random_markup($result->get_price()));
                $sku_list[$result->get_sku()] = $result;
            }
        }

        return $sku_list;
    }

    public function set_sub_domain_srv($srv)
    {
        $this->sub_domain_srv = $srv;
        return $srv;
    }

    public function get_sub_domain_srv()
    {
        return $this->sub_domain_srv;
    }

    public function calc_auto_price_value()
    {
        $recal_vat = 0;
        $this->init_dto($dto);
        $tmp_cost = $dto->get_supplier_cost() + $dto->get_logistic_cost() + $dto->get_listing_fee();
        $markup_percent = $dto->get_sub_cat_margin() + $dto->get_platform_commission() + $dto->get_payment_charge_percent() + $dto->get_forex_fee_percent();
        $auto_declared = $tmp_cost / (1 - ($markup_percent / 100 )) * ($dto->get_declared_pcent() / 100);
        $country_id = substr($dto->get_platform_id(),-2);
        if($obj = $this->get_sub_domain_srv()->get(array("subject"=>"MAX_DECLARE_VALUE.{$country_id}")))
        {
            $max_value = $obj->get_value();
            $auto_declared = min($max_value, $auto_declared);
        }

        $auto_vat = $auto_declared * $dto->get_vat_percent() / 100;
        $auto_duty = $auto_declared * $dto->get_duty_pcent() / 100;

        if($country_id == 'NZ')
        {
            $auto_vat = 0;
        }

        $total_cost = $tmp_cost + $auto_vat + $auto_duty;
        $auto_total_charge = $total_cost / (1 - ($markup_percent / 100 ));
        if($country_id == 'NZ')
        {
            if($auto_total_charge > 750)
            {
                if($auto_total_charge > 800)
                {
                    $dto->set_declared_pcent(50);
                    $auto_declared = $auto_total_charge * $dto->get_declared_pcent() / 100;
                }
                else
                {
                    $auto_declared = 400;
                }
                $dto->set_vat_percent(15);
                $recal_vat = $auto_declared * $dto->get_vat_percent() / 100 + 38.07;
            }
        }
        $dto->set_auto_total_charge($auto_total_charge + $recal_vat);
    }
    public function get_platform_price_list($where, $option)
    {
        return $this->get_dao()->get_platform_price_list($where, $option);
    }

    public function update_auto_price($platform_id, $sku)
    {
        $this->get_dao()->db->save_queries = false;
        $price_obj = $this->get_dao()->get(array("platform_id"=>$platform_id, "sku"=>$sku));
        if($price_obj->get_auto_price() == "Y")
        {
            $json_obj = $this->get_profit_margin_json($platform_id, $sku, 0);
            $php_array = json_decode($json_obj,true);
            $profit = $php_array['get_profit'];
            $margin = $php_array['get_margin'];
            $selling_price = $php_array['get_price'];

            if($selling_price != $price_obj->get_price())
            {
                $price_obj->set_price($selling_price);
                if($this->update($price_obj) === FALSE)
                {
                    $title = "VB Update AUTO PRICE Error - ".$sku ." - ".$platform_id;
                    $message = __LINE__. " price_service: $sku - $platform_id \nSQL:" . $this->db->last_query() . "\n DBerror: " . $this->db->_error_message();
                    mail('itsupport@eservicesgroup.net', $title, $message);
                }
                else
                {
                    $this->get_price_margin_srv()->insert_or_update_margin($sku, $platform_id, $selling_price, $profit, $margin);

                    $this->get_product_update_followup_service()->adwords_update($sku);

                    $this->get_product_update_followup_service()->google_shopping_update($sku);

                    # go inside this function to update various marketplaces with Auto price
                    $this->get_product_update_followup_service()->marketplace_update($platform_id, $sku);
                }
            }
        }
    }


    public function calculate_declared_value($prod_obj = "", $country_id = "", $price = "")
    {
        $max_declared_value = -1;
        $declared_pcent = 100;
        $declared = -1;

        // var_dump($country_id); die();
        // $this->declared_value_debug = "";

        #if($prod_obj)
        {
            switch ($country_id)
            {
                case "AU":
                    if ($price < 950)
                        $declared_pcent = 100;
                    else
                        $max_declared_value = 950;
                    break;

                case "SG":
                    if ($price < 350)
                        $declared_pcent = 100;
                    else
                        $max_declared_value = 350;
                    break;

                case "NZ":
                    if ($price >= 400)
                    {
                        $declared_pcent = 80;
                    }
                    else
                    {
                        $declared_pcent = 100;
                    }
                    break;

                // based on SBF#1790
                case "AE":case "AF":case "AG":case "AI":case "AM":case "AN":case "AO":case "AQ":case "AR":case "AS":case "AW":case "AZ":case "BB":case "BD":case "BF":case "BH":case "BI":case "BJ":case "BM":case "BN":case "BO":case "BR":case "BS":case "BT":case "BV":case "BW":case "BZ":case "CA":case "CC":case "CD":case "CF":case "CG":case "CI":case "CK":case "CL":case
                "CM":case "CN":case "CO":case "CR":case "CU":case "CV":case "CX":case "CY":case "DJ":case "DM":case "DO":case "DZ":case "EC":case "EG":case "EH":case "ER":case "ET":case "FJ":case "FK":case "FM":case "GA":case "GD":case "GE":case "GF":case "GH":case "GL":case "GM":case "GN":case "GP":case "GQ":case "GS":case "GT":case "GU":case "GW":case "GY":case "HK":case
                "HM":case "HN":case "HT":case "ID":case "IL":case "IN":case "IO":case "IQ":case "IR":case "JM":case "JO":case "JP":case "KE":case "KG":case "KH":case "KI":case "KM":case "KN":case "KP":case "KR":case "KW":case "KY":case "KZ":case "LA":case "LB":case "LC":case "LK":case "LR":case "LS":case "LY":case "MA":case "ME":case "MG":case "MH":case "ML":case "MM":case
                "MN":case "MO":case "MP":case "MQ":case "MR":case "MS":case "MU":case "MV":case "MW":case "MX":case "MY":case "MZ":case "NA":case "NC":case "NE":case "NF":case "NG":case "NI":case "NP":case "NR":case "NU":case "OM":case "PA":case "PE":case "PF":case "PG":case "PH":case "PK":case "PM":case "PN":case "PR":case "PS":case "PW":case "PY":case "QA":case
                "RE":case "RU":case "RW":case "SA":case "SB":case "SC":case "SD":case "SH":case "SL":case "SN":case "SO":case "SR":case "ST":case "SV":case "SY":case "SZ":case "TC":case "TD":case "TF":case "TG":case "TH":case "TJ":case "TK":case "TL":case "TM":case "TN":case "TO":case "TR":case "TT":case "TV":case "TW":case "TZ":case "UG":case "UM":case "US":case
                "UY":case "UZ":case "VC":case "VE":case "VG":case "VI":case "VN":case "VU":case "WF":case "WS":case "YE":case "YT":case "ZA":case "ZM":case "ZW":
                        $declared_pcent = 10;
                        break;
                case "AD":case "AL":case "AT":case "AX":case "BA":case "BE":case "BG":case "BL":case "BY":case "CH":case "CZ":case "DE":case "DK":case "EE":case "ES":case "FI":case "FO":case "FR":case "GG":case
                "GI":case "GR":case "HR":case "HU":case "IE":case "IM":case "IS":case "IT":case "JE":case "LI":case "LT":case "LU":case "LV":case "MC":case "MD":case "MF":case "MK":case "MT":case "NL":case "NO":case
                "PL":case "PT":case "RO":case "RS":case "SE":case "SI":case "SJ":case "SK":case "SM":case "UA":case "VA":
                case "GB":
                        $declared_pcent = 10;
                        break;
                        // $declared_pcent = 30;
                        // break;

                default:    # all other countries
                    $declared_pcent = 10;
                    break;
                    if($fc_obj = $this->get_fc_srv()->get(array("id"=>$prod_obj->get_freight_cat_id())))
                    {
                        $declared_pcent = $fc_obj->get_declared_pcent();
                        $this->declared_value_debug .= "1. declared pcent is $declared_pcent\r\n";
                    }
                    else
                    {
                        // default value
                        $declared_pcent = $this->get_config()->value_of("default_declared_pcent");
                        $this->declared_value_debug .= "2. declared pcent is $declared_pcent\r\n";
                    }

                    if($obj = $this->get_sub_domain_srv()->get(array("subject"=>"MAX_DECLARE_VALUE.{$country_id}")))
                    {
                        $max_value = $obj->get_value();
                        $declared = min($max_value, $price);
                        $this->declared_value_debug .= "3. (max, price, chosen) is ($max_value, $price, $declared)\r\n";
                    }
                    else
                    {
                        $declared = $price * $declared_pcent / 100;
                        $this->declared_value_debug .= "4. (price, declared_pcent, final) is ($price, $declared_pcent, $declared)\r\n";
                    }
            }

            if ($declared == -1)
            {
                # we have to use max declared value
                if ($max_declared_value != -1)
                {
                    if ($price > $max_declared_value)
                        $declared = $max_declared_value;
                    else
                        $declared = $price;
                }
                else
                {
                    # we have to use declared percent
                    $declared = $price * $declared_pcent / 100;
                }
                $prod_obj->set_declared_pcent($declared_pcent);
            }
        }

        $this->declared_value_debug .= "5. declared_at $declared\r\n";
        return $declared;
    }

    private function to_2_decimal($value)
    {
        return number_format($value, 2, ".", "");
    }

    private function perform_business_logic($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    {
        return $this->perform_business_logic_v2($dto, $value_to_return, $required_selling_price, $required_cost_price);
    }

    function bt()
    {
        array_walk
        (
            debug_backtrace(),
            create_function
            (
                '$a,$b',
                'print "<br /><b>". basename( $a[\'file\'] ).
                        "</b> &nbsp; <font color=\"red\">{$a[\'line\']}</font> &nbsp;
                        <font color=\"green\">{$a[\'function\']} ()</font> &nbsp; -- ". dirname( $a[\'file\'] ). "/";'
            )
        );
    }

    private function perform_business_logic_v2($dto, $value_to_return, $required_selling_price = -1, $required_cost_price = -1)
    {
        if ($dto == null)
        {
            // var_dump(debug_backtrace());
            $this->bt();
        }

        // 10% - our margin
        $required_margin = -1;
        if ($required_selling_price <= 0)
            $required_margin = $dto->get_sub_cat_margin() / 100;

        // echo "<pre>"; var_dump ("HERE WE ARE: $a, {$dto->get_platform_country_id()}");

        #sbf #3746 - add complementary accessories
        $this->calc_complementary_acc_cost($dto);

        // these values are fixed, calculate here
        $this->calc_logistic_cost($dto);
        $this->calc_forex_fee($dto);
        // nobody uses this
        $this->calc_commission($dto);

        // var_dump ("{$dto->get_margin()}");
        // $dto->set_margin($a*100);
        // if auto pricing ($required_selling_price = -1), we don't have to loop
        // loop only when trying to find the closest price to $required_selling_price
        for(;;)
        {
            $c = 0; # delivery charge paid by customer
            $b = $required_selling_price;
            $bc = $b + $c;

            // 39.41
            $l  = $dto->get_logistic_cost();
            // 2.25%
            $d1 = $dto->get_payment_charge_percent() / 100;
            // $d2 = $d1 * $bc;
            // 0%
            $f1 = $dto->get_forex_fee_percent() / 100;
            $f2 = $f1 * $bc;

            $ca = 0;
            if(method_exists($dto, "get_complementary_acc_cost"))
            {
                $ca = $dto->get_complementary_acc_cost();
            }

            if ($required_cost_price != -1)
            {
                $k = $required_cost_price;
                $dto->set_supplier_cost($k);
            }
            else
                $k  = $dto->get_supplier_cost();    # 359.72

            // var_dump($v); die();

            // 363.88
            // var_dump($b); die();
            $declared_value = $this->calculate_declared_value($dto, $dto->get_platform_country_id(), $b);
            $dto->set_declared_value($declared_value);

            $z = $dto->get_vat_percent() / 100;
            $h = $dto->get_declared_pcent() / 100;
            $a1 = $h * $b;
            $y = $a1 * $z;

            $dto->set_price($b);

            $this->calc_cost($dto); # this will activate the calculation of listing fee
            $v  = $dto->get_listing_fee();      # 0

            $this->calc_vat();

            $this->calc_payment_charge();
            $d2 = $dto->get_payment_charge();

            $this->calc_commission();
            $x2 = $dto->get_sales_commission();

            // var_dump("$required_selling_price's VAT is " . $dto->get_vat());
            // if ($country_id == "NZ")
            // {
            //  if ($b >= 400)
            //  {
            //      $dto->set_vat_percent(15);
            //      $y = number_format(($declared * $prod_obj->get_vat_percent() / 100) + 38.07, 2, ".", "");
            //  }
            // }

            // duties are dependent on declared value, so iterate here
            $this->calc_duty($dto);

            // if ($value_to_return == 2) return $declared_value;
            // $dto->set_vat(number_format(($declared_value * $dto->get_vat_percent() / 100), 2, ".", ""));
            // if ($value_to_return == 3) return

            $y = $dto->get_vat();

            $f = $dto->get_duty_pcent() / 100;

            $e = $a1 * $f;
            $c = $dto->get_delivery_cost();

            // $total_cost_d = $d2 + $e + $f2 + $k + $l + $v + $x2 + $y - $c;
            $total_cost_d = $k + $l + $v + $x2 + $d2 + $f2 + $y + $e + $ca- $c;

            $profit = $b - $total_cost_d;
            if ($bc > 0)
                $margin = $profit / $bc * 100;
            else
                $margin = 0;

            if ($required_margin >= 0)
            {
                if ($required_selling_price < $total_cost_d)
                {
                    $required_selling_price = $total_cost_d;
                    $increment_unit = $total_cost_d *  1 / 100;
                    if ($increment_unit <= 0) $increment_unit = 0.1;
                }
                $b = $required_selling_price;
                $bc = $b + $c;

                if ($margin >= ($required_margin * 100)) break;
                $required_selling_price += $increment_unit;
            }
            else
                break;
        }

        $total_cost_d = $this->to_2_decimal($total_cost_d);
        $dto->set_cost($total_cost_d);

        $current_selling_price = $dto->get_price();
        if ($current_selling_price > 0)
        {
            $profit = $current_selling_price - $total_cost_d;
            $margin = (1-($total_cost_d/($current_selling_price+$c)) - $x - $d1 - $f1) * 100;
            // $margin = $profit / $current_selling_price * 100;

            $profit = $this->to_2_decimal($profit);
            $margin = $this->to_2_decimal($margin);

            $dto->set_profit($profit);
            $dto->set_margin($margin);
        }

        if ($value_to_return == 4) return $total_cost_d;

        // $profit = $auto_selling_price - $total_cost_d;
        // $margin = (1-($total_cost_d/($auto_selling_price+$c)) - $x - $d1 - $f1) * 100;

        $profit = $b - $total_cost_d;
        if($bc > 0)
        {
            $margin = $profit / $bc * 100;
        }
        else{
            $margin = 0;
        }


        // var_dump($margin);

        $profit = $this->to_2_decimal($profit);
        $margin = $this->to_2_decimal($margin);

        $dto->set_profit($profit);
        $dto->set_margin($margin);
        $dto->set_price($required_selling_price);

        // this is depending on selling price, so calc here
        $this->calc_payment_charge($dto);

        return $required_selling_price;

        echo "<hr><pre>";
        var_dump("a: ".$a);
        var_dump("required_selling_price: ".$required_selling_price);

        echo "<hr><pre>";
        var_dump("k: " . $k);
        var_dump("l: " . $l);
        var_dump("v: " . $v);
        var_dump("d1: " . $d1);
        var_dump("d2: " . $d2);
        var_dump("f1: " . $f1);
        var_dump("f2: " . $f2);
        var_dump("h (declared_pcent): " . $h);
        var_dump("a1: " . $a1);
        var_dump("y: " . $y);

        var_dump("value_to_declare: " . $value_to_declare);
        var_dump("declared_value: " . $declared_value);
        var_dump("get_vat: " . $dto->get_vat());
        var_dump("get_vat_percent: " . $dto->get_vat_percent());

        var_dump("get_sales_commission: ". $dto->get_sales_commission());

        var_dump("total_cost(d): " . $total_cost_d);

        var_dump("Selling_price: " . $selling_price);
    }

    public function get_profit_margin_json($platform_id, $sku, $required_selling_price, $required_cost_price = -1, $throw_exception = true)
    {
        // var_dump("HI");
        $this->set_tool_path('marketing/pricing_tool_'.strtolower(PLATFORM_TYPE));

        $dto = $this->get_dao()->get_price_cost_dto($sku, $platform_id);
        if ($dto == null)
        {
            if ($throw_exception)
                throw new Exception("[$sku] on [$platform_id] cannot be found, unable to calculate profit/margin");
            return false;
        }

        $this->perform_business_logic($dto, 5, $required_selling_price, $required_cost_price);
        if (1 == 10)
        {
            echo "<pre>";
            var_dump("get_supplier_cost:          " . $dto->get_supplier_cost());
            var_dump("get_logistic_cost:          " . $dto->get_logistic_cost());
            var_dump("get_listing_fee:            " . $dto->get_listing_fee());
            var_dump("get_payment_charge_percent: " . $dto->get_payment_charge_percent());
            var_dump("get_vat_percent:            " . $dto->get_vat_percent());
            var_dump("get_complementary_acc_cost: " . $dto->get_complementary_acc_cost());
            var_dump("get_cost:                   " . $dto->get_cost());
            die();
        }
        // $this->calc_auto_price_value($dto);

        // if ($dto->get_margin() >= -30)
        {
            $array = array(
            "local_sku"                     => $sku,
            "based_on"                      => $required_selling_price,
            "get_margin"                    => $dto->get_margin(),

            "get_price"                     => $dto->get_price(),

            "get_delivery_cost"             => $dto->get_delivery_cost(),
            "get_declared_value"            => $this->to_2_decimal($dto->get_declared_value()),

            "get_vat_percent"               => $dto->get_vat_percent(),
            "get_vat"                       => $dto->get_vat(),

            "get_sales_commission"          => $dto->get_sales_commission(),

            "get_duty_pcent"                => $dto->get_duty_pcent(),
            "get_duty"                      => $dto->get_duty(),

            "get_payment_charge_percent"    => $dto->get_payment_charge_percent(),
            "get_payment_charge"            => $dto->get_payment_charge(),

            "get_forex_fee_percent"         => $dto->get_forex_fee_percent(),
            "get_forex_fee"                 => $dto->get_forex_fee(),

            "get_listing_fee"               => $dto->get_listing_fee(),

            "get_logistic_cost"             => $dto->get_logistic_cost(),
            "get_supplier_cost"             => $dto->get_supplier_cost(),

            "get_complementary_acc_cost"    => $dto->get_complementary_acc_cost(),

            "get_cost"                      => $dto->get_cost(),
            "get_price"                     => $this->to_2_decimal($dto->get_price()),
            "get_profit"                    => $this->to_2_decimal($dto->get_price()) - $dto->get_cost()

            );
        }

        // var_dump($array);
        // header('Content-type: application/json');
        return json_encode($array);
    }

    public function update_sku_price($platform_id = "", $local_sku = "", $price = "", $commit = false)
    {
        $affected = $this->get_dao()->update_sku_price($platform_id, $local_sku, $price, $commit);

        if($affected)
            $this->price_margin_service->refresh_all_platform_margin(array("id"=>$platform_id), $local_sku);

        $this->get_product_update_followup_service()->adwords_update($local_sku);
        $this->get_product_update_followup_service()->google_shopping_update($local_sku);
        return $affected;
    }

    public function commit()
    {
        $this->get_dao()->commit();
    }

    public function auto_pricing_by_platform($platform_id)
    {
        $price_list = $this->get_platform_price_list(array("p.platform_id"=>$platform_id, "auto_price"=>"Y"), array("limit"=>-1));
        // $price_list = $this->integration_model->get_platform_price_list(array("p.platform_id"=>$platform_id, "auto_price"=>"Y"), array("limit"=>-1));
        if($price_list)
        {
            $title = '[VB] AUTO PRICE BY PLATFORM';
            $message = "PLATFORM=>SKU\n";
            foreach($price_list AS $price_obj)
            {
                $this->update_auto_price($price_obj->get_platform_id(), $price_obj->get_sku());
                // $this->integration_model->update_auto_price($price_obj->get_platform_id(), $price_obj->get_sku());
                $message .= $price_obj->get_platform_id()."=>".$price_obj->get_sku()."\n";
            }
            // mail($this->notification_email, $title, $message);
        }
    }

}
