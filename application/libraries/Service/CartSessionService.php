<?php
namespace AtomV2\Service;

use AtomV2\Service\ProductService;

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

    public function __construct()
    {
        $this->productService = new ProductService;
        // $this->setDao(new ProductDao);
    }

    public function isAllowToAdd($sku, $qty, $platform)
    {
        $productObj = $this->productService->getDao()->get(["sku" => $sku]);
        if (empty($productObj)) {
            return self::UNKNOWN_ITEM_STATUS;
        }

        // product.website_status 0 = Outstock / 1 = Instock / 2 = Pre-Order / 3 = Arriving
        $websiteStatus = $productObj->getWebsiteStatus();
        if (count($_SESSION["cart"][$platform]) === 0) {
            if ($websiteStatus === '1') {
                return self::ALLOW_AND_IS_NORMAL_ITEM;
            } elseif ($websiteStatus === '2') {
                return self::ALLOW_AND_IS_PREORDER;
            } elseif ($websiteStatus === '3') {
                return self::ALLOW_AND_IS_ARRIVING;
            }

            return self::UNKNOWN_ITEM_STATUS;
        } else {
            if (isset($_SESSION['cart'][$platform][$sku])) {
                if ($websiteStatus === "2") {
                    return self::SAME_PREORDER_ITEM;
                } elseif ($websiteStatus === "3") {
                    return self::SAME_ARRIVING_ITEM;
                } elseif ($websiteStatus === "1") {
                    return self::SAME_NORMAL_ITEM;
                }

                foreach ($_SESSION["cart"][$platform] as $key => $value) {
                    $storedWebsiteStatus = $value["website_status"];
                    if (($storedWebsiteStatus === "1") && (($websiteStatus === "2") || ($websiteStatus === "3")) ) {
                        return self::NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM;
                    } elseif ((($storedWebsiteStatus === "2") || ($storedWebsiteStatus === "3")) && ($websiteStatus === "1") ) {
                        return self::NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM;
                    } elseif ((($storedWebsiteStatus === "2") && ($websiteStatus === "2")) || (($storedWebsiteStatus === "2") && ($websiteStatus === "3")) ) {
                        return self::DIFFERENT_PREORDER_ITEM;
                    } elseif ((($storedWebsiteStatus === "3") && ($websiteStatus === "3")) || (($storedWebsiteStatus === "3") && ($websiteStatus === "2")) ) {
                        return self::DIFFERENT_ARRIVING_ITEM;
                    }
                }
            }

            return self::ALLOW_AND_IS_NORMAL_ITEM;
        }
    }

    public function addItemQty($sku, $qty, $platform)
    {
        $where = [
            'pd.status' => 2,
            'pd.website_status <>' => 'O'
        ];

        $option = ['limit' => 1];

        $prodInfo = $this->productService->getDao()->getProductInfo($where, $option);

        if (empty($prodInfo)) {
            return false;
        }

        $websiteQty = $prodInfo->getWebsiteQuantity();
        $websiteStatus = $prodInfo->getWebsiteStatus();
        $status = $prodInfo->getStatus();
        $listingStatus = $prodInfo->getListingStatus();
        $price = $prodInfo->getPrice();
        $expectDeliveryDate = $prodInfo->getExpectedDeliveryDate();
        $warrantyInMonth = $prodInfo->getWarrantyInMonth();
        $qty = ($qty > $websiteQty) ? $websiteQty : $qty;

        // TODO
        // should write logic in isAllowToAdd()
        if (! ($status === '2' && $listingStatus === '1' && $websiteQty > 0 && $price > 0)) {
            $this->remove($sku, $platform);
            return false;
        }

        $this->putCartSku($platform, $sku, $qty, $websiteStatus, $expectDeliveryDate, $warrantyInMonth);
        $this->setCartCookie($platform);

        return true;
    }

    public function updateItemQty($sku, $qty, $platform)
    {
        //
    }

    public function putCartSku($platform, $sku, $qty, $websiteStatus, $expectDeliveryDate, $warrantyInMonth)
    {
        $_SESSION['cart'][$platform][$sku] = $this->cartFormat($qty, $websiteStatus, $expectDeliveryDate, $warrantyInMonth);
    }

    public function cartFormat($qty, $websiteStatus, $expectDeliveryDate, $warrantyInMonth)
    {
        return [
            'qty' => $qty,
            'website_status' => $websiteStatus,
            'expect_delivery_date' => $expectDeliveryDate,
            'warranty_in_month' => $warrantyInMonth
        ];
    }

    public function setCartCookie($platform)
    {
        setcookie('chk_cart', base64_encode(serialize($_SESSION['cart'][$platform])), time() + 86400, '/', SITE_DOMAIN);
    }

    public function getCartInfo()
    {
        //
        // var_dump($_SESSION);
        // var_dump(PLATFORM);
        // var_dump(count($_SESSION['cart'][PLATFORM]));die;
        if (count($_SESSION['cart'][PLATFORM])) {
            foreach ($_SESSION['cart'][PLATFORM] as $sku => $info) {
                $prodInfo = $this->productService->getProductInfo(['pd.sku' => $sku], ['limit' => 1]);
                $ret[] = [
                    "sku" => $sku,
                    "image" => $prodInfo->getImage(),
                    "qty" => $info[$qty],
                    "name" => $prodInfo->getName(),
                    "price" => $prodInfo->getPrice(),
                    "total" => $prodInfo->getPrice() * $info[$qty]
                ];
            }

            return $ret;
        }
    }
}
