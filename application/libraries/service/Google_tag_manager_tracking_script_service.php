<?php

include_once(APPPATH."libraries/service/Atom_tracking_script_service.php");

class Google_tag_manager_tracking_script_service extends Atom_tracking_script_service
{
    const GTM_CONTAINER_ID = 'GTM-77HZ';

    public function __construct()
    {
        parent::Atom_tracking_script_service();
    }

    public function is_registered_page($page)
    {
        $result = false;

        $current_page_info = $page;

        if ($this->is_home_page($page))
        {
            $result = true;
        }
        else if ($this->is_cat_page($page))
        {
            $result = true;
        }
        else if ($this->is_mainproduct_page($page))
        {
            $result = true;
        }
        else if ($this->is_ra_page($page))
        {
//RA
            $result = true;
        }
        else if ($this->is_review_page($page))
        {
            $result = true;
        }
        else if ($this->is_checkout_page($page))
        {
            $result = true;
        }
        else if ($this->is_payment_result_page($page))
        {
//payment success / failure
            $result = true;
        }

        return $result;
    }

    public function need_to_show_generic_tracking_page()
    {
        return true;
    }

    public function get_specific_code($page = array(), $var = array())
    {
        $script = "";

        return $this->_form_data_layer($page, $var) . $this->_show_common_script();
    }

    public function get_all_page_code($page = array(), $var = array())
    {
        return $this->_form_data_layer($page, $var) . $this->_show_common_script();
    }

    private function _show_common_script()
    {
        $containerId = self::GTM_CONTAINER_ID;
$scripts = <<<javascript
<!-- Google Tag Manager -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id={$containerId}" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{$containerId}');
    </script>
<!-- End Google Tag Manager -->
javascript;
        return $scripts;
    }

    private function _form_data_layer($page = array(), $var = array())
    {
        $pageInfo = $this->_page_title_mapping($page);
        $dataLayer = "<script>";
        $dataLayer .= 'var google_tag_params = { "countryId": "' . PLATFORMCOUNTRYID . '"';

        if ($this->is_home_page($page)
                || $this->is_cat_page($page)
                || $this->is_checkout_page($page)
                || $this->is_payment_failure_page($page))
        {
            $dataLayer .= $this->_put_common_remarketing($pageInfo, $var);
        }
        else if ($this->is_mainproduct_page($page))
        {
            $dataLayer .= $this->_remarketing_mainproduct_page($pageInfo, $var);
        }
        else if ($this->is_review_page($page))
        {
            $dataLayer .= $this->_remarketing_review_page($pageInfo, $var);
        }
        else if ($this->is_payment_success_page($page))
        {
            $dataLayer .= $this->_remarketing_payment_result(1, $pageInfo, $var);
        }
        else if ($this->is_bank_transfer_success_page($page))
        {
            $dataLayer .= $this->_bank_transfer_success_page($pageInfo, $var);
        }
        $dataLayer .= " }
                        </script>";

        $dataLayer .= "<script>
                        dataLayer = [{";
        $dataLayer .= '"google_tag_params": window.google_tag_params,';
        $dataLayer .= '"pageTitle": "' . $pageInfo['title'] . '",
                        "affiliateID": "'.$_COOKIE['af'].'",
                       "pageCountryID": "' . PLATFORMCOUNTRYID . '"';

        if ($this->is_home_page($page))
            $dataLayer .= $this->_home_page($pageInfo, $var);
        else if ($this->is_cat_page($page))
            $dataLayer .= $this->_category_page($pageInfo, $var);
        else if ($this->is_mainproduct_page($page))
            $dataLayer .= $this->_mainproduct_page($pageInfo, $var);
        else if ($this->is_review_page($page))
            $dataLayer .= $this->_review_order_page($pageInfo, $var);
        else if ($this->is_checkout_page($page))
        {
            $dataLayer .= $this->_checkout_page($pageInfo, $var);
        }
        else if ($this->is_payment_success_page($page))
        {
//hard code 1, so this will include order confirmation page for PH
            $dataLayer .= $this->_payment_result(1, $pageInfo, $var);
        }
        else if ($this->is_payment_failure_page($page))
        {
            $dataLayer .= $this->_payment_result($page['method_parameter1'], $pageInfo, $var);
        }

        $dataLayer .= '}];
                        </script>
                        ';
        return $dataLayer;
    }

    private function _bank_transfer_success_page($pageInfo = array(), $var = array())
    {
        return "";
    }

    private function _remarketing_payment_result($pass_or_fail, $pageInfo = array(), $var = array())
    {
        if ($pass_or_fail == 1)
        {
            $prodid = "";
            $pname = "";
            $pcat = "";
            $pvalue = "";
            $additionDataLayer .= '
                                ,"pagetype": "purchase"';

            foreach($var["soi"] as $product)
            {
                if ($prodid != "")
                    $prodid .= ",";
                if ($pname != "")
                    $pname .= ",";
                if ($pcat != "")
                    $pcat .= ",";
                if ($pvalue != "")
                    $pvalue .= ",";

                $prodid .= '"' . PLATFORMCOUNTRYID . "-" . $product->get_prod_sku() . '"';
                $pname .= '"' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->get_name()) . '"';
                $pcat .= '"' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->get_cat_name()) . '"';
                $pvalue .= $product->get_unit_price();
            }
            $additionDataLayer .= ',"prodid": [' . $prodid . ']
                                ,"pname": [' . $pname . ']
                                ,"pcat": [' . $pcat . ']
                                ,"pvalue": [' . $pvalue . ']';
        }
        return $additionDataLayer;
    }

    private function _payment_result($pass_or_fail, $pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_put_ecomm_page_type($pageInfo, $var);
        if ($pass_or_fail == 1)
        {
//success
            $additionDataLayer .= $this->_put_ecomm_products_info($pageInfo, $var);
            $additionDataLayer .= '
                                ,"conversionDate": "' . $var['so']->get_order_create_date() . '"
                                ,"conversionValue": ' . $var['total_amount'] . '
                                ,"clientName": "' . $var['so']->get_bill_name() . '"
                                ,"clientEmail": "' . $var['client_email'] . '"';

            $additionDataLayer .= '
                                ,"transactionId": "' . $var['so']->get_so_no() . '"
                                ,"transactionDate": "' . $var['so']->get_order_create_date() . '"
                                ,"transactionType": "' . $var['so']->get_biz_type() . '"
                                ,"transactionAffiliation": "' . $var['affiliate_name'] . '"
                                ,"transactionTotal": ' . $var['total_amount'] . '
                                ,"transactionShipping": ' . $var['so']->get_delivery_charge() . '
                                ,"transactionPaymentType": "' . $var['sops']->get_payment_gateway_id() . '"
                                ,"transactionCurrency": "' . $var['so']->get_currency_id() . '"
                                ,"transactionPromoCode": "' . $var['so']->get_promotion_code() . '"
                                ,"transactionProducts": [' . $this->_put_transaction_product_object($pageInfo, $var) . ']';

        }
        return $additionDataLayer;
    }

    private function _checkout_page($pageInfo = array(), $var = array())
    {
        return $this->_put_ecomm_page_type($pageInfo, $var);
    }

    private function _review_order_page($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_put_ecomm_page_type($pageInfo, $var);
        $additionDataLayer .= $this->_put_ecomm_products_info($pageInfo, $var);

        return $additionDataLayer;
    }

    private function _remarketing_review_page($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_put_common_remarketing($pageInfo, $var);

        if ($var["products"])
        {
            $skus = "";
            $price = "";
            foreach($var["products"] as $product)
            {
                if ($skus != "")
                    $skus .= ",";
                if ($price != "")
                    $price .= ",";
                $skus .= '"' . PLATFORMCOUNTRYID . "-" . $product["sku"] . '"';
                $price .= $product["unit_price"];
            }
            $additionDataLayer .= '
                                ,"prodid": [' . $skus . ']';
            $additionDataLayer .= '
                                ,"pvalue": [' . $price . ']';
        }
        return $additionDataLayer;
    }

    private function _mainproduct_page($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_put_ecomm_page_type($pageInfo, $var);
        $additionDataLayer .= '
                            ,"ecomm_prodid": "' . $var['sku'] . '"
                            ,"ecomm_totalvalue": "' . $var['price'] . '"';
        $additionDataLayer .= '
                            ,"pageCategory": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category']) . '"
                            ,"pageProductName": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"
                            ,"productSKU": "' . $var['sku'] . '"
                            ,"productName": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"';
        return $additionDataLayer;
    }

    private function _remarketing_mainproduct_page($pageInfo = array(), $var = array())
    {
        $additionDataLayer .= '
                            ,"prodid": ["' . PLATFORMCOUNTRYID . "-" . $var['sku'] . '"]
                            ,"pagetype": "product"
                            ,"pname": ["' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"]
                            ,"pcat": ["' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category']) . '"]
                            ,"pvalue": ["' . $var['price'] . '"]';
        return $additionDataLayer;
    }

    private function _category_page($pageInfo = array(), $var = array())
    {
        $additionDataLayer = '
                            ,"ecomm_pagetype": "' . $pageInfo['ecom_title'] . '"';
        $additionDataLayer .= '
                            ,"pageCategory": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category_name']) . '"
                            ,"pageValue": ' . $var['category_id'];
        return $additionDataLayer;
    }

    private function _home_page($pageInfo = array(), $var = array())
    {
        return $this->_put_ecomm_page_type($pageInfo, $var);
    }

    private function _page_title_mapping($page = array())
    {
        $pageTitle = "";
        $ecommPagetype = "";

        if ($page['class'] == "redirect_controller")
        {
            $pageTitle = "HomePage";
            $ecommPagetype = "home";
        }
        else if ($page['class'] == "cat")
        {
            $pageTitle = "CategoryPage";
            $ecommPagetype = "category";
        }
        else if ($page['class'] == "mainproduct")
        {
            $pageTitle = "ProductPage";
            $ecommPagetype = "product";
        }
        else if ($page['class'] == "cart")
        {
//RA
            $pageTitle = "RA";
        }
        else if ($page['class'] == "review_order")
        {
            $pageTitle = "Review";
            $ecommPagetype = "cart";
        }
        else if ($this->is_checkout_page($page))
        {
            $pageTitle = "Checkout";
            $ecommPagetype = "siteview";
        }
        else if ($this->is_payment_success_page($page))
        {
//payment success
            $pageTitle = "PaymentSuccess";
            $ecommPagetype = "purchase";
        }
        else if ($this->is_payment_failure_page($page))
        {
            $pageTitle = "PaymentFailed";
            $ecommPagetype = "siteview";
        }
        else if ($page['class'] == "display")
        {
            if ($this->is_about_us($page))
            {
                $pageTitle = "AboutUs";
            }
            else if ($this->is_conditions_of_use($page))
            {
                $pageTitle = "ConditionsOfUse";
            }
            else if ($this->is_privacy_policy($page))
            {
                $pageTitle = "PrivacyPolicy";
            }
            else if ($this->is_shipping($page))
            {
                $pageTitle = "Shipping";
            }
            else if ($this->is_faq($page))
            {
                $pageTitle = "Faq";
            }
            else if ($this->is_newsletter_thank_you($page))
            {
                $pageTitle = "NewsletterThankYou";
            }
        }
        else if ($this->is_contact_us_page($page))
        {
            $pageTitle = "ContactUs";
        }
        else if ($this->is_myaccount_page($page))
        {
            $pageTitle = "MyAccount";
        }
        else if ($this->is_login_page($page))
        {
            $pageTitle = "loginMyAccount";
        }
        else if ($this->is_bank_transfer_success_page($page))
        {
            $pageTitle = "BankTransferAcknowledgement";
            $ecommPagetype = "purchase";
        }

        if ($pageTitle == "")
            $pageTitle = $page['class'] . $controller['method'] . (($page['method_parameter1']) ? $page['method_parameter1'] : "");
        if ($ecommPagetype == "")
            $ecommPagetype = $page['class'] . $controller['method'] . (($page['method_parameter1']) ? $page['method_parameter1'] : "");

        return array("title" => $pageTitle, "ecom_title" => $ecommPagetype);
    }

    private function _put_transaction_product_object($pageInfo = array(), $var = array())
    {
        $google_product_obj = "";

        foreach($var["soi"] as $product)
        {
            if ($google_product_obj != "")
                $google_product_obj .= ",";

            $google_product_obj .= '{' .
              '"name": "'         . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->get_name()) . '",' .
              '"sku": "'          . $product->get_prod_sku() . '",' .
              '"category": "'     . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->get_cat_name()) . '",' .
              '"price": '         . $product->get_unit_price() . ',' .
              '"quantity": '      . $product->get_qty() . ',' .
              '"availability": "' . $product->get_website_status() . '"' .
            '}';
        }
        return $google_product_obj;
    }

    private function _put_ecomm_products_info($pageInfo = array(), $var = array())
    {
        $skus = "";
        if ($var["products"])
            $product_list = $var["products"];
        else
            $product_list = $var["soi"];

        if ($var["products"] || $var["soi"])
        {
            foreach($product_list as $product)
            {
                if ($skus != "")
                    $skus .= ",";
                if (is_array($product))
                    $skus .= '"' . $product["sku"] . '"';
                else
                    $skus .= '"' . $product->get_prod_sku() . '"';
            }
            $additionDataLayer .= '
                                ,"ecomm_prodid": [' . $skus . ']';
            $additionDataLayer .= '
                                ,"ecomm_totalvalue": "' . $var['total_amount'] . '"';
        }
        return $additionDataLayer;
    }

    private function _put_ecomm_page_type($pageInfo = array(), $var = array())
    {
        $additionDataLayer = '
                            ,"ecomm_pagetype": "' . $pageInfo['ecom_title'] . '"';
        return $additionDataLayer;
    }

    private function _put_common_remarketing($pageInfo = array(), $var = array())
    {
        $additionDataLayer = '
                            ,"pagetype": "' . $pageInfo['ecom_title'] . '"';
        return $additionDataLayer;
    }
}
