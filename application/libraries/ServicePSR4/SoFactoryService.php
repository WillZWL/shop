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
use ESG\Panther\Service\ClientService;
use ESG\Panther\Service\ProductService;
use ESG\Panther\Service\CreateClientInterface;
use ESG\Panther\Service\ExchangeRateService;;
use ESG\Panther\Service\PriceWebsiteService;;
use ESG\Panther\Service\PriceService;;

class SoFactoryService extends BaseService
{
    const ENABLE_SO_ITEM = FALSE;
    public $injectObj = null;
    public $clientService = null;

    public function __construct($injectObj = null) {
        parent::__construct();
        $this->injectObj = $injectObj;
        $this->clientService = new ClientService;
        $this->productService = new ProductService;
        $this->exchangeRateService = new ExchangeRateService;
        $this->cartSessionService = new CartSessionService;
        $this->setDao(new SoDao());
        $this->setSoItemDao(new SoItemDao());
        $this->setSoItemDetailDao(new SoItemDetailDao());
        $this->setSoPaymentStatusDao(new SoPaymentStatusDao());
        $this->setSoExtendDao(new SoExtendDao());
        $this->setSoCreditChkDao(new SoCreditChkDao());
        $this->setSoRiskDao(new SoRiskDao());
    }

    public function getNewCartByOrderInfo($orderInfo) {
        $skuList = [];
        foreach($orderInfo->items as $sku => $item) {
            $skuList[$sku] = ["qty" => $item->getQty(), "amount" => $item->getAmount()];
        }
//Centralize a buildcart function, to get all the cart details
        $newCart = $this->rebuildCartBySku($orderInfo->getPlatformId(), $orderInfo->getBizType(), $skuList);    
        $newCart->setPlatformOrderId($orderInfo->getPlatformOrderId());
        $newCart->setPlatformId($orderInfo->getPlatformId());

        return $newCart;
    }

    public function createSaleOrder($clientAndCheckoutInfo = [], $orderInfo) {
        $soObj = null;
//create client
        $clientInfo = $clientAndCheckoutInfo;
        $clientObj = $this->_createClient($clientInfo);
        if ($clientObj) {
//rebuild the cart info, prevent hacking or price updated by BD
            $newCart = $this->getNewCartByOrderInfo($orderInfo);
            $newSoNo = $this->getDao()->getNewSoNo();
            $this->getDao()->db->trans_start();
            $soObj = $this->_createSo($clientObj, $newCart, $newSoNo);
            if ($soObj !== false)
            {
                if (self::ENABLE_SO_ITEM)
                {
                    if (!$this->_createSoItem($soObj, $newCart))
                    {
                        $this->getDao()->db->trans_rollback();
                    }
                }
                if ($this->_createSoItemDetailAndUpdateSo($soObj, $newCart)) {
                    if ($this->_createSoPaymentStatus($soObj, $clientAndCheckoutInfo)) {
                        if (!$this->_createSoExtend($soObj, $clientAndCheckoutInfo)) {
                            $this->getDao()->db->trans_rollback();
                        }
                    } else {
                        $this->getDao()->db->trans_rollback();
                    }
                } else {
                    $this->getDao()->db->trans_rollback();
                }
            }
            $transCompleteResult = $this->getDao()->db->trans_complete();
            if ($transCompleteResult === false)
            {
                $subject = "[Panther] Rollback Create so:(" . (($soObj) ? $soObj->getSoNo():"") . ") " . __METHOD__ . __LINE__;
                $message = $this->getDao()->db->last_query() . "," . $this->getDao()->db->_error_message();
                $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
                return false;
            }
        } else {
//no need to send email alert, as Client Service handle it already
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
        $soExtendObj = $this->getSoExtendDao()->get();
        $soExtendObj->setSoNo($soObj->getSoNo());
        if (isset($checkoutInfo["convSiteId"]))
        {
//            $this->storeAfInfo($soExtendObj, $checkoutInfo["convSiteId"]);
            $soExtendObj->setConvSiteId($checkoutInfo["convSiteId"]);
        }
        if (isset($checkoutInfo["convSiteRef"]))
            $soExtendObj->setConvSiteRef($checkoutInfo["convSiteRef"]);
        
        $insertSoExtendResult = $this->getSoExtendDao()->insert($soExtendObj);
        if ($insertSoExtendResult === false)
        {
            $subject = "[Panther] Cannot create so extend:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getSoExtendDao()->db->last_query() . "," . $this->getSoExtendDao()->db->_error_message();
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $insertSoPaymentResult;
    }

    private function _createSoPaymentStatus($soObj, $checkoutInfo)
    {
        $soPaymentStatusObj = $this->getSoPaymentStatusDao()->get();
        $soPaymentStatusObj->setSoNo($soObj->getSoNo());
        $soPaymentStatusObj->setPaymentGatewayId($checkoutInfo["paymentGatewayId"]);
        $soPaymentStatusObj->setCardId($checkoutInfo["paymentCardId"]);
        $soPaymentStatusObj->setPaymentStatus("N");
        
        $insertSoPaymentResult = $this->getSoPaymentStatusDao()->insert($soPaymentStatusObj);
        if ($insertSoPaymentResult === false)
        {
            $subject = "[Panther] Cannot create so payment status:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getSoPaymentStatusDao()->db->last_query() . "," . $this->getSoPaymentStatusDao()->db->_error_message();
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $insertSoPaymentResult;
    }

    private function _createClient($clientInfo = []) {
        $clientObj = $this->clientService->createClient($clientInfo, $this->injectObj, true);
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
        $updateSoResult = $this->getDao()->update($soObj);
        if ($updateSoResult === false)
        {
            $subject = "[Panther] Cannot update so cost:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao()->db->last_query() . "," . $this->getDao()->db->_error_message();
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
        $soItemDetailObj = $this->getSoItemDetailDao()->get();
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

        $insertSoItemDetailResult = $this->getSoItemDetailDao()->insert($soItemDetailObj);
        if ($insertSoItemDetailResult === false)
        {
            $subject = "[Panther] Cannot create so item detail:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getSoItemDetailDao()->db->last_query() . "," . $this->getSoItemDetailDao()->db->_error_message();
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $soItemDetailObj;
    }

    private function _createSingleSoItem($soObj, $item, $lineNo) {
        $soItemObj = $this->getSoItemDao()->get();
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

        $insertSoItemResult = $this->getSoItemDao()->insert($soItemObj);
        if ($insertSoItemResult === false)
        {
            $subject = "[Panther] Cannot create so item:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getSoItemDao()->db->last_query() . "," . $this->getSoItemDao()->db->_error_message();
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
            return false;
        }
        return $soItemObj;
    }

    private function _createSo($clientObj, $orderInfo, $newSoNo, $parentSoNo = null) {
        $soObj = $this->getDao()->get();
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
        $soObj->setBizType($orderInfo->getBizType());
        $soObj->setDeliveryCharge($orderInfo->getDeliveryCharge());
        $soObj->setDeliveryTypeId($orderInfo->getDeliveryType());
        $soObj->setWeight($orderInfo->getTotalWeight());
        if ($orderInfo->getBizType() == "ONLINE")
            $soObj->setLangId(LANG_ID);
        else {
//other are all through admincentre
            $soObj->setLangId("en");
        }

        if (isset($parentSoNo))
            $soObj->setParentSoNo($parentSoNo);
        $soObj->setStatus(1);
        $soObj->setHoldStatus(0);
        $soObj->setRefundStatus(0);
        $soObj->setCsCustomerQuery(0);
        if ($orderInfo->getOrderCreateDate())
            $soObj->setOrderCreateDate($orderInfo->getOrderCreateDate());
        else
            $soObj->setOrderCreateDate(date("Y-m-d H:i:s"));

        $this->setOrderInfoDetail($orderInfo->getPlatformId(), $soObj);
        $insertSoResult = $this->getDao()->insert($soObj);
        if ($insertSoResult === false)
        {
            $subject = "[Panther] Cannot create so:(" . $soObj->getSoNo() . ") " . __METHOD__ . __LINE__;
            $message = $this->getDao()->db->last_query() . "," . $this->getDao()->db->_error_message();
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
        list($usdArr) = $this->exchangeRateService->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "USD");
        list($eurArr) = $this->exchangeRateService->getDao("ExchangeRate")->getExchangeRateByPlatform($platformId, "EUR");

        if (isset($usdArr["rate"]))
            $soObj->setRate($usdArr["rate"]);
        if (isset($eurArr["rate"]))
            $soObj->setRef1($eurArr["rate"]);
    }

/********************************************************
**  function to prevent cart was amended by user and in case the price is updating by BD before checkout
**  rebuildCart to find all the required value for an order
*********************************************************/
    public function rebuildCartBySku($platformId, $bizType, $skuInfo) {
        $totalItems = 0;
        $totalItemCost = 0;
        $totalAmount = 0.0;
        $orderVatTotal = 0;
        $totalWeight = 0;

        $newCart = new \CartDto();
        if ($bizType== "ONLINE")
            $langId = LANG_ID;
        else
            $langId = "en";
        foreach($skuInfo as $sku => $item)
        {
            $newProductInfo = $this->cartSessionService->getCartItemInDetail($sku, $langId, $platformId);
            $newProductInfo->setQty($item["qty"]);
            $newProductInfo->setUnitCost(round($newProductInfo->getUnitCost(), $newProductInfo->getDecPlace()));
            $newProductInfo->setVatTotal(round($newProductInfo->getVatTotal(), $newProductInfo->getDecPlace()));
            $unitPrice = $newProductInfo->getPrice();

            $itemSubTotal = $unitPrice * $newProductInfo->getQty();
            if ($bizType == "ONLINE")
                $newProductInfo->setAmount($itemSubTotal);
            else
                $newProductInfo->setAmount($item["amount"]);

            $totalItemCost += $newProductInfo->getUnitcost() * $newProductInfo->getQty();
            $totalAmount += $itemSubTotal;

            $vatPercent = $newProductInfo->getVatPercent();
            $totalItems += $newProductInfo->getQty();
            $orderVatTotal += round(($newProductInfo->getVatTotal() * $newProductInfo->getQty()), $newProductInfo->getDecPlace());
            $totalWeight += $newProductInfo->getUnitWeight() * $newProductInfo->getQty();
            $newCart->items[$sku] = $newProductInfo;

            if (!$newCart->getPlatformCurrency())
                $newCart->setPlatformCurrency($newProductInfo->getPlatformCurrency());
        }

        $newCart->setSubtotal($totalAmount);
        $newCart->setTotalNumberOfItems($totalItems);
        $newCart->setVatPercent($vatPercent);
        $newCart->setVat($orderVatTotal);
        $newCart->setBizType($bizType);
//this is itemCost only, not all the cost from price service
        $newCart->setCost($totalItemCost);
        $newCart->setTotalWeight($totalWeight);

        return $newCart;
    }

    public function isFraudOrder($soObj = '')
    {
        if (!$soObj)
            return false;

        $so_no = $soObj->getSoNo();
        if ($clientObj = $this->clientService->getDao()->get(array("id" => $soObj->getClientId()))) {
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
/*
        if (!defined('PLATFORM_TYPE'))
            $use_new = true;
        else {
            if (PLATFORM_TYPE == 'EBAY' || PLATFORM_TYPE == 'QOO10' || PLATFORM_TYPE == 'FNAC' || PLATFORM_TYPE == 'RAKUTEN')
                $use_new = false;
            else
                $use_new = true;
        }
*/
        $use_new = true;
        if (defined('PLATFORM_TYPE'))
            $type = PLATFORM_TYPE;
        else
            $type = $this->get_pbv_srv()->selling_platform_dao->get(array("id" => $platformId))->get_type();

/* calculate raw first, common to all */
        $this->_initPriceService($type);
        $unitSellingPrice = $soidObj->getUnitPrice();
        $json = $this->priceService->getProfitMarginJson($platformId, $soidObj->getItemSku(), $unitSellingPrice);
        $jj = json_decode($json, true);

        $soidObj->setProfitRaw(round($jj["get_profit"], $decPlace));
        $soidObj->setMarginRaw(round($jj["get_margin"], $decPlace));

        if ($use_new) {
//            $this->_initPriceService($type);
//            $gst = @$soidObj->getGstTotal();
            $selling_price = ($soidObj->getAmount()/* + $gst*/) / $soidObj->getQty();
            $json = $this->priceService->getProfitMarginJson($platformId, $soidObj->getItemSku(), $selling_price);
            //file_put_contents("/var/log/vb-json", "{$soidObj->get_so_no()} || $json", FILE_APPEND);

            $jj = json_decode($json, true);

            $soidObj->setCost(round($jj["get_cost"], $decPlace));
            $soidObj->setProfit(round($jj["get_profit"], $decPlace));
            $soidObj->setMargin(round($jj["get_margin"], $decPlace));

        }/* else {
            $soidObj->setProfit(round($soidObj->getAmount() - $soidObj->getCost(), $decPlace));
            if ($soidObj->getAmount()) {
                $soidObj->setMargin(round($soidObj->getProfit() / $soidObj->getAmount() * 100, $decPlace));
            } else {
                $soidObj->setMargin(0);
            }
        }*/
    }

    public function getSoItemDao()
    {
        return $this->soItemDao;
    }

    public function setSoItemDao($value)
    {
        $this->soItemDao = $value;
    }

    public function getSoItemDetailDao()
    {
        return $this->soItemDetailDao;
    }

    public function setSoItemDetailDao($value)
    {
        $this->soItemDetailDao = $value;
    }

    public function getSoPaymentStatusDao()
    {
        return $this->soPaymentStatusDao;
    }

    public function setSoPaymentStatusDao($value)
    {
        $this->soPaymentStatusDao = $value;
    }

    public function getSoExtendDao()
    {
        return $this->soExtendDao;
    }

    public function setSoExtendDao($value)
    {
        $this->soExtendDao = $value;
    }

    public function getSoCreditChkDao()
    {
        return $this->soCreditChkDao;
    }

    public function setSoCreditChkDao($value)
    {
        $this->soCreditChkDao = $value;
    }

    public function getSoRiskDao()
    {
        return $this->soRiskDao;
    }

    public function setSoRiskDao($value)
    {
        $this->soRiskDao = $value;
    }
}
