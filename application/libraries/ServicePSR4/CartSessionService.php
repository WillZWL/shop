<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\ProductService;
use ESG\Panther\Dao\SoDao;
use ESG\Panther\Models\Website\PromotionFactoryModel;

class CartSessionService extends BaseService
{
    const ALLOW_AND_IS_NORMAL_ITEM = 1;
    const ALLOW_AND_IS_PREORDER = 10;
    const ALLOW_AND_IS_ARRIVING = 20;
    const SAME_PREORDER_ITEM = 30;
    const SAME_ARRIVING_ITEM = 35;
    const SAME_NORMAL_ITEM = 40;
    const DECISION_POINT = 50;
    const NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM = 60;
    const NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM = 70;
    const DIFFERENT_PREORDER_ITEM = 80;
    const DIFFERENT_ARRIVING_ITEM = 85;
    const UNKNOWN_ITEM_STATUS = 100;

    const CART_ACTION_ADD = "ADD";
    const CART_ACTION_SUBTRACTION = "MINUS";
    const CART_ACTION_SET = "SET";
    const CART_ACTION_REMOVE = "REMOVE";
    const CART_TYPE_ONLINE = "ONLINE";
    const CART_TYPE_OFFLINE = "OFFLINE";
    const CART_TYPE_MANUAL = "MANUAL";
    const CART_TYPE_SPECIAL = "SPECIAL";

    private $_cart = null;
    private $_platformType = null;
    private $_priceService = null;

//with this _rebuildCartNoSessionMode mode enabled, we will get detail cart info, instead of lite info
    private $_rebuildCartNoSessionMode = false;
    public static $noRebuild = false;
    public $support_email = "oswald-alert@eservicesgroup.com";

    public function __construct($rebuildMode = false) {
        parent::__construct();
        $this->productService = new ProductService;
        $this->promotionFactoryModel = new PromotionFactoryModel;
        $this->setSoDao(new SoDao);
//var_dump($_SESSION["cart"]);
//unset($_SESSION["cart"]);
        $this->_rebuildCartNoSessionMode = $rebuildMode;
        if ($this->_rebuildCartNoSessionMode) {
            CartSessionService::setNoRebuildCart();
        }
        if ((!$this->_rebuildCartNoSessionMode) && (isset($_SESSION["cart"]))) {
            $this->_cart = unserialize($_SESSION["cart"]);
           
        }
    }

    public static function setNoRebuildCart()
    {
        CartSessionService::$noRebuild = true;
    }

    public function __destruct () {
        if ($this->_cart) {
            if (!CartSessionService::$noRebuild) {
                $_SESSION["cart"] = serialize($this->_cart);
            }
        }
    }

    public function setOfflineFee($offlineFee) {
        if ($this->_cart) {
            $this->_cart->setOfflineFee($offlineFee);
        }
    }

    public function calculateAndGetCartProfit() {
        $totalProfit = 0;
        $totalCost = 0;
        if ($this->_cart) {
            foreach ($this->_cart->items as $sku => $item) {
//                var_dump($this->_cart->items);exit;
                $decPlace = $this->_cart->items[$sku]->getDecPlace();
                $calProfitDto = new \CalculateProfitDto($sku, $this->_cart->items[$sku]->getQty(), $this->_cart->items[$sku]->getPrice(), $this->_cart->items[$sku]->getAmount());
                $this->storeProfitInfoToDto($calProfitDto, $this->_cart->getPlatformId(), $this->_cart->items[$sku]->getDecPlace());
                $this->_cart->items[$sku]->setProfitRaw($calProfitDto->getRawProfit());
                $this->_cart->items[$sku]->setMarginRaw($calProfitDto->getRawMargin());
                $this->_cart->items[$sku]->setUnitCost($calProfitDto->getCost());
                $this->_cart->items[$sku]->setProfit($calProfitDto->getProfit());
                $this->_cart->items[$sku]->setMargin($calProfitDto->getMargin());
                $totalProfit += $calProfitDto->getProfit();
                $totalCost += $calProfitDto->getCost() * $this->_cart->items[$sku]->getQty();
            }
            $this->_cart->setTotalProfit($totalProfit);
            $this->_cart->setCost($totalCost);
            $this->_cart->setMargin(round((($this->_cart->getGrandTotal() - $totalCost) / $this->_cart->getGrandTotal() * 100), $decPlace));
       }
    }

    public function updateCartDelivery($deliveryCharge) {
        $this->_cart->setDeliveryCharge($deliveryCharge);
        $this->updateQuickInfo();
    }

    public function updateQuickInfo($totalItems = null) {
        if ($totalItems != null)
            $_SESSION["CART_QUICK_INFO"]["TOTAL_NUMBER_OF_ITEMS"] = $totalItems;
        if ($this->_cart)
            $_SESSION["CART_QUICK_INFO"]["TOTAL_AMOUNT"] = $this->_cart->getGrandTotal();
        else {
            $_SESSION["CART_QUICK_INFO"]["TOTAL_NUMBER_OF_ITEMS"] = 0;
            $_SESSION["CART_QUICK_INFO"]["TOTAL_AMOUNT"] = 0;
        }
    }

/******************************************************************************************************************
**  getCart($saveProfit)
**  $saveProfit default = false, we won't calculate profit until client really checkout to save server resources
******************************************************************************************************************/
    public function getCart($saveProfit = false) {
        $totalItems = 0;
        $totalAmount = 0.0;
        if ($this->_cart) {
            foreach ($this->_cart->items as $sku => $item) {
                $unitPrice = $item->getPrice();
                $itemSubTotal = $unitPrice * $item->getQty()-$item->getPromoDiscAmt();
                $this->_cart->items[$sku]->setAmount($itemSubTotal);
                $totalItems += $item->getQty();
                $totalAmount += $itemSubTotal;
                $vatPercent = $item->getVatPercent();
                $orderVatTotal += round(($item->getVatTotal() * $item->getQty()), $item->getDecPlace());
                $totalWeight += $item->getUnitWeight() * $item->getQty();
                $totalItemCost += $item->getUnitcost() * $item->getQty();
            }
//this is itemCost only, not all the cost from price service
            $this->_cart->setCost($totalItemCost);
            $this->_cart->setTotalWeight($totalWeight);
            $this->_cart->setVatPercent($vatPercent);
            $this->_cart->setVat($orderVatTotal);
            $this->_cart->setSubtotal($totalAmount);
            if ($this->_cart)
            {
                $this->_cart->setTotalNumberOfItems($totalItems);
            }
//            $this->_cart->setBizType($type);
        }
        if ($saveProfit) {
            $this->calculateAndGetCartProfit();
        }
        //if promotion code used, get promotion item again
        $this->initPromotionFactoryService("modifyPromotionCart");

        if($this->_cart){
            $this->updateQuickInfo($this->_cart->getTotalNumberOfItems());
        }
        //end get promotion item
        return $this->_cart;    //=return $_SESSION["cart"]
    }

    public function add($sku, $qty, $lang, $platformId, $currencyId) {
        if (!$this->modifyItem(self::CART_ACTION_ADD, $sku, $qty, $lang, $platformId)) {
            $this->addItemToSession($sku, $qty, $lang, $platformId, $currencyId);
        }
    }

    public function minus($sku, $qty, $lang, $platformId, $currencyId) {
        $this->modifyItem(self::CART_ACTION_SUBTRACTION, $sku, $qty, $lang, $platformId, $currencyId);
    }

    public function setQty($sku, $qty, $lang, $platformId, $currencyId) {
        $this->modifyItem(self::CART_ACTION_SET, $sku, $qty, $lang, $platformId, $currencyId);
    }

    public function emptyCart() {
        $this->_cart = null;
        unset($_SESSION["cart"]);
        unset($_SESSION["CART_QUICK_INFO"]);
    }

    public function modifyItem($action, $sku, $qty, $platformId) {

        if (isset($this->_cart) && is_array($this->_cart->items) && array_key_exists($sku, $this->_cart->items)) {
            if ($action == self::CART_ACTION_ADD) {
                $this->_cart->items[$sku]->setQty($this->_cart->items[$sku]->getQty() + $qty);
            }
            elseif ($action == self::CART_ACTION_SUBTRACTION) {
                $this->_cart->items[$sku]->setQty($this->_cart->items[$sku]->getQty() - $qty);
                if ($this->_cart->items[$sku]->getQty() <= 0) {
                    $this->removeItem($sku);
                }
            }
            elseif ($action == self::CART_ACTION_SET) {
                $this->_cart->items[$sku]->setQty($qty);
                if ($this->_cart->items[$sku]->getQty() <= 0) {
                    $this->removeItem($sku);
                }
            }
            elseif ($action == self::CART_ACTION_REMOVE)
                $this->removeItem($sku);
            return true;
        }
        return false;
    }

    public function removeItem($sku) {

        if (isset($this->_cart))
            unset($this->_cart->items[$sku]);
        $this->initPromotionFactoryService("validRemoveItemPromotion");
        if (sizeof($this->_cart->items) == 0){
            $this->emptyCart();
        }
    }

    public function manualAddItemsToCart($skuList = [], $platformId, $platformBizObj, $deliveryCharge = null, $relaxCriteria = false) {
        if (!$this->_cart) {
            $this->_cart = [];
            $this->_cart = new \CartDto();
            $this->_cart->setPlatformId($platformId);
            $this->_cart->setPlatformCurrency($platformBizObj->getPlatformCurrencyId());
            $this->_cart->setVatPercent($platformBizObj->getVatPercent());
            $this->_cart->setLanguageId($platformBizObj->getLanguageId());

            if (!is_null($deliveryCharge))
                $this->_cart->setDeliveryCharge($deliveryCharge);
            $this->_cart->items = [];
        }
        foreach($skuList as $sku => $item) {
            $productDetails = $this->_createCartItem($sku, $platformBizObj->getLanguageId(), $platformId, $relaxCriteria, true);
            $productDetails->setQty($item["qty"]);
            $productDetails->setPrice($item["unitPrice"]);
            $this->_cart->items[$sku] = $productDetails;
        }
    }

    public function addItemToSession($sku, $qty, $lang, $platformId, $currencyId) {
        if ($productDetails = $this->_createCartItem($sku, $lang, $platformId)) {
            if (isset($productDetails) && $productDetails) {
                if (!$this->_cart) {
                    $this->_cart = [];
                    $this->_cart = new \CartDto();
                    $this->_cart->setPlatformId($platformId);
                    $this->_cart->setPlatformCurrency($currencyId);
                    $this->_cart->setLanguageId($lang);
                    $this->_cart->items = [];
                }
                $productDetails->setQty($qty);
//                $productDetails->setAmount($qty * $productDetails->getPrice());
                $this->_cart->items[$sku] = $productDetails;
            }
        }
    }

    private function _createCartItem($sku, $lang, $platformId, $relaxCriteria = false, $ignoreListingStatus = false) {
        if ($this->_rebuildCartNoSessionMode || $this->getCartDetailInfo())
            return $this->getCartItemInDetail($sku, $lang, $platformId, $relaxCriteria, $ignoreListingStatus);
        else
            return $this->getCartItemInfoLite($sku, $lang, $platformId);
    }

    private function _getCommonCartParameter($sku, $lang, $platformId) {
        $where = ["pr.platform_id" => $platformId
                , "pc.lang_id" => $lang
                , "p.sku" => $sku
                , "p.status" => 2
                , "pr.listing_status" => "L"
                , "p.website_status in ('I', 'P')" => null];
        $options["limit"] = 1;
        return ["where" => $where, "options" => $options];
    }
/********************************
**  function _getRelaxCartParameter
**  this is only for speical order, we can remove more criteria if needed
*********************************/
    private function _getRelaxCartParameter($sku, $lang, $platformId, $ignoreListingStatus = false) {
        $para = $this->_getCommonCartParameter($sku, $lang, $platformId);
        unset($para["where"]["pc.lang_id"]);
        if ($ignoreListingStatus) {
            unset($para["where"]["pr.listing_status"]);
        }
        return $para;
    }

    public function getCartItemInDetail($sku, $lang, $platformId, $relaxCriteria = false, $ignoreListingStatus = false) {
        if ($relaxCriteria) {
            $para = $this->_getRelaxCartParameter($sku, $lang, $platformId, $ignoreListingStatus);
        } else {
            $para = $this->_getCommonCartParameter($sku, $lang, $platformId);
        }
        $productInfo = $this->getDao('Product')->getCartDataDetail($para["where"], $para["options"]);
/*
        print $this->getDao('Product')->db->last_query();
        var_dump($productInfo);
        exit;
*/
        if ($productInfo) {
            return $productInfo;
        }
        else
        {
//out of stock, or
            $subject = "[Panther] Adding product which is not valid to the cart " . $sku . ":" . $platformId . " " . __METHOD__ . __LINE__;
            $message = $this->getDao('Product')->db->last_query();
            mail($this->support_email, $subject, $message, "From: website@" . SITE_DOMAIN . "\r\n");
        }
        return false;
    }

    public function getCartItemInfoLite($sku, $lang, $platformId) {
        $para = $this->_getCommonCartParameter($sku, $lang, $platformId);
        $para["options"]["orderby"] = "pi.priority";

        $productInfo = $this->getDao('Product')->getCartDataLite($para["where"], $para["options"]);
//        print $this->getDao('Product')->db->last_query();
        if ($productInfo) {
            return $productInfo;
        }
        else
        {
//out of stock, or
            $subject = "[Panther] Adding product which is not valid to the cart, SKU:" . $sku . ", PlatformId:" . $platformId . " " . __METHOD__ . __LINE__;
            $message = $this->getDao('Product')->db->last_query();
            mail($this->support_email, $subject, $message, "From: website@" . SITE_DOMAIN . "\r\n");
        }
        return false;
    }

    private function _initPriceService($platformType = null) {
        if (is_null($platformType)) {
            $this->_priceService = new PriceService;
        } else if (is_null($this->_priceService)) {
            $classname = "ESG\Panther\Service\Price" . ucfirst(strtolower($platformType)) . "Service";
            $this->_priceService = new $classname($platformType);
        }
    }

    public function storeProfitInfoToDto(\CalculateProfitDto $calProfitDto, $platformId, $decPlace)
    {
        $calOriginal = true;
        if (!$this->_platformType) {
            if (defined('PLATFORM_TYPE'))
                $this->_platformType = PLATFORM_TYPE;
            else
                $this->_platformType = $this->getService("SellingPlatform")->getDao("SellingPlatform")->get(["selling_platform_id" => $platformId])->getType();
        }
/* calculate raw first, common to all, */
        $this->_initPriceService($this->_platformType);
        $unitSellingPrice = $calProfitDto->getUnitPrice();

        $json = $this->_priceService->getProfitMarginJson($platformId, $calProfitDto->getSku(), $unitSellingPrice);
        $jj = json_decode($json, true);
        $calProfitDto->setRawProfit(round($jj["get_profit"], $decPlace));
        $calProfitDto->setRawMargin(round($jj["get_margin"], $decPlace));
        if ($calOriginal) {
            $sellingPrice = ($calProfitDto->getTotalAmount()) / $calProfitDto->getQty();
            $json = $this->_priceService->getProfitMarginJson($platformId, $calProfitDto->getSku(), $sellingPrice);
            $jj = json_decode($json, true);
            $calProfitDto->setCost(round($jj["get_cost"], $decPlace));
            $calProfitDto->setProfit(round($jj["get_profit"], $decPlace));
            $calProfitDto->setMargin(round($jj["get_margin"], $decPlace));
        }
        return $calProfitDto;
    }

    //get the used promotion items
    public function initPromotionFactoryService($function)
    {   if($this->_cart){
            if($this->_cart->getPromotionCode() && sizeof($this->_cart->items) > 0){
                $promotionCart=$this->promotionFactoryModel->initPromotionFactoryService($this->_cart,$this->_cart->getPromotionCode(),$function);
                if($promotionCart){
                    $this->_cart=$promotionCart;
                }
            }
        }
    }
    public function getCartDetailInfo()
    {
        return $this->cartDetailInfo;
    }

    public function setCartDetailInfo($value)
    {
        $this->cartDetailInfo = $value;
    }

    public function getSoDao()
    {
        return $this->soDao;
    }

    public function setSoDao($value)
    {
        $this->soDao = $value;
    }
}
