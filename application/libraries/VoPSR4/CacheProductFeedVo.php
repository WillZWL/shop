<?php

class CacheProductFeedVo extends \BaseVo
{
    private $sku;
    private $platform_id;
    private $prod_name;
    private $prod_url;
    private $currency_id;
    private $price = '0.00';
    private $promotion_price = '0.00';
    private $bundle_price = '0.00';
    private $shipping_cost = '0.00';
    private $promo_text;
    private $listing_status = 'N';
    private $expiry_time = '0000-00-00 00:00:00';

    protected $primary_key = ['sku', 'platform_id'];
    protected $increment_field = '';

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setProdName($prod_name)
    {
        if ($prod_name !== null) {
            $this->prod_name = $prod_name;
        }
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdUrl($prod_url)
    {
        if ($prod_url !== null) {
            $this->prod_url = $prod_url;
        }
    }

    public function getProdUrl()
    {
        return $this->prod_url;
    }

    public function setCurrencyId($currency_id)
    {
        if ($currency_id !== null) {
            $this->currency_id = $currency_id;
        }
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setPrice($price)
    {
        if ($price !== null) {
            $this->price = $price;
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPromotionPrice($promotion_price)
    {
        if ($promotion_price !== null) {
            $this->promotion_price = $promotion_price;
        }
    }

    public function getPromotionPrice()
    {
        return $this->promotion_price;
    }

    public function setBundlePrice($bundle_price)
    {
        if ($bundle_price !== null) {
            $this->bundle_price = $bundle_price;
        }
    }

    public function getBundlePrice()
    {
        return $this->bundle_price;
    }

    public function setShippingCost($shipping_cost)
    {
        if ($shipping_cost !== null) {
            $this->shipping_cost = $shipping_cost;
        }
    }

    public function getShippingCost()
    {
        return $this->shipping_cost;
    }

    public function setPromoText($promo_text)
    {
        if ($promo_text !== null) {
            $this->promo_text = $promo_text;
        }
    }

    public function getPromoText()
    {
        return $this->promo_text;
    }

    public function setListingStatus($listing_status)
    {
        if ($listing_status !== null) {
            $this->listing_status = $listing_status;
        }
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setExpiryTime($expiry_time)
    {
        if ($expiry_time !== null) {
            $this->expiry_time = $expiry_time;
        }
    }

    public function getExpiryTime()
    {
        return $this->expiry_time;
    }

}
