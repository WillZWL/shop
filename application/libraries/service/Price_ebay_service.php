<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Price_service.php";

class Price_ebay_service extends Price_service
{

    private $listing_fee = array(
                                "GB"=>array("bound"=>array(0),
                                            "fee"=>array(0.4, 0.4)),
                                "AU"=>array("bound"=>array(19.99, 99.99),
                                            "fee"=>array(0.2, 0.2, 0.2)),
                                "US"=>array("bound"=>array(0),
                                            "fee"=>array(0, 0)),
                                "SG"=>array("bound"=>array(0),
                                            "fee"=>array(0, 0)),
                                "MY"=>array("bound"=>array(0),
                                            "fee"=>array(0, 0))
                            );

    private $ar_commission = array(
                                /*
                                "GB"=>array("bound"=>array(29.99, 99.99, 199.99, 299.99, 599.99),
                                            "pcent"=>array(0.0525, 0.03, 0.025, 0.02, 0.015, 0.01),
                                            "adj"=>array(1.57, //29.99*5.25%
                                                        3.67, //1.57 + (99.99-29.99)*3%
                                                        6.17, //3.67 + (199.99-99.99)*2.5%
                                                        8.17, //6.17 + (299.99-199.99)*2%
                                                        12.67 //8.17 + (599.99-299.99)*1.5%
                                                        )),
                                */
                                "GB"=>array("bound"=>array(0, 0),
                                            "pcent"=>array(0.1, 0.1, 0.1),
                                            "adj"=>array(0, 0),
                                            "max_charge"=>40),
                                "AU"=>array("bound"=>array(0, 0),
                                            "pcent"=>array(0.079, 0.079, 0.079),
                                            "adj"=>array(0, 0),
                                            "max_charge"=>250),
                                "US"=>array("bound"=>array(0, 0),
                                            "pcent"=>array(0.1, 0.1, 0.1),
                                            "adj"=>array(0, 0),
                                            "max_charge"=>-1),
                                "SG"=>array("bound"=>array(0, 0),
                                            "pcent"=>array(0, 0, 0),
                                            "adj"=>array(0, 0),
                                            "max_charge"=>-1),
                                "MY"=>array("bound"=>array(0, 0),
                                            "pcent"=>array(0.024, 0.024, 0.024),
                                            "adj"=>array(0, 0),
                                            "max_charge"=>-1)
                                );


        private $commission_pcent_by_price = array(
                                "EBAYAU" => array(
                                    array('sub_cat_id'=>array(11,12,13,14,15,31,32,33,37,38,39,42,43,44,45,46,47,48,53,54,60,65,66,72,73,74,76,339,350,360,362,368,383,417,422,482,521,530,539,541,543,567,589,628,652,672,683,723,743,749),
                                            'price_boundary'=>200,
                                            'commission_percentage'=>7
                                        ),
                                    array('sub_cat_id'=>array(16,17,18,19,20,21,22,23,24,25,26,28,29,30,34,35,36,40,41,49,50,51,52,55,58,59,61,62,63,64,75,361,376,386,393,394,419,433,466,472,491,518,523,538,572,655,701,702,703,704,705,706,707,708,709,710,712,736,754,755),
                                            'price_boundary'=>200,
                                            'commission_percentage'=>8.5
                                        )
                                )


        );


    private $paypal_fee_adj = 0.3;

    public function __construct()
    {
        parent::__construct();
        $this->set_tool_path('marketing/pricing_tool_'.strtolower(PLATFORM_TYPE));
    }
/*
    public function draw_table_header_row($dto = NULL)
    {
        $this->init_dto($dto);

        $header .= "\$header = \"<tr class='header'>
                        <td>&nbsp;</td>
                        <td>\$lang[selling_price]</td>
                        <td>\$lang[declared]<br>(".$dto->get_declared_pcent()."%)</td>
                        <td>\$lang[vat]<br>(".$dto->get_vat_percent()."%)</td>
                        <td>\$lang[platform_commission]</td>
                        <td>\$lang[listing_fee]</td>
                        <td>\$lang[duty]<br>(".$dto->get_duty_pcent()."%)</td>
                        <td nowrap style='white-space:nowrap'>\$lang[paypal_fee]<br>(".$dto->get_payment_charge_percent()."%)</td>
                        <td>\$lang[admin_fee]</td>
                        <td>\$lang[logistic_cost]</td>
                        <td>\$lang[cost]</td>
                        <td>\$lang[total_cost]</td>
                        <td>\$lang[delivery]</td>
                        <td>\$lang[total]</td>
                        <td>\$lang[profit]</td>
                        <td>\$lang[margin]</td>
                    </tr>\";";
        return $header;
    }

    public function draw_table_row_for_pricing_tool($dto = NULL, $default_shiptype="")
    {
        $this->init_dto($dto);

        $delivery = $dto->get_delivery_charge();

        $total = $dto->get_price() + $dto->get_delivery_charge();

        $bgcolor = $total > $dto->get_cost()?"#ddffdd":"#ffdddd";

        $platform = $dto->get_platform_id();
        $country_id = $dto->get_platform_country_id();

        $table_row .='<tr id="row['.$platform.']" style="background-color:'.$bgcolor.'">
                        <td>
                            <input type="hidden" id="declared_rate['.$platform.']" value="'.$dto->get_declared_pcent().'">
                            <input type="hidden" id="payment_charge_rate['.$platform.']" value="'.$dto->get_payment_charge_percent().'">
                            <input type="hidden" id="vat_percent['.$platform.']" value="'.$dto->get_vat_percent().'">
                            <input type="hidden" id="duty_percent['.$platform.']" value="'.$dto->get_duty_pcent().'">
                            <input type="hidden" id="free_delivery_limit['.$platform.']" value="'.$dto->get_free_delivery_limit().'">
                            <input type="hidden" id="default_delivery_charge['.$platform.']" value="'.$dto->get_default_delivery_charge().'">
                            <input type="hidden" id="scost['.$platform.']" value="'.$dto->get_supplier_cost().'">
                            <input type="hidden" id="country_id['.$platform.']" value="'.$country_id.'">
                        </td>
                        <td><input type="text" name="selling_price['.$platform.']" value="'.($dto->get_current_platform_price()*1).'" id="sp['.$platform.']" onKeyup="rePrice(\''.$platform.'\')" style="width:50px;" notEmpty></td>
                        <td id="declare['.$platform.']">'.number_format($dto->get_declared_value(),2, ".", "").'</td>
                        <td id="vat['.$platform.']">'.number_format($dto->get_vat(),2, ".", "").'</td>
                        <td id="comm['.$platform.']">'.number_format($dto->get_sales_commission(),2, ".", "").'</td>
                        <td id="listing_fee['.$platform.']">'.$this->listing_fee[$country_id].'</td>
                        <td id="duty['.$platform.']">'.number_format($dto->get_duty(),2, ".", "").'</td>
                        <td id="pc['.$platform.']">'.number_format($dto->get_payment_charge(),2, ".", "").'</td>
                        <td id="admin_fee['.$platform.']">'.number_format($dto->get_admin_fee(),2, ".", "").'</td>
                        <td id="logistic_cost['.$platform.']">'.number_format($dto->get_logistic_cost(),2, ".", "").'</td>
                        <td id="supplier_cost['.$platform.']">'.number_format($dto->get_supplier_cost(),2, ".", "").'</td>
                        <td id="total_cost['.$platform.']">'.number_format($dto->get_cost(),2, ".", "").'</td>
                        <td id="delivery_charge['.$platform.']">'.number_format($delivery,2, ".", "").'</td>
                        <td id="total['.$platform.']">'.number_format(($dto->get_price() + $delivery),2, ".", "").'</td>
                        <td id="profit['.$platform.']">'.number_format($dto->get_profit(),2, ".", "").'</td>
                        <td id="margin['.$platform.']">'.number_format($dto->get_margin(),2, ".", "").'%</td>
                     </tr>
                    '."\n";
        return $table_row;
    }
*/
    public function calc_cost($dto = NULL)
    {
        $this->init_dto($dto);
        $this->calc_dto_data();
        // calcualte ebay listing fee
        $this->calc_listing_fee();

        $country_id = $dto->get_platform_country_id();

        $dto->set_cost(number_format($dto->get_vat()
                        + $dto->get_supplier_cost()
                        + $dto->get_logistic_cost()
                        + $dto->get_payment_charge()
                        + $dto->get_sales_commission()
                        + $dto->get_duty()
                        + $dto->get_listing_fee(), 2, ".", ""));
    }

    public function calc_commission($dto = NULL)
    {
        $this->init_dto($dto);

        $country_id = $dto->get_platform_country_id();

        $price = $dto->get_price();
        $sub_cat_id = $dto->get_sub_cat_id();
        if($dto->get_platform_id() == "EBAYAU")
        {
            if(array_key_exists($dto->get_platform_id(), $this->commission_pcent_by_price))
            {
                foreach($this->commission_pcent_by_price as  $platform_level)
                {
                    foreach($platform_level as $bottom_level)
                    {
                        $sub_cat_id_list = $bottom_level['sub_cat_id'];

                        $price_bounday = $bottom_level['price_boundary'];
                        if(in_array($sub_cat_id,$sub_cat_id_list ) && ($price <$price_bounday))
                        {
                            $commission_pcent = $bottom_level['commission_percentage'];
                            break 2;
                        }
                    }
                }

                if(!isset($commission_pcent))
                {
                    $commission_pcent = $dto->get_platform_commission();
                }
            }
            else
            {
                $commission_pcent = $dto->get_platform_commission();
            }

            $commission = round($price*$commission_pcent*0.01, 2);
            $dto->set_sales_commission(number_format(($this->ar_commission[$country_id]["max_charge"] == -1 ? $commission : min($commission, $this->ar_commission[$country_id]["max_charge"])), 2, ".", ""));
        }
        else
        {
            if (isset($this->ar_commission[$country_id]))
            {
                $commission = 0;
                $total_bound = count($this->ar_commission[$country_id]["bound"]);
                $last_bound = $total_bound-1;

                if ($price<=$this->ar_commission[$country_id]["bound"][0])
                {
                    $commission = round($price*$this->ar_commission[$country_id]["pcent"][0], 2);
                }
                elseif($price > $this->ar_commission[$country_id]["bound"][$last_bound])
                {
                    $commission = $this->ar_commission[$country_id]["adj"][$last_bound-1]
                                + ($price - $this->ar_commission[$country_id]["bound"][$last_bound-1]) * $this->ar_commission[$country_id]["pcent"][$last_bound];
                }
                else
                {
                    for($i=1; $i<$total_bound; $i++)
                    {
                        if ($price<=$this->ar_commission[$country_id]["bound"][$i])
                        {
                            $commission = $this->ar_commission[$country_id]["adj"][$i-1]
                                        + ($price - $this->ar_commission[$country_id]["bound"][$i-1]) * $this->ar_commission[$country_id]["pcent"][$i];
                            break;
                        }
                    }
                }
                $dto->set_sales_commission(number_format(($this->ar_commission[$country_id]["max_charge"] == -1 ? $commission : min($commission, $this->ar_commission[$country_id]["max_charge"])), 2, ".", ""));

            }
            else
            {
                $dto->set_sales_commission(0);
            }
        }
    }

    public function calc_listing_fee($dto = NULL)
    {
        $this->init_dto($dto);

        $country_id = $dto->get_platform_country_id();

        if (isset($this->ar_commission[$country_id]))
        {
            $commission = 0;
            $price = $dto->get_price();
            $total_bound = count($this->listing_fee[$country_id]["bound"]);
            $last_bound = $total_bound-1;

            if ($price<=$this->listing_fee[$country_id]["bound"][0])
            {
                $listing_fee = $this->listing_fee[$country_id]["fee"][0];
            }
            elseif($price > $this->listing_fee[$country_id]["bound"][$last_bound])
            {
                $listing_fee = $this->listing_fee[$country_id]["fee"][$last_bound+1];
            }
            else
            {
                for($i=1; $i<$total_bound; $i++)
                {
                    if ($price<=$this->listing_fee[$country_id]["bound"][$i])
                    {
                        $listing_fee = $this->listing_fee[$country_id]["fee"][$i];
                        break;
                    }
                }
            }
            $dto->set_listing_fee($listing_fee);
        }
        else
        {
            $dto->set_listing_fee(0);
        }
    }

    public function calc_payment_charge($dto = NULL)
    {
        $this->init_dto($dto);

        $dto->set_payment_charge(number_format($dto->get_price() * $dto->get_payment_charge_percent()/100 + $this->paypal_fee_adj, 2, ".", ""));
    }

    public function calc_delivery_charge($dto = NULL)
    {
        $this->init_dto($dto);

        $dto->set_default_delivery_charge(0);
        $dto->set_delivery_charge(0);
    }

    public function get_listing_fee()
    {
        return $this->listing_fee;
    }

    public function get_ar_commission()
    {
        return $this->ar_commission;
    }

    public function get_paypal_fee_adj()
    {
        return $this->paypal_fee_adj;
    }
}
/* End of file price_website_service.php */
/* Location: ./system/application/libraries/service/Price_website_service.php */
