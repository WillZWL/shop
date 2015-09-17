<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\ProductService;

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

    private $_cart = null;
    public $support_email = "oswald-alert@eservicesgroup.com";

    public function __construct() {
        parent::__construct();
        $this->productService = new ProductService;
//unset($_SESSION["cart"]);
        if (isset($_SESSION["cart"])) {
/*
            if ($_SESSION["cart"] instanceof \CartDto) {
                if (PLATFORMID != $_SESSION["cart"]->getPlatformId())
                {
                    $this->reBuildCart(PLATFORMID, $_SESSION["cart"]);
                }
            }
*/
            $this->_cart = unserialize($_SESSION["cart"]);
        }
    }

    public function __destruct () {
        if ($this->_cart) {
            $_SESSION["cart"] = serialize($this->_cart);
        }
    }

    public function getCart() {
        $totalItems = 0;
        $totalAmount = 0.0;
        if ($this->_cart)
        {
            foreach ($this->_cart->items as $sku => $item) {
                $unitPrice = $item->getPrice();
                $itemSubTotal = $unitPrice * $item->getQty();
                $this->_cart->items[$sku]->setAmount($itemSubTotal);
                $totalItems += $item->getQty();
                $totalAmount += $itemSubTotal;
            }
            $this->_cart->setSubtotal($totalAmount);
            if ($this->_cart)
            {
                $this->_cart->setTotalNumberOfItems($totalItems);
            }
        }
        $_SESSION["CART_QUICK_INFO"]["TOTAL_NUMBER_OF_ITEMS"] = $totalItems;
        $_SESSION["CART_QUICK_INFO"]["TOTAL_AMOUNT"] = $totalAmount;
        return $this->_cart;    //=return $_SESSION["cart"]
    }
/*
    public function isAllowToAdd($sku, $qty, $platform)
    {
        $product_obj = $this->productService->getDao()->get(["sku" => $sku]);
        if (empty($product_obj)) {
            return self::UNKNOWN_ITEM_STATUS;
        }

        $website_status = $product_obj->getWebsiteStatus();
        if (count($_SESSION["cart"][$platform]) === 0) {
            if ($website_status === 'I') {
                return self::ALLOW_AND_IS_NORMAL_ITEM;
            } elseif ($website_status === 'P') {
                return self::ALLOW_AND_IS_PREORDER;
            } elseif ($website_status === 'A') {
                return self::ALLOW_AND_IS_ARRIVING;
            }
            return self::UNKNOWN_ITEM_STATUS;
        } else {
            if (isset($_SESSION['cart'][$platform][$sku])) {
                if ($website_status === "P") {
                    return self::SAME_PREORDER_ITEM;
                } elseif ($website_status === "A") {
                    return self::SAME_ARRIVING_ITEM;
                } elseif ($website_status === "I") {
                    return self::SAME_NORMAL_ITEM;
                }

                foreach ($_SESSION["cart"][$platform] as $key => $value) {
                    $stored_website_status = $value["website_status"];
                    if (($stored_website_status == "I") && (($website_status == "P") || ($website_status == "A")) ) {
                        return self::NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM;
                    } elseif ((($stored_website_status == "P") || ($stored_website_status == "A")) && ($website_status == "I") ) {
                        return self::NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM;
                    } elseif ((($stored_website_status == "P") && ($website_status == "P")) || (($stored_website_status == "P") && ($website_status == "A")) ) {
                        return self::DIFFERENT_PREORDER_ITEM;
                    } elseif ((($stored_website_status == "A") && ($website_status == "A")) || (($stored_website_status == "A") && ($website_status == "P")) ) {
                        return self::DIFFERENT_ARRIVING_ITEM;
                    }
                }
            }

            return self::ALLOW_AND_IS_NORMAL_ITEM;
        }
    }
*/
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
        if (sizeof($this->_cart->items) == 0)
        {
            $this->emptyCart();
        }
    }

    public function addItemToSession($sku, $qty, $lang, $platformId, $currencyId) {
        if (!$this->_cart) {
            $this->_cart = [];
            $this->_cart = new \CartDto();
            $this->_cart->setPlatformId($platformId);
            $this->_cart->setCurrency($currencyId);
            $this->_cart->items = [];
        }
        if ($productDetails = $this->_createCartItem($sku, $lang, $platformId)) {
            if (isset($productDetails) && $productDetails) {
                $productDetails->setQty($qty);
//                $productDetails->setAmount($qty * $productDetails->getPrice());
                $this->_cart->items[$sku] = $productDetails;
            }
        }
    }

    private function _createCartItem($sku, $lang, $platformId) {
        $where = ["pr.platform_id" => $platformId
                , "pc.lang_id" => $lang
                , "p.sku" => $sku
                , "p.status" => 2
                , "pr.listing_status" => "L"
                , "p.website_status in ('I', 'P')" => null];
        $options = ["orderby" => "pi.priority"
                    , "limit" => 1];
        $productInfo = $this->productService->getDao()->getCartData($where, $options);
//        print $this->productService->getDao()->db->last_query();
        if ($productInfo) {
            return $productInfo;
        }
        else
        {
//out of stock, or
            $subject = "[Panther] Adding product which is not valid to the cart " . $sku . ":" . $platformId;
            $message = $this->productService->getDao()->db->last_query();
            mail($this->support_email, $subject, $message, "From: website@" . SITE_DOMAIN . "\r\n");
        }
        return false;
    }
}
