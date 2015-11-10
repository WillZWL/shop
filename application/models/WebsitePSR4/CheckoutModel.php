<?php
namespace ESG\Panther\Models\Website;
use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\CountryStateService;
use ESG\Panther\Service\PaymentOptionService;
use ESG\Panther\Service\SoFactoryService;
use ESG\Panther\Service\CartSessionService;
use ESG\Panther\Service\PaymentGatewayRedirectService;
use ESG\Panther\Service\PaymentGatewayRedirectPaypalService;
use ESG\Panther\Service\PaymentGatewayRedirectMoneybookersService;
use ESG\Panther\Form\GeneralInputFilter;
use ESG\Panther\Service\ClientService;

class CheckoutModel extends \CI_Model
{
    const BILLING_COUNTRY = "billing";
    const SHIPPING_COUNTRY = "shipping";

    private $_countryService;
    private $_stateService;
    private $_paymentOptionService;
    private $_soFactoryService;
    private $_cartSessionService;

    public $poBoxAmount = ["GBP" => 100
                            , "AUD" => 100
                            , "NZD" => 100
                            , "EUR" => 100
                            , "PLN" => 100];

    public function __construct() {
        parent::__construct();
        $this->setCountryService(new CountryService());
        $this->setCountryStateService(new CountryStateService());
        $this->setPaymentOptionService(new PaymentOptionService());
        $this->setSoFactoryService(new SoFactoryService());
        $this->setCartSessionService(new CartSessionService());
    }

    public function getPoBoxAmountLimit($inJson = false) {
        if ($inJson)
            return json_encode($this->poBoxAmount);
        else
            return $this->poBoxAmount;
    }

    public function isLoggedIn() {
        if (isset($_SESSION["client"])) {
            if ($_SESSION["client"]["loggedIn"] == 1)
                return $_SESSION["client"];
        }
        return false;
    }

    public function clientLogin($email, $password) {
        $filter = new GeneralInputFilter();
        $clientService = new ClientService();
        if ($filter->isValidEmail($email) && ($password != "")) {
            $loginResult = $clientService->login($email, $password);
            if ($loginResult) {
               return $_SESSION["client"];
            }
        }
        return false;
    }

    public function createSaleOrder($formValue) {
        $result = ["error" => -10, "errorMessage" => _("Please contact CS") . ", err:" . __LINE__];
        $cart = $this->getCartSessionService()->getCart();
//        var_dump($cart);
        if ($cart)
        {
            $soObj = $this->getSoFactoryService()->createSaleOrder($formValue, $cart);
            if ($soObj)
            {
                $paymentGatewayId = $formValue["paymentGatewayId"];
                $gatewayRedirectService = $this->_createPaymentGatewayRedirectService($paymentGatewayId, $soObj, ((isset($formValue["debug"]))?$formValue["debug"]:0));
                if ($gatewayRedirectService)
                    return $gatewayRedirectService->checkout($formValue);
                else
                {
                    error_log("Payment Gateway Service-" . $paymentGatewayId . " not found " . __METHOD__ . __LINE__);
                }
                $result = ["error" => -11, "errorMessage" => _("Please contact CS") . ", err:" . __LINE__];
            }
            else
                $result = ["error" => -12, "errorMessage" => _("Please contact CS") . ", err:" . __LINE__];
        }
        else
        {
            $result = ["error" => -13, "errorMessage" => _("Session timeout, please check your cart!" . ", err:" . __LINE__)];
        }
        return $result;
    }

    public function notification($paymentGatewayId, $data, $debug)
    {
        $gatewayRedirectService = $this->_createPaymentGatewayRedirectService($paymentGatewayId, null, $debug);
        if ($gatewayRedirectService) {
            $gatewayRedirectService->notification($data);
        }
        else
        {
            error_log("Payment Gateway Service in notification -" . $paymentGatewayId . " not found " . __METHOD__ . __LINE__);
            return false;
        }
    }

    public function response($paymentGatewayId, $debug = 0)
    {
        $gatewayRedirectService = $this->_createPaymentGatewayRedirectService($paymentGatewayId, null, $debug);
        if ($gatewayRedirectService) {
//prevent to rebuild session after unset when payment success
            CartSessionService::setNoRebuildCart();
            $gatewayRedirectService->processPaymentStatusInGeneral($_POST, $_GET);
        }
        else
        {
            error_log("Payment Gateway Service-" . $paymentGatewayId . " not found " . __METHOD__ . __LINE__);
            return false;
        }
    }

    public function verifyAndGetOrderDetails($paymentResult, $soNo, $option = [])
    {
        $valid = false;
        $soObj = null;
        $soItemDetail = null;
        $soPaymentStatus = null;

        if (intval($soNo) == $soNo) {
            $soObj = $this->_soFactoryService->getDao()->get(["so_no" => $soNo]);
            if ($soObj->getStatus() >= $option["status"]) {
                if (($soObj->getCreateAt() == ip2long($_SERVER["REMOTE_ADDR"]))
                    || (isset($_GET["debug"]) && ($_GET["debug"] == 1))) {
                    if (isset($option["soItemDetail"]))
                        $soItemDetail = $this->_soFactoryService->getDao("SoItemDetail")->getList(["so_no" => $soNo], ["limit" => -1]);
                    if (isset($option["soPaymentStatus"]))
                    {
                        $soPaymentStatus = $this->_soFactoryService->getDao("SoPaymentStatus")->getRecordWithGatewayName(["sops.so_no" => $soNo], ["limit" => 1]);
                    }
                    $valid = true;
                }
            }
        }

        return ["valid" => $valid
                , "so" => $soObj
                , "soItemDetail" => $soItemDetail
                , "soPaymentStatus" => $soPaymentStatus];
    }

    public function getPaymentOption($platformId, $cartAmount) {
        return $this->getPaymentOptionService()->getPaymentOptionByPlatformId($platformId, $cartAmount);
    }

    public function getCheckoutFormCountryList($platformCountryId, $type = self::BILLING_COUNTRY) {
        $data = array();
        $countryExtObj = $this->_countryService->getCountryExtDao()->get(["cid" => $platformCountryId, "lang_id" => LANG_ID]);
        $data[$type]["countryName"] = $countryExtObj->getName();
        $data[$type]["countryId"] = $platformCountryId;
        return $data;
    }

    public function getCheckoutFormStateList($platformCountryId, $type = self::BILLING_COUNTRY) {
        $stateList = $this->getCountryStateService()->getDao()->getList(["country_id" => $platformCountryId, "status" => 1], ["limit" => -1]);
        return $stateList;
    }

    private function _createPaymentGatewayRedirectService($paymentGatewayId, $soObj, $debug = 0) {
        $classname = "ESG\Panther\Service\PaymentGatewayRedirect" . ucfirst(strtolower($paymentGatewayId)) . "Service";
        if (file_exists(APPPATH . "/libraries/ServicePSR4/PaymentGatewayRedirect" . ucfirst(strtolower($paymentGatewayId)) . "Service.php")) {
            $gatewayRedirectService = new $classname($soObj, $debug);
            return $gatewayRedirectService;
        }
        return false;
    }

    public function setCountryService($countryService) {
        $this->_countryService = $countryService;
    }

    public function getCountryService() {
        return $this->_countryService;
    }

    public function setCountryStateService($stateService) {
        $this->_stateService = $stateService;
    }

    public function getCountryStateService() {
        return $this->_stateService;
    }

    public function setPaymentOptionService($paymentOptionService) {
        $this->_paymentOptionService = $paymentOptionService;
    }

    public function getPaymentOptionService() {
        return $this->_paymentOptionService;
    }

    public function setSoFactoryService($soFactoryService) {
        $this->_soFactoryService = $soFactoryService;
    }

    public function getSoFactoryService() {
        return $this->_soFactoryService;
    }

    public function setCartSessionService($cartSessionService) {
        $this->_cartSessionService = $cartSessionService;
    }

    public function getCartSessionService() {
        return $this->_cartSessionService;
    }

}
