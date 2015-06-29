<?php
    $this->load->helper('tbswrapper');
    $this->tbswrapper = new Tbswrapper();

    if ($data['success'])
    {
        $this->tbswrapper->tbsLoadTemplate('resources/template/payment_result.html', '', '', $data['lang_text']);

        $total = 0;
        $promo_disc_amount = 0;
        $gst_total = 0;
        $order_item_list = array();

        $i = 0;
        $item_list = $data['so_items'];
        foreach ($item_list as $so_item)
        {
            $total += ($so_item->get_qty() * $so_item->get_unit_price());
            $promo = (($so_item->get_qty() * $so_item->get_unit_price()) - $so_item->get_amount());

            if (is_numeric($promo))
                $promo_disc_amount += $promo;
            if (is_numeric($so_item->get_gst_total()))
                $gst_total += $so_item->get_gst_total();

            $order_item_list[$i]['image'] = get_image_file($so_item->get_main_img_ext(), 's', $so_item->get_prod_sku());
            $order_item_list[$i]['content_prod_name'] = $so_item->get_name();
            $order_item_list[$i]['qty'] = $so_item->get_qty();
            $order_item_list[$i]['price'] = platform_curr_format(PLATFORMID, $so_item->get_amount());

            $i++;
        }

        $delivery_charge = $data['so']->get_delivery_charge();
        $grand_total = $total + $delivery_charge - $promo_disc_amount + $gst_total;

        if ($promo_disc_amount == 0)
            $promo_disc_amount = '';
        if ($gst_total == 0)
            $gst_total = '';
    }
    else
    {
        if ($data['payment_retry'])
        {
            $this->tbswrapper->tbsLoadTemplate('resources/template/payment_retry.html', '', '', $data['lang_text']);
/*
            $visa_card_url = isset($data['lang_text']['card_message_visa_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['lang_text']['card_message_visa_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
            $master_card_url = isset($data['lang_text']['card_message_master_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['lang_text']['card_message_master_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
            $cvv_url = isset($data['lang_text']['card_message_cvv_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['lang_text']['card_message_cvv_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
            $this->tbswrapper->tbsMergeField('visa_card_url', $visa_card_url);
            $this->tbswrapper->tbsMergeField('master_card_url', $master_card_url);
            $this->tbswrapper->tbsMergeField('cvv_url', $cvv_url);
*/
            $this->tbswrapper->tbsMergeField('site_down', ($data['site_down'])?1:0);

            if (isset($data['lang_text']['card_message']) && ($data['lang_text']['card_message'] != ""))
            {
                $card_explantion_area = $data['lang_text']['card_message'];
                $card_explantion_area .= "<br>
                <div style=\"padding-top:10px;text-align:center;\">
                    <img src='" . base_cdn_url() . "/resources/images/" . get_lang_id() . "_cards.jpg' border='0'>
                </div>";
            }
            else
            {
                $card_explantion_area .= "<div style='padding-top:10px;text-align:left;'>
                    <div>
                        <div style='float:left;padding-top:6px;'><img width='120px' src='" . base_cdn_url() . "/resources/images/card_explain_visa.jpg' border='0'></div>
                        <div style=\"float:left;padding-top:10px;width:500px;font-size:13px;\">" .
                            $data['lang_text']['card_message_visa'];

                $card_url = isset($data['lang_text']['card_message_visa_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['lang_text']['card_message_visa_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
                if ($card_url != "")
                {

                    $card_explantion_area .= "<a target='_blank' href=\"https://" .  $card_url . "\" style=\"font-weight:bold;text-decoration:underline;\">" . 
                                $data['lang_text']['card_message_click_here'] . 
                            "</a>";

                }

                    $card_explantion_area .= "</div>
                    </div>
                    <div style=\"clear:both;\"></div>
                    <div>
                        <div style=\"float:left;\"><img width=\"120px\" src='" . base_cdn_url() . "/resources/images/card_explain_master.jpg' border='0'></div>
                        <div style=\"float:left;padding-top:10px;width:500px;font-size:13px;\">" . 
                            $data['lang_text']['card_message_master'];

                    $card_url = isset($data['lang_text']['card_message_master_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['lang_text']['card_message_master_url_' . strtolower(PLATFORMCOUNTRYID)]:"";
                    if ($card_url != "")
                    {
                        $card_explantion_area .= "<a target='_blank' href='https://" . $card_url . "' style=\"font-weight:bold;text-decoration:underline;\">"
                                                . $data['lang_text']['card_message_click_here'] .
                                                "</a>";
                    }

                        $card_explantion_area .= "</div>
                    </div>
                    <div style=\"clear:both;\"></div>
                    <div style=\"float:left;padding-top:10px;width:600px;font-size:13px;\">" . 
                        $data['data']['lang_text']['card_message_cvv'] . 

                    $cvv_url = isset($data['lang_text']['card_message_cvv_url_' . strtolower(PLATFORMCOUNTRYID)]) ? $data['lang_text']['card_message_cvv_url_' . strtolower(PLATFORMCOUNTRYID)]:"";

                    
                    $card_explantion_area .= "<a target='_blank' href='https://" . $cvv_url . "' style=\"font-weight:bold;text-decoration:underline;\">" . 
                                $data['lang_text']['card_message_click_here'] . 
                            "</a>
                    </div>
                </div>";
            }
            $this->tbswrapper->tbsMergeField('card_explantion_area', $card_explantion_area);
        }
        else
        {
            $this->tbswrapper->tbsLoadTemplate('resources/template/payment_fail.html', '', '', $data['lang_text']);
        }

        $lang_id = strtoupper(get_lang_id());
        switch($lang_id)
        {
            case "EN": $enquiry_question_id = '105';
            case "ES": $enquiry_question_id = '113';
            case "FR": $enquiry_question_id = '132';
            case "IT": $enquiry_question_id = '140';
            default : $enquiry_question_id = '105';
        }
        $this->tbswrapper->tbsMergeField('enquiry_question_id', $enquiry_question_id);

        $total = 0;
        $cart_item = $data['cart_item'];
        $chk_cart = $data['chk_cart'];
        $order_item_list = array();
        for($i=0; $i<count($chk_cart); $i++)
        {
            $total += $chk_cart[$i]["price"] * $chk_cart[$i]["qty"];
            $item = $cart_item[$chk_cart[$i]["sku"]];

            $order_item_list[$i]['image'] = get_image_file($item->get_image(), 's', $item->get_sku());
            $order_item_list[$i]['content_prod_name'] = $item->get_content_prod_name()?$item->get_content_prod_name():$item->get_prod_name();
            $order_item_list[$i]['qty'] = $chk_cart[$i]["qty"];
            $order_item_list[$i]['price'] = platform_curr_format(PLATFORMID, $chk_cart[$i]["price"]);
        }

        $promo_disc_amount = '';
        if ($data['promo_disc_amount'] != '')
        {
            $promo_disc_amount = platform_curr_format(PLATFORMID, $data['promo_disc_amount']);
        }

        $gst_total = '';
        if ($data['gst_order'])
        {
            $gst_total = platform_curr_format(PLATFORMID, $data["gst_total"]);
        }
        $delivery_charge = $data["dc_default"]["charge"];
        $grand_total = $total + $delivery_charge - $data['promo_disc_amount'] + $data["gst_total"];
    }

    $this->tbswrapper->tbsMergeField('base_url', base_url());
    $this->tbswrapper->tbsMergeField('contact_operation_hour', $data['contact_info']['operating_hours']);
    $this->tbswrapper->tbsMergeField('debug',   $data['debug']);
    $this->tbswrapper->tbsMergeField('postform', $data['postform']);
    $this->tbswrapper->tbsMergeBlock('order_item_list', $order_item_list);
    $this->tbswrapper->tbsMergeField('promo_disc_amount', $promo_disc_amount);
    $this->tbswrapper->tbsMergeField('gst_total', $gst_total);
    $this->tbswrapper->tbsMergeField('subtotal', platform_curr_format(PLATFORMID, $total));
    $this->tbswrapper->tbsMergeField('delivery_charge', platform_curr_format(PLATFORMID, $delivery_charge));
    $this->tbswrapper->tbsMergeField('grand_total', platform_curr_format(PLATFORMID, $grand_total));

    $google_adword_script  = "";
    if(!$data['success'])
    {
        if ($data['client'])
        {
// if not cancel payment
#       SBF #2210 generate info for auto-filled Pre-Sales contact form in payment failure
            $fullname = $data['client']->get_forename() . ' ' . $data['client']->get_surname();
            $email = $data['client']->get_email();
            $client_tel = trim(($data["client"]->get_tel_1() . '-' . $data["client"]->get_tel_2() . '-' . $data["client"]->get_tel_3()), ' -');
            foreach ($data['so_items'] as $key => $row) 
            {
                $items .= $row->get_name() . ', ';
            }
            $item_list = rtrim($items, ', ');
            $url_get_info = "?fullname=$fullname&email=$email&phone_no=$client_tel&item_country=$item_list";
        }

        # SBF #2188, #2189, #2192 Failed payment msg content - phone & contact page change according to country
        if ((empty($cs_phone_no)) || (PLATFORMCOUNTRYID == "ES") || (PLATFORMCOUNTRYID == "FR") || (PLATFORMCOUNTRYID == "BE"))  // $cs_phone_no is defined in PUB_Controller
        {
            $hotline = '';
        }
        else
        {
            $hotline = $cs_phone_no;
        }
        $this->tbswrapper->tbsMergeField('hotline', $hotline);
        # End SBF #2188, #2189, #2192 Failed payment msg content - phone & contact page change according to country
    }
    else
    {
        $so_no = $data["so"]->get_client_id().'-'.$data["so"]->get_so_no();
        if ($data["so_items"])
        {
            $ar_prod_name = array();
            foreach ($data["so_items"] as $so_item)
            {
                $ar_prod_name[] = $so_item->get_name();
            }
        }
        $prod_name =  @implode("<br/>", $ar_prod_name);
        $client_email = $_SESSION["client"]["email"];

#       SBF #2308 change contents of payment success page
        $success = '1';
        $content['header'] = $data['lang_text']['payment_success'];
        $content['content'] =   $data['lang_text']['payment_success_content_1'] . $so_no . '.' .
                                $data['lang_text']['payment_success_content_2'] . 
                                '<a href="mailto:' . $data["client"]->get_email() . '">' . $data["client"]->get_email() . '</a>' .
                                $data['lang_text']['payment_success_content_3'];
        $content['content_2'] = $data['lang_text']['payment_success_content_4'] . '<a href="' . base_url() . 'contact">' . 
                                $data['lang_text']['payment_success_content_5'] . '</a>' . $data['lang_text']['payment_success_content_6'];
        $content['title_shipping'] = $data['lang_text']['payment_success_table_header_1'];
        $content['title_order'] = $data['lang_text']['payment_success_table_header_2'];
        $content['title_phone'] = $data['lang_text']['payment_success_table_header_3'];

        // adds billing address, delivery address & items to payment_result.html
        $billing_addr['forename'] = $data['so']->get_bill_name();
        $billing_addr['surname'] = '';
        $billing_addr['companyname'] = $data['so']->get_bill_company();
        $billing_addr['tel_1'] = $data['client']->get_tel_1();
        $billing_addr['tel_2'] = $data['client']->get_tel_2();
        $billing_addr['tel_3'] = $data['client']->get_tel_3();
        list($billing_addr['address_1'], $billing_addr['address_2'], $billing_addr['address_3']) = explode('|', $data['so']->get_bill_address());
        $billing_addr['city'] = $data['so']->get_bill_city();
        $billing_addr['state'] = $data['so']->get_bill_state();
        $billing_addr['postcode'] = $data['so']->get_bill_postcode();
        $billing_addr['country_id'] = $data['so']->get_bill_country_id();

        $delivery_addr['del_first_name'] = $data['so']->get_delivery_name();
        $delivery_addr['del_last_name'] = '';
        $delivery_addr['del_company'] = $data['so']->get_delivery_company();
        $delivery_addr['del_tel_1'] = $data['client']->get_del_tel_1();
        $delivery_addr['del_tel_2'] = $data['client']->get_del_tel_2();
        $delivery_addr['del_tel_3'] = $data['client']->get_del_tel_3();
        list($delivery_addr['del_address_1'], $delivery_addr['del_address_2'], $delivery_addr['del_address_3']) = explode('|', $data['so']->get_delivery_address());
        $delivery_addr['del_city'] = $data['so']->get_delivery_city();
        $delivery_addr['del_state'] = $data['so']->get_delivery_state();
        $delivery_addr['del_postcode'] = $data['so']->get_delivery_postcode();
        $delivery_addr['del_country_id'] = $data['so']->get_delivery_country_id();

        $this->tbswrapper->tbsMergeField('billing_addr', $billing_addr);
        $this->tbswrapper->tbsMergeField('delivery_addr', $delivery_addr);

        #check if address-state exists 
        if($data['so']->get_delivery_state())
            {   
                $delivery_cust['state'] = $data['so']->get_delivery_state();
            }
        else {$delivery_cust['state'] = ""; }
        #remove extra pipes from address
        $address_remove_pipe = array_filter((explode("|", $data['so']->get_delivery_address())));
        foreach ($address_remove_pipe as $key => $value) 
        {
            $address_array[] = trim($value, " ");
            $delivery_cust['address'] = implode(" ", $address_array);
        }

        $items[] = array();
        foreach ($data['so_items'] as $key => $row) 
        {
            $items = $row->get_name();
            $qty = $row->get_qty();
            $delivery_cust['items'] .= $qty . ' x ' . $items . "<br>";
        }
    }
    
    $emailvision_trackingpixel_url = "";
    $tradedoubler_trackingpixel_url = "";
    if ($data['success'])   # only when success
    {   
        $so = $data['so'];
        
        $so_items = ($data["so_items"]);
        
        foreach ($data["skuinfo"] as $sku)
        {
            $cat_name   .= "{$sku['cat_name']};";
            $sc_name    .= "{$sku['sc_name']};";
            $brand      .= "{$sku['brand']};";
        }
    
        $cat_name   = trim($cat_name,";");
        $sc_name    = trim($sc_name,";");
        $brand      = trim($brand,";");
    
        $so_date = date("Y-m-d",strtotime($so->get_order_create_date()));
    
        #SBF#1658
        #updated by SBF#2421
        $emailvision_trackingpixel_url = "http://evmail.valuebasket.com/P?"
            ."&emv_conversionflag=Y"
            ."&emv_value={$so->get_amount()}"
            ."&emv_currency={$so->get_currency_id()}"
            ."&emv_transid={$so->get_so_no()}"
            ."&emv_text1=".urlencode($cat_name)
            ."&emv_text2=".urlencode($sc_name)
            ."&emv_text3=".urlencode($brand)
            ."&emv_date=".urlencode($so_date)
            ."&emv_client_id=1101037361";
    
        $emailvision_trackingpixel_url = str_replace("\n", "", $emailvision_trackingpixel_url);
    
        { # SBF 1755/2235 - using a better defined org_id
            switch (PLATFORMCOUNTRYID)
            {
                # SBF 2235
                case "AU": case "FI": case "ES": case "HK":case "IE": case "MY": case "NZ": case "SG": case "US":
                    $org_id = "ESERVICES_" . PLATFORMCOUNTRYID;
                    break;
                # SBF 1755
                default:
                    $org_id = "ESERVICES";
                    break;
            }
        }

        $orderNumber = $so->get_so_no();
        $reportInfo = "";
                
        if ($so_items)
        {
            $cat_name = null;
            foreach ($data["skuinfo"] as $sku)
                $cat_name[] = "{$sku['cat_name']};";
                
            $f = "";
            $i = 0;
            foreach ($so_items as $item)
            {
                $f1 = urlencode($item->get_prod_sku());
                $f2 = urlencode($item->get_name());
                $f3 = urlencode($item->get_amount());
                $f4 = urlencode($item->get_qty());
                $f .= "$f1|$f4|$f3|{$cat_name[$i]};";
                $i++;               
            }
            $reportInfo = trim($f, ";");
        }
        #https://cpr.prismastar.com/v2_0/recorder/? customerCode=[CUSTOMER_CODE]
        #&customerOrderId=[ORDER_ID]& ID]|[QUANTITY]|[ITEM_TOTAL]|[CATEGORY_CODE]
        $primastar_trackingpixel_url = "http://valuebasket22.cpr.prismastar.com/v2_0/recorder/?"
                ."type=order"
                ."&customerCode=".$org_id               
                ."&customerOrderId=".$orderNumber
                ."&order=".$reportInfo;             
                            
        $primastar_trackingpixel_url = str_replace("\r\n", "", $primastar_trackingpixel_url);       
    }

    $this->tbswrapper->tbsMergeField('primastar_trackingpixel_url', $primastar_trackingpixel_url);
    $this->tbswrapper->tbsMergeField('emailvision_trackingpixel_url', $emailvision_trackingpixel_url);

    $this->tbswrapper->tbsMergeField('content', $content);

    $tracking_script = $data["tracking_script"];
    $this->tbswrapper->tbsMergeField('tracking_script', $tracking_script);
    $this->tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
    $this->tbswrapper->tbsMergeField('language_id',get_lang_id());
    $this->tbswrapper->tbsMergeField('client_tel', $client_tel);
    $this->tbswrapper->tbsMergeField('delivery_cust', $delivery_cust);
    $this->tbswrapper->tbsMergeField('success', $success);

    echo $this->tbswrapper->tbsRender();