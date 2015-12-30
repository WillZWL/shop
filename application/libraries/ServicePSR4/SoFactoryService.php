<?php
namespace ESG\Panther\Service;

//use PHPMailer;
//use EventEmailDto;
//use ESG\Panther\Service\EventService;
//use ESG\Panther\Service\EntityService;
//use ESG\Panther\Service\DelayedOrderService;
//use ESG\Panther\Service\ReviewFianetService;
//use ESG\Panther\Service\PdfRenderingService;
//use ESG\Panther\Service\CurrencyService;
//use ESG\Panther\Service\TemplateService;
//use ESG\Panther\Service\SubjectDomainService;
//use ESG\Panther\Service\DataExchangeService;
//use ESG\Panther\Service\ComplementaryAccService;
use ESG\Panther\Dao\SoDao;
use ESG\Panther\Dao\SoItemDao;
use ESG\Panther\Dao\SoItemDetailDao;
use ESG\Panther\Dao\SoPaymentStatusDao;
use ESG\Panther\Dao\SoExtendDao;
use ESG\Panther\Dao\SoCreditChkDao;
use ESG\Panther\Dao\SoRiskDao;
use ESG\Panther\Service\CartSessionService;
//use ESG\Panther\Service\ProductService;
//use ESG\Panther\Service\CreateClientInterface;
//use ESG\Panther\Service\ExchangeRateService;
//use ESG\Panther\Service\PriceWebsiteService;
//use ESG\Panther\Service\PriceService;

class SoFactoryService extends BaseService
{
//    public $injectObj = null;
    private $_platformType = null;

    public function __construct($injectObj = null) {
        parent::__construct();
//        $this->injectObj = $injectObj;
        $this->setDao(new SoDao());
    }

    public function getNewCartByOrderInfo($orderInfo) {
        $skuList = [];
        foreach($orderInfo->items as $sku => $item) {
            $skuList[$sku] = ["qty" => $item->getQty(), "amount" => $item->getAmount()];
        }
//Centralize a buildcart function, to get all the cart details
        $newCart = $this->rebuildCartBySku($orderInfo->getPlatformId(), $orderInfo->getPlatformCurrency(), $orderInfo->getBizType(), $skuList);
        $newCart->setPlatformOrderId($orderInfo->getPlatformOrderId());
        $newCart->setPlatformId($orderInfo->getPlatformId());

        return $newCart;
    }

    public function createSaleOrder(CreateSoInterface $interfaceType) {
        if ($interfaceType instanceof CreateSoInterface) {
//create client
            $clientObj = $this->_createClient($interfaceType);
            if ($clientObj) {
//rebuild the cart info, prevent hacking or price updated by BD
                if ($interfaceType->getBizType() == "ONLINE")
                    $newCart = $this->getNewCartByOrderInfo($interfaceType->getCartDto());
                else
                    $newCart = $interfaceType->getCartDto();
                $newSoNo = $this->getDao("So")->getNewSoNo();
                $this->getDao("So")->db->trans_start();
                $soObj = $this->_createSo($clientObj, $newCart, $newSoNo, null, $interfaceType);
                if ($soObj !== false) {
                    if ($this->_createSoItemDetailAndUpdateSo($soObj, $newCart)) {
                        if ($this->_createSoPaymentStatus($soObj, $interfaceType->getCheckoutData())) {
                            if (!$this->_createSoExtend($soObj, $interfaceType->getCheckoutData())) {
                                $this->getDao("So")->db->trans_rollback();
                            }
                        } else {
                            $this->getDao("So")->db->trans_rollback();
                        }
                    } else {
                        $this->getDao("So")->db->trans_rollback();
                    }
                }
                $transCompleteResult = $this->getDao("So")->db->trans_complete();
                if ($interfaceType instanceof CreateSoEventInterface) {
                    $interfaceType->soInsertSuccessEvent($soObj);
                }
                if ($transCompleteResult === false)
                {
                    $subject = "[Panther] Rollback Create so:(" . (($soObj) ? $soObj->getSoNo():"") . ") " . __METHOD__ . __LINE__;
                    $message = $this->getDao("So")->db->last_query() . "," . $this->getDao("So")->db->error()["message"];
                    $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
                    return false;
                }
            } else {
//no need to send email alert, as Client Service handle it already
            }
        }
        return $soObj;
    }
/*
    public function storeAfInfo($soExtObj, $af = NULL)
    {
        if (is_null($af)) {
            $af_data = $this->get_af_srv()->get_af_record();
        } else {
            $af_data = is_array($af) ? $af : array("af" => $af);
        }

        if ($af_data) {
            $soExtendObj->setConvSiteId($af_data["af"]);
            if (is_array($af_data) && !is_null($af_data["af_ref"])) {
                $soExtendObj->setConvSiteRef($af_data["af_ref"]);
            }
        }
    }
*/
    private function _createSoExtend($soObj, $checkoutInfo)
    {
        $soExtendObj = $this->getDao("SoExtend")->get();
        $soExtendObj->setSoNo($soObj->getSoNo());
        if ($checkoutInfo->getConvSiteId())
        {
//            $this->storeAfInfo($soExtendObj, $checkoutInfo["convSiteId"]);
            $soExtendObj->setConvSiteId($checkoutInfo->getConvSiteId());
        }
        if ($checkoutInfo->getConvSiteRef())
            $soExtendObj->setConvSiteRef($checkoutInfo->getConvSiteRef());
        if ($checkoutInfo->getOrderReason())
            $soExtendObj->setOrderReason($checkoutInfo->getOrderReason());
        if ($checkoutInfo->getOrderNotes())
            $soExtendObj->setNotes($checkoutInfo->getOrderNotes());

        $insertSoExtendResult = $this->getDao("SoExtend")->insert($soExtendObj);
        if ($insertSoExtendResult === false) {
            $subject = "[Panther] Cannot create so extend:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("SoExtend")->db->last_query() . "," . $this->getDao("SoExtend")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $insertSoPaymentResult;
    }

    private function _createSoPaymentStatus($soObj, $checkoutInfo)
    {
        $soPaymentStatusObj = $this->getDao("SoPaymentStatus")->get();
        $soPaymentStatusObj->setSoNo($soObj->getSoNo());
        $soPaymentStatusObj->setPaymentGatewayId($checkoutInfo->getPaymentGatewayId());
        $soPaymentStatusObj->setCardId($checkoutInfo->getPaymentCardId());
        $soPaymentStatusObj->setPaymentStatus("N");

        $insertSoPaymentResult = $this->getDao("SoPaymentStatus")->insert($soPaymentStatusObj);
        if ($insertSoPaymentResult === false) {
            $subject = "[Panther] Cannot create so payment status:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("SoPaymentStatus")->db->last_query() . "," . $this->getDao("SoPaymentStatus")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $insertSoPaymentResult;
    }

    private function _createClient($interfaceType) {
        if (!($clientObj = $interfaceType->selfCreateClientObj()))
            $clientObj = $this->getService("Client")->createClient($interfaceType, true);
        return $clientObj;
    }

    private function _setSoObjClientInfo($clientObj, $soObj) {
        $soObj->setClientId($clientObj->getId());
        $billName = $clientObj->getForename();
        if ($billName) {
            $billName .= " " . $clientObj->getSurname();
        } else {
            $billName = $clientObj->getSurname();
        }
        if ($billName)
            $soObj->setBillName($billName);
        if (!is_null($clientObj->getCompanyname()))
            $soObj->setBillCompany($clientObj->getCompanyname());
        $billAddress = $clientObj->getAddress1();
        if ($billAddress) {
            $billAddress .= "||" . ($clientObj->getAddress2()?$clientObj->getAddress2():"");
        } else {
            $billAddress = ($clientObj->getAddress2()?$clientObj->getAddress2():"");
        }
        if ($billAddress) {
            $billAddress .= "||" . ($clientObj->getAddress3()?$clientObj->getAddress3():"");
        } else {
            $billAddress = ($clientObj->getAddress3()?$clientObj->getAddress3():"");
        }
        $soObj->setBillAddress($billAddress);
        if (!is_null($clientObj->getPostcode()))
            $soObj->setBillPostcode($clientObj->getPostcode());
        if (!is_null($clientObj->getCity()))
            $soObj->setBillCity($clientObj->getCity());
        if (!is_null($clientObj->getState()))
            $soObj->setBillState($clientObj->getState());
        $soObj->setBillCountryId($clientObj->getCountryId());

        if (!is_null($clientObj->getDelName()))
            $soObj->setDeliveryName($clientObj->getDelName());
        if (!is_null($clientObj->getDelCompany()))
            $soObj->setDeliveryCompany($clientObj->getDelCompany());
        $deliveryAddress = $clientObj->getDelAddress1();
        if ($deliveryAddress) {
            $deliveryAddress .= "||" . ($clientObj->getDelAddress2()?$clientObj->getDelAddress2():"");
        } else {
            $deliveryAddress = ($clientObj->getDelAddress2()?$clientObj->getDelAddress2():"");
        }
        if ($deliveryAddress) {
            $deliveryAddress .= "||" . ($clientObj->getDelAddress3()?$clientObj->getDelAddress3():"");
        } else {
            $deliveryAddress = ($clientObj->getDelAddress3()?$clientObj->getDelAddress3():"");
        }
        $soObj->setDeliveryAddress($deliveryAddress);
        if (!is_null($clientObj->getDelPostcode()))
            $soObj->setDeliveryPostcode($clientObj->getDelPostcode());
        if (!is_null($clientObj->getDelCity()))
            $soObj->setDeliveryCity($clientObj->getDelCity());
        if (!is_null($clientObj->getDelState()))
            $soObj->setDeliveryState($clientObj->getDelState());
        if (!is_null($clientObj->getDelCountryId()))
            $soObj->setDeliveryCountryId($clientObj->getDelCountryId());
    }

    private function _createSoItemDetailAndUpdateSo($soObj, $cart) {
        $result = true;
        $totalCost = 0;
        $i = 1;
        foreach($cart->items as $sku => $item) {
            $soItemDetailObj = $this->_createSingleSoItemDetail($soObj, $item, $i, $cart->getPlatformId());
            if (!$soItemDetailObj)
                $result = false;
            else
            {
                $totalCost += $soItemDetailObj->getCost() * $soItemDetailObj->getQty();
            }
            $i++;
        }

        $soObj->setCost($totalCost);
        $updateSoResult = $this->getDao("So")->update($soObj);
        if ($updateSoResult === false)
        {
            $subject = "[Panther] Cannot update so cost:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("So")->db->last_query() . "," . $this->getDao("So")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_GENERAL_LEVEL);
        }
        return $result;
    }

    private function _createSoItem($soObj, $cart) {
        $result = true;
        $i = 1;
        foreach($cart->items as $sku => $item) {
            $soItemObj = $this->_createSingleSoItem($soObj, $item, $i);
            if (!$soItemObj)
                $result = false;
            $i++;
        }
        return $result;
    }

    private function _createSingleSoItemDetail($soObj, $item, $lineNo, $platformId) {
        $soItemDetailObj = $this->getDao("SoItemDetail")->get();
        $soItemDetailObj->setSoNo($soObj->getSoNo());
        $soItemDetailObj->setLineNo($lineNo);
        $soItemDetailObj->setItemSku($item->getSku());
        $soItemDetailObj->setProdName($item->getNameInLang());

        if ($item->getExtItemCd())
            $soItemDetailObj->setExtItemCd($item->setExtItemCd());
        $soItemDetailObj->setQty($item->getQty());
        $soItemDetailObj->setOutstandingQty($item->getQty());
        $soItemDetailObj->setUnitPrice($item->getPrice());
        $soItemDetailObj->setStatus(0);

        $soItemDetailObj->setWebsiteStatus($item->getWebsiteStatus());
        $soItemDetailObj->setSupplierStatus($item->getSourcingStatus());
        $soItemDetailObj->setWarrantyInMonth($item->getWarrantyInMonth());

//        $soItemDetailObj->setCost($item->getUnitCost() * $item->getQty());
        $this->_setProfitInfo($soItemDetailObj, $platformId, $item->getDecPlace());

        $soItemDetailObj->setItemUnitCost($item->getSupplierUnitCostInHkd());

//promo code, bundle, bundle may be implemented different from VB
        $soItemDetailObj->setBundleCoreId(0);
        $soItemDetailObj->setBundleLevel(0);
        if ($item->getDiscountTotal())
            $soItemDetailObj->setDiscountTotal($item->getDiscountTotal());
        if ($item->getPromoDiscAmt())
            $soItemDetailObj->setPromoDiscAmt($item->getPromoDiscAmt());

//better to use a function to calculate VAT, GST in the future
        $soItemDetailObj->setVatTotal(round(($item->getAmount() * $item->getVatPercent() / 100), $item->getDecPlace()));
//        $soItemDetailObj->setGstTotal(round(($item->getAmount() * $item->getVatPercent() / 100), $item->getDecPlace()));
        $soItemDetailObj->setAmount($item->getAmount());

        $insertSoItemDetailResult = $this->getDao("SoItemDetail")->insert($soItemDetailObj);
        if ($insertSoItemDetailResult === false)
        {
            $subject = "[Panther] Cannot create so item detail:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("SoItemDetail")->db->last_query() . "," . $this->getDao("SoItemDetail")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $soItemDetailObj;
    }

    private function _createSingleSoItem($soObj, $item, $lineNo) {
        $soItemObj = $this->getDao("SoItem")->get();
        $soItemObj->setSoNo($soObj->getSoNo());
        $soItemObj->setLineNo($lineNo);
        $soItemObj->setProdSku($item->getSku());
        $soItemObj->setProdName($item->getNameInLang());
        if ($item->getExtItemCd())
            $soItemObj->setExtItemCd($item->setExtItemCd());
        $soItemObj->setQty($item->getQty());
        $soItemObj->setUnitPrice($item->getPrice());
        $soItemObj->setAmount($item->getAmount());
        $soItemObj->setStatus(0);

//better to use a function to calculate VAT, GST in the future
        $soItemObj->setVatTotal(round(($item->getAmount() * $item->getVatPercent() / 100), $item->getDecPlace()));
//        $soItemObj->setGstTotal(round(($item->getAmount() * $item->getVatPercent() / 100), $item->getDecPlace()));

        $soItemObj->setWebsiteStatus($item->getWebsiteStatus());
//        $soItemObj->setSourcingStatus($item->getSourcingStatus());
        $soItemObj->setWarrantyInMonth($item->getWarrantyInMonth());

        $insertSoItemResult = $this->getDao("SoItem")->insert($soItemObj);
        if ($insertSoItemResult === false)
        {
            $subject = "[Panther] Cannot create so item:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("SoItem")->db->last_query() . "," . $this->getDao("SoItem")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $soItemObj;
    }

    private function _createSo($clientObj, $orderInfo, $newSoNo, $parentSoNo = null, $injectedInterfaceObj) {
        $soObj = $this->getDao("So")->get();
        $checkoutInfoDto = $injectedInterfaceObj->getCheckoutData();
        $this->_setSoObjClientInfo($clientObj, $soObj);
        $soObj->setSoNo($newSoNo);
        if ($orderInfo->getPlatformOrderId())
            $soObj->setPlatformOrderId($orderInfo->getPlatformOrderId());
        else
            $soObj->setPlatformOrderId($newSoNo);
        $soObj->setPlatformId($orderInfo->getPlatformId());
//this is itemCost only, not all the cost from price service
        $soObj->setCost($orderInfo->getCost());
        $soObj->setAmount($orderInfo->getGrandTotal());
        $soObj->setCurrencyId($orderInfo->getPlatformCurrency());
        $soObj->setBizType($injectedInterfaceObj->getBizType());
        $soObj->setDeliveryCharge($orderInfo->getDeliveryCharge());
        $soObj->setDeliveryTypeId($orderInfo->getDeliveryType());
        $soObj->setWeight($orderInfo->getTotalWeight());
        if ($checkoutInfoDto->getLangId())
            $soObj->setLangId($checkoutInfoDto->getLangId());
        elseif ($injectedInterfaceObj->getBizType() == "ONLINE")
            $soObj->setLangId(LANG_ID);
        else {
//other are all through admincentre
            $soObj->setLangId("en");
        }
        if ($checkoutInfoDto->getParentSoNo())
            $soObj->setParentSoNo($checkoutInfoDto->getParentSoNo());
        $soObj->setStatus(1);
        $soObj->setHoldStatus(0);
        $soObj->setRefundStatus(0);
        $soObj->setCsCustomerQuery(0);
        if ($orderInfo->getOrderCreateDate())
            $soObj->setOrderCreateDate($orderInfo->getOrderCreateDate());
        else
            $soObj->setOrderCreateDate(date("Y-m-d H:i:s"));
        if ($checkoutInfoDto->getCybersourceFingerprint())
            $soObj->setFingerprintId($checkoutInfoDto->getCybersourceFingerprint());

        $this->setOrderInfoDetail($orderInfo->getPlatformId(), $soObj);
        if ($interfaceType instanceof CreateSoEventInterface) {
            $interfaceType->soBeforeInsertEvent($soObj);
        }
        $insertSoResult = $this->getDao("So")->insert($soObj);
        if ($insertSoResult === false)
        {
            $subject = "[Panther] Cannot create so:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("So")->db->last_query() . "," . $this->getDao("So")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        else
            return $soObj;
    }
/**************************************************
**  pass CartDto into
**  you can form this DTO and pass the
***************************************************/
    public function setOrderInfoDetail($platformId, $soObj) {
//rate, ref_1, expect_delivery_date
        list($usdArr) = $this->getService("ExchangeRate")->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "USD");
        list($eurArr) = $this->getService("ExchangeRate")->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "EUR");

        if (isset($usdArr["rate"]))
            $soObj->setRate($usdArr["rate"]);
        if (isset($eurArr["rate"]))
            $soObj->setRef1($eurArr["rate"]);
    }

/********************************************************
**  function to prevent cart was amended by user and in case the price is updating by BD before checkout
**  rebuildCart to find all the required value for an order
*********************************************************/
    public function rebuildCartBySku($platformId, $currencyId, $bizType, $skuInfo) {
//we will need a new CartSessionService, not a shared one
        $cartSessionService = new CartSessionService(TRUE);
        $langId = (($bizType == "ONLINE") ? LANG_ID : "en");
        foreach($skuInfo as $sku => $item) {
            $cartSessionService->add($sku, $item["qty"], $langId, $platformId, $currencyId);
        }
        $cart = $cartSessionService->getCart();
        return $cart;
    }

    public function isFraudOrder($soObj = '')
    {
        if (!$soObj)
            return false;

        $so_no = $soObj->getSoNo();
        if ($clientObj = $this->getService("Client")->getDao()->get(array("id" => $soObj->getClientId()))) {
            $this->emailReferralListService = new EmailReferralListService;
            $clientEmail = $clientObj->getEmail();
            if ($blackListObject = $this->emailReferralListService->get(array('email' => $clientEmail, '`status`' => 1))) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    private function _initPriceService($platformType = null) {
        if (is_null($platformType)) {
            $this->priceService = new PriceService;
        } else {
            $classname = "ESG\Panther\Service\Price" . ucfirst(strtolower($platformType)) . "Service";
            $this->priceService = new $classname($platformType);
        }
    }

    private function _setProfitInfo(\BaseVo $soidObj, $platformId, $decPlace)
    {
        $use_new = true;
        if (!$this->_platformType) {
            if (defined('PLATFORM_TYPE'))
                $this->_platformType = PLATFORM_TYPE;
            else
                $this->_platformType = $this->getService("SellingPlatform")->getDao("SellingPlatform")->get(["selling_platform_id" => $platformId])->getType();
        }
/* calculate raw first, common to all */
        $this->_initPriceService($this->_platformType);
        $unitSellingPrice = $soidObj->getUnitPrice();
        $json = $this->priceService->getProfitMarginJson($platformId, $soidObj->getItemSku(), $unitSellingPrice);
        $jj = json_decode($json, true);

        $soidObj->setProfitRaw(round($jj["get_profit"], $decPlace));
        $soidObj->setMarginRaw(round($jj["get_margin"], $decPlace));

        if ($use_new) {
            $selling_price = ($soidObj->getAmount()/* + $gst*/) / $soidObj->getQty();
            $json = $this->priceService->getProfitMarginJson($platformId, $soidObj->getItemSku(), $selling_price);
            $jj = json_decode($json, true);
            $soidObj->setCost(round($jj["get_cost"], $decPlace));
            $soidObj->setProfit(round($jj["get_profit"], $decPlace));
            $soidObj->setMargin(round($jj["get_margin"], $decPlace));
        }
    }
}
