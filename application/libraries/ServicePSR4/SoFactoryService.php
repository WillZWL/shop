<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SoDao;
use ESG\Panther\Dao\SoItemDao;
use ESG\Panther\Dao\SoItemDetailDao;
use ESG\Panther\Dao\SoPaymentStatusDao;
use ESG\Panther\Dao\SoExtendDao;
use ESG\Panther\Dao\SoCreditChkDao;
use ESG\Panther\Dao\SoRiskDao;
use ESG\Panther\Service\CartSessionService;

class SoFactoryService extends BaseService
{
    public $_siteObj;

    public function __construct() {
        parent::__construct();
        $this->setDao(new SoDao());
    }

    public function getNewCartByOrderInfo($orderInfo, $bizType, $clientObj) {
        $skuList = [];
        foreach($orderInfo->items as $sku => $item) {
            $skuList[$sku] = ["qty" => $item->getQty(), "amount" => $item->getAmount(),"promoDiscAmt"=>$item->getPromoDiscAmt()];
        }
//Centralize a buildcart function, to get all the cart details
        $newCart = $this->rebuildCartBySku($orderInfo, $bizType, $skuList, $clientObj);
        foreach($newCart->items as $sku => $item){
            if($skuList[$sku]["promoDiscAmt"]){
                $item->setPromoDiscAmt($skuList[$sku]["promoDiscAmt"]); 
                $item->setAmount($skuList[$sku]["amount"]); 
            } 
        }
        $newCart->setPlatformOrderId($orderInfo->getPlatformOrderId());
        $newCart->setPlatformId($orderInfo->getPlatformId());
        $newCart->setPromotionCode($orderInfo->getPromotionCode());
        $newCart->setPromoDiscTotal($orderInfo->getPromoDiscTotal());

        return $newCart;
    }

    public function createSaleOrder(CreateSoInterface $interfaceType) {
        if ($interfaceType instanceof CreateSoInterface) {
//create client
            $clientObj = $this->_createClient($interfaceType);
            if ($clientObj) {
//rebuild the cart info, prevent hacking or price updated by BD
                if ($interfaceType->getBizType() == "ONLINE")
                    $newCart = $this->getNewCartByOrderInfo($interfaceType->getCartDto(), $interfaceType->getBizType(), $clientObj);
                else
                    $newCart = $interfaceType->getCartDto();
                $newSoNo = $this->getDao("So")->getNewSoNo();
                $this->getDao("So")->db->trans_start();
                $soObj = $this->_createSo($clientObj, $newCart, $newSoNo, null, $interfaceType);
                if ($soObj !== false) {
                    if (($createSoItemResult = $this->_createSoItemDetailAndUpdateSo($soObj, $newCart))
                        && ($createSoItemResult["result"])) {
//no error check for complementary accessory
                        $this->_addComplementaryAccessory($soObj, $createSoItemResult["lastLineNo"]);

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
                    $this->getDao("So")->db->trans_rollback();
                    return false;
                }
            } else {
//no need to send email alert, as Client Service handle it already
                return false;
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
        if ($_COOKIE['af'])
            $soExtendObj->setConvSiteId($_COOKIE['af']);

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
        $soPaymentStatusObj->setPayDate($checkoutInfo->getPayDate());
        $soPaymentStatusObj->setCardId($checkoutInfo->getPaymentCardId());
        $soPaymentStatusObj->setPaymentStatus("S");

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

    private function _addComplementaryAccessory($soObj, $lastLineNo = 0) {
        $soItemObj = $this->getDao("SoItemDetail")->getList(["so_no" => $soObj->getSoNo()], ["limit" => -1]);
        $caList = $this->getService("So")->getDao("SoItemDetail")->getComplementaryAccessoryListBySo($soObj->getSoNo());
        if ($lastLineNo > 0)
            $i = $lastLineNo;
        else
        {
//find it here, not implemented yet
        }

        foreach($caList as $cartItem) {
            $i++;
            $cartItem->setAmount(0);
            $this->_createSingleSoItemDetail($soObj, $cartItem, $i/*, soObj->getPlatformId()*/);
        }
        if ($i > $lastLineNo) {
            $soObj->setOrderTotalItem($i);

            $this->getDao("So")->update($soObj);
        }
    }

    private function _createSoItemDetailAndUpdateSo($soObj, $cart) {
        $result = true;
        $totalCost = 0;
        $i = 0;
        foreach($cart->items as $sku => $item) {
            $i++;
            $soItemDetailObj = $this->_createSingleSoItemDetail($soObj, $item, $i, $cart->getPlatformId());
            if (!$soItemDetailObj)
                $result = false;
            else {
                $totalCost += $soItemDetailObj->getCost() * $soItemDetailObj->getQty();
            }
        }

        if ($i > 0) {
            $soObj->setOrderTotalItem($i);
        }

        $soObj->setCost($totalCost);
        $updateSoResult = $this->getDao("So")->update($soObj);
        if ($updateSoResult === false) {
            $subject = "[Panther] Cannot update so cost:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("So")->db->last_query() . "," . $this->getDao("So")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_GENERAL_LEVEL);
        }

        return ["result" => $result, "lastLineNo" => $i];
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

    private function _createSingleSoItemDetail($soObj, $item, $lineNo, $platformId = null) {
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
        $soItemDetailObj->setItemUnitCost($item->getSupplierUnitCostInHkd());

//promo code, bundle, bundle may be implemented different from VB
        $soItemDetailObj->setBundleCoreId(0);
        $soItemDetailObj->setBundleLevel(0);
        if ($item->getDiscountTotal())
            $soItemDetailObj->setDiscountTotal($item->getDiscountTotal());
        if ($item->getPromoDiscAmt())
            $soItemDetailObj->setPromoDiscAmt($item->getPromoDiscAmt());
//better to use a function to calculate VAT, GST in the future
        $priceWithCost = new \PriceWithCostDto();
        $priceWithCost->setPrice($item->getAmount());
        $priceWithCost->setPlatformCountryId($soObj->getBillCountryId());
        $this->getService("PlatformBizVar")->calculateDeclaredValue($priceWithCost);

        $soItemDetailObj->setVatTotal(round(($priceWithCost->getDeclaredValue() * $item->getVatPercent() / 100), $item->getDecPlace()));
        $soItemDetailObj->setAmount($item->getAmount());
        $this->_setProfitInfo($soItemDetailObj, $platformId, $item->getDecPlace());

        $insertSoItemDetailResult = $this->getDao("SoItemDetail")->insert($soItemDetailObj);
//        print $this->getDao("SoItemDetail")->db->last_query();
        if ($insertSoItemDetailResult === false) {
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
        if($orderInfo->getPromotionCode()){
           $soObj->setPromotionCode($orderInfo->getPromotionCode()); 
        }
        if($orderInfo->getPromoDiscTotal()){
           $soObj->setPromoDiscTotal($orderInfo->getPromoDiscTotal()); 
        }
        if ($checkoutInfoDto->getPaymentGatewayId())
            $soObj->setPaymentGatewayId($checkoutInfoDto->getPaymentGatewayId());
        if ($checkoutInfoDto->getTxnId())
            $soObj->setTxnId($checkoutInfoDto->getTxnId());
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
//set Vat
        $soObj->setVatPercent($orderInfo->getVatPercent());
        //$this->_setOrderVat($soObj, $orderInfo);

        $declared_value = $this->_getDeclaredValue($soObj->getDeliveryCountryId(), $soObj->getAmount());
        $soObj->setDeclaredValue($declared_value);

        $this->setOrderInfoDetail($orderInfo->getPlatformId(), $soObj);
        if ($injectedInterfaceObj instanceof CreateSoEventInterface) {
            $injectedInterfaceObj->soBeforeInsertEvent($soObj);
        }
        $insertSoResult = $this->getDao("So")->insert($soObj);

        if ($insertSoResult === false) {
            $subject = "[Panther] Cannot create so:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao("So")->db->last_query() . "," . $this->getDao("So")->db->error()["message"];
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        } else {
            return $soObj;
        }
    }
/*
    private function _setOrderVat($soObj, $orderInfo) {
//setup VAT

        if (!$orderInfo->getPlatformCountryId()) {
            if ($this->_siteObj == null)
                $this->_siteObj = $this->getService("LoadSiteParameter")->loadSiteByPlatform($orderInfo->getPlatformId());
            $platformCountryId = $this->_siteObj->getPlatformCountryId();
        } else {
            $platformCountryId = $orderInfo->getPlatformCountryId();
        }

        $priceWithCost = new PriceWithCostDto();
        $priceWithCost->setPrice($soObj->getAmount());
        $priceWithCost->setPlatformCountryId($platformCountryId);
        $this->getService("PlatformBizVar")->calculateDeclaredValue();
        $soObj->setVat($declaredValue * $orderInfo->getVatPercent() / 100);

        $soObj->setVatPercent($orderInfo->getVatPercent());
    }
*/
/**************************************************
**  pass CartDto into
**  you can form this DTO and pass the
***************************************************/
    public function setOrderInfoDetail($platformId, $soObj) {
//rate, ref_1, expect_delivery_date
        list($usdArr) = $this->getService("ExchangeRate")->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "USD");
        list($eurArr) = $this->getService("ExchangeRate")->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "EUR");
        list($hkdArr) = $this->getService("ExchangeRate")->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "HKD");

        if (isset($usdArr["rate"]))
            $soObj->setRate($usdArr["rate"]);
        if (isset($eurArr["rate"]))
            $soObj->setRef1($eurArr["rate"]);
        if (isset($hkdArr["rate"]))
            $soObj->setRateToHkd($hkdArr["rate"]);
    }

/********************************************************
**  function to prevent cart was amended by user and in case the price is updating by BD before checkout
**  rebuildCart to find all the required value for an order
*********************************************************/
    public function rebuildCartBySku($orderInfo, $bizType, $skuInfo, $clientObj) {
//we will need a new CartSessionService, not a shared one
        $cartSessionService = new CartSessionService(TRUE);
        $langId = (($bizType == "ONLINE") ? LANG_ID : "en");
        foreach($skuInfo as $sku => $item) {
            $cartSessionService->add($sku, $item["qty"], $langId, $orderInfo->getPlatformId(), $orderInfo->getPlatformCurrency());
        }
/* need to update delivery charge */
        $siteInfo = $this->getService("LoadSiteParameter")->loadSiteByPlatform($orderInfo->getPlatformId());
        $deliverySurcharge = $this->getService("Delivery")->getDelSurcharge($siteInfo, $clientObj->getDelPostcode(), $clientObj->getDelCountryId());
        if ($deliverySurcharge > 0) {
            $cartSessionService->updateCartDelivery($deliverySurcharge);
        }
        $cart = $cartSessionService->getCart();
        return $cart;
    }

    public function isFraudOrder($soObj = '')
    {
        if (!$soObj)
            return false;

        $so_no = $soObj->getSoNo();
        if ($clientObj = $this->getDao('Client')->get(array("id" => $soObj->getClientId()))) {
            $clientEmail = $clientObj->getEmail();
            if ($blackListObject = $this->getDao('EmailReferralList')->get(array('email' => $clientEmail, '`status`' => 1))) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
/*
    private function _initPriceService($platformType = null) {
        if (is_null($platformType)) {
            $this->_priceService = new PriceService;
        } else if (is_null($this->_priceService)) {
            $classname = "ESG\Panther\Service\Price" . ucfirst(strtolower($platformType)) . "Service";
            $this->_priceService = new $classname($platformType);
        }
    }
*/
    private function _setProfitInfo(\BaseVo $soidObj, $platformId, $decPlace)
    {
        if ($platformId) {
            $calProfitDto = new \CalculateProfitDto($soidObj->getItemSku(), $soidObj->getQty(), $soidObj->getUnitPrice(), $soidObj->getAmount());
            $this->getService("CartSession")->storeProfitInfoToDto($calProfitDto, $platformId, $decPlace);
            $soidObj->setProfitRaw($calProfitDto->getRawProfit());
            $soidObj->setMarginRaw($calProfitDto->getRawMargin());
            $soidObj->setCost($calProfitDto->getCost());
            $soidObj->setProfit($calProfitDto->getProfit());
            $soidObj->setMargin($calProfitDto->getMargin());
        } else {
            $soidObj->setCost(0);
            $soidObj->setProfit(0);
            $soidObj->setMargin(0);
        }
/*
        $use_new = true;
        if (!$this->_platformType) {
            if (defined('PLATFORM_TYPE'))
                $this->_platformType = PLATFORM_TYPE;
            else
                $this->_platformType = $this->getService("SellingPlatform")->getDao("SellingPlatform")->get(["selling_platform_id" => $platformId])->getType();
        }

        $this->_initPriceService($this->_platformType);
        $unitSellingPrice = $soidObj->getUnitPrice();
        $json = $this->_priceService->getProfitMarginJson($platformId, $soidObj->getItemSku(), $unitSellingPrice);
        $jj = json_decode($json, true);

        $soidObj->setProfitRaw(round($jj["get_profit"], $decPlace));
        $soidObj->setMarginRaw(round($jj["get_margin"], $decPlace));

        if ($use_new) {
            $selling_price = ($soidObj->getAmount()) / $soidObj->getQty();
            $json = $this->_priceService->getProfitMarginJson($platformId, $soidObj->getItemSku(), $selling_price);
            $jj = json_decode($json, true);
            $soidObj->setCost(round($jj["get_cost"], $decPlace));
            $soidObj->setProfit(round($jj["get_profit"], $decPlace));
            $soidObj->setMargin(round($jj["get_margin"], $decPlace));
        }
*/
    }

    public function _getDeclaredValue($country_id = "", $price = "", $vat = 0)
    {
        $max_declared_value = -1;
        $declared_pcent = 100;
        $declared = -1;
        switch ($country_id) {
            case "AU":
                if ($price < 910) {
                    $declared_pcent = 100;
                } else {
                    $max_declared_value = 910;
                }
                break;
            case "NZ":
                if ( ($price-$vat) <= 350) {
                    $declared_pcent = 100;
                } elseif ( ( ($price - $vat) > 350) && ( ($price - $vat ) <= 470) ) {
                    $declared_pcent = 80;
                } elseif ( ($price > 470) && ($price <= 970) ) {
                    $declared_pcent = 80;
                } else {
                    $max_declared_value = 776;
                }
                break;
            case 'SG':
                if ($price >= 350) {
                    $max_declared_value = 350;
                } else {
                   $declared_pcent = 100;
                }
                break;
            case 'TH':
                if ($price < 5454) {
                    $declared_pcent = 100;
                } else {
                    $max_declared_value = 5454;
                }
                break;
            case 'PH':
                if ($price < 4142) {
                    $declared_pcent = 100;
                } else {
                    $max_declared_value = 4142;
                }
                break;
            case 'MY':
                if ($price < 266) {
                    $declared_pcent = 100;
                } else {
                    $max_declared_value = 266;
                }
                break;
            case 'MX':
                if ($price < 1300) {
                    $max_declared_value = 266;
                } else {
                    $declared_pcent = 10;
                }
                break;
            default:
                $declared_pcent = 10;
                break;
        }

        if ($max_declared_value != -1) {
            if ($price > $max_declared_value) {
                $declared = $max_declared_value;
            } else {
                $declared = $price;
            }
        } else {
            $declared = $price * $declared_pcent / 100;
        }
        return $declared;
    }
}
