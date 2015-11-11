<?php
namespace ESG\Panther\Service;

class GoogleTagManagerTrackingScriptService extends AtomTrackingScriptService
{
    const GTM_CONTAINER_ID = 'GTM-77HZ';


    public function __construct()
    {
        parent::__construct();
    }

    public function isRegisteredPage($page)
    {
        $result = false;

        $current_page_info = $page;

        if ($this->isHomePage($page)) {
            $result = true;
        } else if ($this->isCatPage($page)) {
            $result = true;
        } else if ($this->isMainproductPage($page)) {
            $result = true;
        } else if ($this->isRaPage($page)) {
            $result = true;
        } else if ($this->isReviewPage($page)) {
            $result = true;
        } else if ($this->isCheckoutPage($page)) {
            $result = true;
        } else if ($this->isPaymentResultPage($page)) {
            $result = true;
        }

        return $result;
    }

    public function needToShowGenericTrackingPage()
    {
        return true;
    }

    public function getSpecificCode($page = array(), $var = array())
    {
        $script = "";

        return $this->_formDataLayer($page, $var) . $this->_show_common_script();
    }

    private function _formDataLayer($page = array(), $var = array())
    {
        $pageInfo = $this->_pageTitleMapping($page);
        $dataLayer = "<script>";
        $dataLayer .= 'var google_tag_params = { "countryId": "' . PLATFORMCOUNTRYID . '"';

        if ($this->is_home_page($page)
            || $this->isCatPage($page)
            || $this->isCheckoutPage($page)
            || $this->isPaymentFailurePage($page)
        ) {
            $dataLayer .= $this->_putCommonRemarketing($pageInfo, $var);
        } else if ($this->isMainproductPage($page)) {
            $dataLayer .= $this->_remarketingMainproductPage($pageInfo, $var);
        } else if ($this->isReviewPage($page)) {
            $dataLayer .= $this->_remarketingReviewPage($pageInfo, $var);
        } else if ($this->isPaymentSuccessPage($page)) {
            $dataLayer .= $this->_remarketingPaymentResult(1, $pageInfo, $var);
        } else if ($this->isBankTransferSuccessPage($page)) {
            $dataLayer .= $this->_bankTransferSuccessPage($pageInfo, $var);
        }
        $dataLayer .= " }
                        </script>";

        $dataLayer .= "<script>
                        dataLayer = [{";
        $dataLayer .= '"google_tag_params": window.google_tag_params,';
        $dataLayer .= '"pageTitle": "' . $pageInfo['title'] . '",
                        "affiliateID": "' . $_COOKIE['af'] . '",
                       "pageCountryID": "' . PLATFORMCOUNTRYID . '"';

        if ($this->is_home_page($page))
            $dataLayer .= $this->_homePage($pageInfo, $var);
        else if ($this->isCatPage($page))
            $dataLayer .= $this->_categoryPage($pageInfo, $var);
        else if ($this->isMainproductPage($page))
            $dataLayer .= $this->_mainproductPage($pageInfo, $var);
        else if ($this->isReviewPage($page))
            $dataLayer .= $this->_reviewOrderPage($pageInfo, $var);
        else if ($this->isCheckoutPage($page)) {
            $dataLayer .= $this->_checkoutPage($pageInfo, $var);
        } else if ($this->isPaymentSuccessPage($page)) {
            //hard code 1, so this will include order confirmation page for PH
            $dataLayer .= $this->_paymentResult(1, $pageInfo, $var);
        } else if ($this->isPaymentFailurePage($page)) {
            $dataLayer .= $this->_paymentResult($page['method_parameter1'], $pageInfo, $var);
        }

        $dataLayer .= '}];
                        </script>
                        ';
        return $dataLayer;
    }

    private function _pageTitleMapping($page = array())
    {
        $pageTitle = "";
        $ecommPagetype = "";

        if ($page['class'] == "redirect_controller") {
            $pageTitle = "HomePage";
            $ecommPagetype = "home";
        } else if ($page['class'] == "cat") {
            $pageTitle = "CategoryPage";
            $ecommPagetype = "category";
        } else if ($page['class'] == "mainproduct") {
            $pageTitle = "ProductPage";
            $ecommPagetype = "product";
        } else if ($page['class'] == "cart") {
//RA
            $pageTitle = "RA";
        } else if ($page['class'] == "review_order") {
            $pageTitle = "Review";
            $ecommPagetype = "cart";
        } else if ($this->isCheckoutPage($page)) {
            $pageTitle = "Checkout";
            $ecommPagetype = "siteview";
        } else if ($this->isPaymentSuccessPage($page)) {
//payment success
            $pageTitle = "PaymentSuccess";
            $ecommPagetype = "purchase";
        } else if ($this->isPaymentFailurePage($page)) {
            $pageTitle = "PaymentFailed";
            $ecommPagetype = "siteview";
        } else if ($page['class'] == "display") {
            if ($this->isAboutUs($page)) {
                $pageTitle = "AboutUs";
            } else if ($this->isConditionsOfUse($page)) {
                $pageTitle = "ConditionsOfUse";
            } else if ($this->isPrivacyPolicy($page)) {
                $pageTitle = "PrivacyPolicy";
            } else if ($this->isShipping($page)) {
                $pageTitle = "Shipping";
            } else if ($this->isFaq($page)) {
                $pageTitle = "Faq";
            } else if ($this->isNewsletterThankYou($page)) {
                $pageTitle = "NewsletterThankYou";
            }
        } else if ($this->isContactUsPage($page)) {
            $pageTitle = "ContactUs";
        } else if ($this->isMyaccountPage($page)) {
            $pageTitle = "MyAccount";
        } else if ($this->isLoginPage($page)) {
            $pageTitle = "loginMyAccount";
        } else if ($this->isBankTransferSuccessPage($page)) {
            $pageTitle = "BankTransferAcknowledgement";
            $ecommPagetype = "purchase";
        }

        if ($pageTitle == "")
            $pageTitle = $page['class'] . $controller['method'] . (($page['method_parameter1']) ? $page['method_parameter1'] : "");
        if ($ecommPagetype == "")
            $ecommPagetype = $page['class'] . $controller['method'] . (($page['method_parameter1']) ? $page['method_parameter1'] : "");

        return array("title" => $pageTitle, "ecom_title" => $ecommPagetype);
    }

    private function _putCommonRemarketing($pageInfo = array(), $var = array())
    {
        $additionDataLayer = '
                            ,"pagetype": "' . $pageInfo['ecom_title'] . '"';
        return $additionDataLayer;
    }

    private function _remarketingMainproductPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer .= '
                            ,"prodid": ["' . PLATFORMCOUNTRYID . "-" . $var['sku'] . '"]
                            ,"pagetype": "product"
                            ,"pname": ["' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"]
                            ,"pcat": ["' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category']) . '"]
                            ,"pvalue": ["' . $var['price'] . '"]';
        return $additionDataLayer;
    }

    private function _remarketingReviewPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_putCommonRemarketing($pageInfo, $var);

        if ($var["products"]) {
            $skus = "";
            $price = "";
            foreach ($var["products"] as $product) {
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

    private function _remarketingPaymentResult($pass_or_fail, $pageInfo = array(), $var = array())
    {
        if ($pass_or_fail == 1) {
            $prodid = "";
            $pname = "";
            $pcat = "";
            $pvalue = "";
            $additionDataLayer .= '
                                ,"pagetype": "purchase"';

            foreach ($var["soi"] as $product) {
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

    private function _bankTransferSuccessPage($pageInfo = array(), $var = array())
    {
        return "";
    }

    private function _homePage($pageInfo = array(), $var = array())
    {
        return $this->_putEcommPageType($pageInfo, $var);
    }

    private function _putEcommPageType($pageInfo = array(), $var = array())
    {
        $additionDataLayer = '
                            ,"ecomm_pagetype": "' . $pageInfo['ecom_title'] . '"';
        return $additionDataLayer;
    }

    private function _categoryPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer = '
                            ,"ecomm_pagetype": "' . $pageInfo['ecom_title'] . '"';
        $additionDataLayer .= '
                            ,"pageCategory": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category_name']) . '"
                            ,"pageValue": ' . $var['category_id'];
        return $additionDataLayer;
    }

    private function _mainproductPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_putEcommPageType($pageInfo, $var);
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

    private function _reviewOrderPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_putEcommPageType($pageInfo, $var);
        $additionDataLayer .= $this->_putEcommProductsInfo($pageInfo, $var);

        return $additionDataLayer;
    }

    private function _putEcommProductsInfo($pageInfo = array(), $var = array())
    {
        $skus = "";
        if ($var["products"])
            $product_list = $var["products"];
        else
            $product_list = $var["soi"];

        if ($var["products"] || $var["soi"]) {
            foreach ($product_list as $product) {
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

    private function _checkoutPage($pageInfo = array(), $var = array())
    {
        return $this->_putEcommPageType($pageInfo, $var);
    }

    private function _paymentResult($pass_or_fail, $pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_putEcommPageType($pageInfo, $var);
        if ($pass_or_fail == 1) {
//success
            $additionDataLayer .= $this->_putEcommProductsInfo($pageInfo, $var);
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

    private function _putTransactionProductObject($pageInfo = array(), $var = array())
    {
        $google_product_obj = "";

        foreach ($var["soi"] as $product) {
            if ($google_product_obj != "")
                $google_product_obj .= ",";

            $google_product_obj .= '{' .
                '"name": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->get_name()) . '",' .
                '"sku": "' . $product->get_prod_sku() . '",' .
                '"category": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->get_cat_name()) . '",' .
                '"price": ' . $product->get_unit_price() . ',' .
                '"quantity": ' . $product->get_qty() . ',' .
                '"availability": "' . $product->get_website_status() . '"' .
                '}';
        }
        return $google_product_obj;
    }

    private function _showCommonScript()
    {
        $containerId = self::GTM_CONTAINER_ID;
        switch (PLATFORMCOUNTRYID)
        {
            case 'BE':
                $country_container_id = "GTM-MPJWJQ";
                break;

            case 'AU':
                $country_container_id = "GTM-NNL2JB";
                break;

            case 'NZ':
                $country_container_id = "GTM-5TFMRD";
                break;

            case 'ES':
                $country_container_id = "GTM-TC6F2D";
                break;

            case 'PL':
                $country_container_id = "GTM-TXWWKC";
                break;

            case 'FR':
                $country_container_id = "GTM-MQ9RSX";
                break;

            case 'GB':
                $country_container_id = "GTM-MHPP6T";
                break;

            case 'IT':
                $country_container_id = "GTM-T33Z3B";
                break;

            default:
                $country_container_id = "";
                break;
        }

        if($country_container_id)
        {
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
}
        return $scripts;
    }

    public function getAllPageCode($page = array(), $var = array())
    {
        return $this->_formDataLayer($page, $var) . $this->_showCommonScript();
    }
}
