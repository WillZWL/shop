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

    public function __construct()
    {
        parent::__construct();
        $this->product_service = new ProductService;
        // $this->setDao(new ProductDao);
    }

    public function isAllowToAdd($sku, $qty, $platform)
    {
        $product_obj = $this->product_service->getDao()->get(["sku" => $sku]);
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

    public function addItemQty($sku, $qty, $platform)
    {
        $where = [
            'vpi.prod_sku' => $sku,
            'vpo.platform_id' => $platform,
            'p.status' => 2,
            'p.website_status <>' => 'O'
        ];

        $prod_obj = $this->product_service->getDao()->getProductOverview($where);
        if (empty($prod_obj)) {
            return false;
        }

        $website_qty = $prod_obj->getWebsiteQuantity();
        $website_status = $prod_obj->getWebsiteStatus();
        $status = $prod_obj->getProdStatus();
        $listing_status = $prod_obj->getListingStatus();
        $price = $prod_obj->getPrice();
        $expect_delivery_date = $prod_obj->getExpectedDeliveryDate();
        $warranty_in_month = $prod_obj->getWarrantyInMonth();
        $qty = ($qty > $website_qty) ? $website_qty : $qty;

        // TODO
        // should write logic in isAllowToAdd()
        if (! ($status == '2' && $listing_status == 'L' && $website_qty > 0 && $price > 0)) {
            $this->remove($sku, $platform);
            return false;
        }

        $this->putCartSku($platform, $sku, $qty, $website_status, $expect_delivery_date, $warranty_in_month);
        $this->setCartCookie($platform);

        return true;
    }

    public function updateItemQty($sku, $qty, $platform)
    {

    }

    public function putCartSku($platform, $sku, $qty, $website_status, $expect_delivery_date, $warranty_in_month)
    {
        $_SESSION['cart'][$platform][$sku] = $this->cartFormat($qty, $website_status, $expect_delivery_date, $warranty_in_month);
    }

    public function cartFormat($qty, $website_status, $expect_delivery_date, $warranty_in_month)
    {
        return [
            'qty' => $qty,
            'website_status' => $website_status,
            'expect_delivery_date' => $expect_delivery_date,
            'warranty_in_month' => $warranty_in_month
        ];
    }

    public function setCartCookie($platform)
    {
        setcookie('chk_cart', base64_encode(serialize($_SESSION['cart'][$platform])), time() + 86400, '/', SITE_DOMAIN);
    }
}
