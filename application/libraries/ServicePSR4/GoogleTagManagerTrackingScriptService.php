<?php
namespace ESG\Panther\Service;

class GoogleTagManagerTrackingScriptService extends AtomTrackingScriptService
{
    const GTM_CONTAINER_ID = 'GTM-77HZ';
    
    private $loadSiteParameterService;
    private $countryContainerId;
    private $countryId;

    public function __construct()
    {
        parent::__construct();
        $this->loadSiteParameterService=new LoadSiteParameterService();
        $this->setCountryId($this->loadSiteParameterService->initSite()->getPlatformCountryId());
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
        return $this->_formDataLayer($page, $var) . $this->_showCommonScript();
    }

    private function _formDataLayer($page = array(), $var = array())
    {
       
        $pageInfo = $this->_pageTitleMapping($page);
        $dataLayer = "<script>";
        $dataLayer .= 'var google_tag_params = { "countryId": "' . $this->getCountryId() . '"';

        if ($this->isHomePage($page)
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
                       "pageCountryID": "' . $this->getCountryId() . '"';

        if ($this->isHomePage($page))
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
        } else if ($page['class'] == "Cat") {
            $pageTitle = "CategoryPage";
            $ecommPagetype = "category";
        } else if ($page['class'] == "MainProduct") {
            $pageTitle = "ProductPage";
            $ecommPagetype = "product";
        } else if ($page['class'] == "Cart") {
//RA
            $pageTitle = "RA";
        } else if ($page['class'] == "ReviewOrder") {
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
        } else if ($page['class'] == "Display") {
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
                            ,"prodid": ["' . $this->getCountryId() . "-" . $var['sku'] . '"]
                            ,"pagetype": "product"
                            ,"pname": ["' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"]
                            ,"pcat": ["' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category_name']) . '"]
                            ,"pvalue": ["' . $var['price'] . '"]';
                            
        return $additionDataLayer;
    }

    private function _remarketingReviewPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_putCommonRemarketing($pageInfo, $var);
        if ($var['cartInfo']) {

            $i=0;$delimiter=null;
            foreach ( $var["cartInfo"]->items as $cartObj) {
                if($cartObj->getSku() !="") {
                    
                    $i ? $delimiter="," :$delimiter="";
                    $skus .= $delimiter.'"' .$cartObj->getSku() . '"';
                    $price .= $delimiter.$cartObj->getPrice();
                    $name .= $delimiter.'"' . $cartObj->getName() . '"';
                }
                $i++;
            }
            $additionDataLayer .= '
                                ,"prodid": [' . $skus . ']';
            $additionDataLayer .= '
                                ,"pvalue": [' . $price . ']';
            $additionDataLayer .= '
                                ,"pname": [' . $name . ']'; 
          return $additionDataLayer;  
        }
    
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
        if($var["soi"]){

            foreach ($var["soi"] as $product) {
                if ($prodid != "")
                    $prodid .= ",";
                if ($pname != "")
                    $pname .= ",";
                if ($pcat != "")
                    $pcat .= ",";
                if ($pvalue != "")
                    $pvalue .= ",";

                $prodid .= '"' . $this->getCountryId() . "-" . $product->getItemSku() . '"';
                $pname .= '"' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->prod_name) . '"';
                $pcat .= '"' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->getCatName()) . '"';
                $pvalue .= $product->getUnitPrice();
            }
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
                            ,"pageCategory": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['category_name']) . '"
                            ,"pageProductName": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"
                            ,"productSKU": "' . $var['sku'] . '"
                            ,"productName": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $var['product_name']) . '"';
                            
        return $additionDataLayer;
    }

    private function _reviewOrderPage($pageInfo = array(), $var = array())
    {
        $additionDataLayer = $this->_putEcommPageType($pageInfo, $var);
        $additionDataLayer .= $this->_putCommonCartInfo($pageInfo, $var);

        return $additionDataLayer;
    }

    private function _putEcommProductsInfo($pageInfo = array(), $var = array())
    {
        $skus = "";
        if ($var["soi"]) {
            foreach ($var["soi"] as $product) {
                if ($skus != "")
                    $skus .= ",";
             $skus .= '"' . $product->getItemSku() . '"';      
            }

            $additionDataLayer .= '
                                ,"ecomm_prodid": [' . $skus . ']';
            $additionDataLayer .= '
                                ,"ecomm_totalvalue": "' . $var['total_amount'] . '"';
        }

        return $additionDataLayer;
    }


    private function _putCommonCartInfo($pageInfo = array(), $var = array()){

         if ($var['cartInfo']) {
            $i=0;
            foreach ( $var["cartInfo"]->items as $cartObj) {
              
                if($cartObj->getSku() !="") {

                    $i ? $delimiter="," :$delimiter="";
                    $skus .= $delimiter.'"' .$cartObj->getSku() . '"';
                }

                $i++;
            }

            $total_amount=$var['cartInfo']->getGrandTotal();
            $additionDataLayer .= '
                                ,"ecomm_prodid": [' . $skus . ']';
            $additionDataLayer .= '
                                ,"ecomm_totalvalue": "' . $total_amount . '"';
          return $additionDataLayer;  
        }
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
           if($var['so']){
            $additionDataLayer .= $this->_putEcommProductsInfo($pageInfo, $var);
            $additionDataLayer .= '
                                ,"conversionDate": "' . $var['so']->getOrderCreateDate() . '"
                                ,"conversionValue": ' . $var['total_amount'] . '
                                ,"clientName": "' . $var['so']->getBillName() . '"
                                ,"clientEmail": "' . $var['client_email'] . '"';

            $additionDataLayer .= '
                                ,"transactionId": "' . $var['so']->getSoNo() . '"
                                ,"transactionDate": "' . $var['so']->getOrderCreateDate() . '"
                                ,"transactionType": "' . $var['so']->getBizType() . '"
                                ,"transactionAffiliation": "' . $var['affiliate_name'] . '"
                                ,"transactionTotal": ' . $var['total_amount'] . '
                                ,"transactionShipping": ' . $var['so']->getDeliveryCharge() . '
                                ,"transactionPaymentType": "' . $var['sops']->getPaymentGatewayId(). '"
                                ,"transactionCurrency": "' . $var['so']->getCurrencyId() . '"
                                ,"transactionPromoCode": "' . $var['so']->getPromotionCode() . '"
                                ,"transactionProducts": [' . $this->_putTransactionProductObject($pageInfo, $var) . ']';
            }
        }

        
        return $additionDataLayer;
    }

    private function _putTransactionProductObject($pageInfo = array(), $var = array())
    {
        $google_product_obj = "";
 //$product->get_cat_name()
        foreach ($var["soi"] as $product) {
            if ($google_product_obj != "")
                $google_product_obj .= ",";

            $google_product_obj .= '{' .
                '"name": "' . str_replace(array('\\', '"'), array('\\\\', '\"'), $product->prod_name) . '",' .
                '"sku": "' . $product->getItemSku() . '",' .
                '"category": "' . str_replace(array('\\', '"'), array('\\\\', '\"'),$aaa) . '",' .
                '"price": ' . $product->getUnitPRice() . ',' .
                '"quantity": ' . $product->getQty() . ',' .
                '"availability": "' . $product->getWebsiteStatus() . '"' .
                '}';
        }
        return $google_product_obj;
    }

    private function _showCommonScript()
    {
        $containerId = self::GTM_CONTAINER_ID;
        $countryContainerId="";
        $countryContainerArr=array(

                "BE" => "GTM-MPJWJQ",
                'AU' => "GTM-NNL2JB",
                'NZ' => "GTM-K3GW2F",
                'ES' => "GTM-TC6F2D",
                'PL' => "GTM-TXWWKC",
                'FR' => "GTM-MQ9RSX",
                'GB' => "GTM-MHPP6T",
                'IT' => "GTM-T33Z3B",
                'NL' => "GTM-M7RGBN",            
            );
        $countryId=strtoupper($this->getCountryId());

        if (array_key_exists($countryId,$countryContainerArr)){

            $countryContainerId= $countryContainerArr[$countryId];
        }

        if($countryContainerId)
        {
        $scripts = <<<javascript
<!-- Google Tag Manager -->
    <noscript>
        <iframe src="//www.googletagmanager.com/ns.html?id={$countryContainerId}" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{$countryContainerId}');
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

    public function setCountryContainerId($value){
         
         $this->countryContainerId=$value;
    }

    public function getCountryContainerId(){

        return $this->countryContainerId;
    }

    public function setCountryId($value){
         
         $this->countryId=$value;
    }

    public function getCountryId(){

        return $this->countryId;
    }
}
