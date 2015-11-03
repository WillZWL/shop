<?php
class ListingInfoDto
{
    private $platform_id;
    private $sku;
    private $prod_name;
    private $short_desc;
    private $image_ext;
    private $currency_id;
    private $rrp_price;
    private $price;
    private $qty;
    private $status;
    private $youtube_id_1;
    private $youtube_id_2;
    private $youtube_caption_1;
    private $youtube_caption_2;
    private $fixed_rrp;
    private $rrp_factor;
    private $warranty_in_month;
    private $delivery_scenarioid;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $productUrl;

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setShortDesc($short_desc)
    {
        $this->short_desc = $short_desc;
    }

    public function getImageExt()
    {
        return $this->image_ext;
    }

    public function setImageExt($image_ext)
    {
        $this->image_ext = $image_ext;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getRrpPrice()
    {
        return $this->rrp_price;
    }

    public function setRrpPrice($rrp_price)
    {
        $this->rrp_price = $rrp_price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getYoutubeId1()
    {
        return $this->youtube_id_1;
    }

    public function setYoutubeId1($youtube_id_1)
    {
        $this->youtube_id_1 = $youtube_id_1;
    }

    public function getYoutubeId2()
    {
        return $this->youtube_id_2;
    }

    public function setYoutubeId2($youtube_id_2)
    {
        $this->youtube_id_2 = $youtube_id_2;
    }

    public function getYoutubeCaption1()
    {
        return $this->youtube_caption_1;
    }

    public function setYoutubeCaption1($youtube_caption_1)
    {
        $this->youtube_caption_1 = $youtube_caption_1;
    }

    public function getYoutubeCaption2()
    {
        return $this->youtube_caption_2;
    }

    public function setYoutubeCaption2($youtube_caption_2)
    {
        $this->youtube_caption_2 = $youtube_caption_2;
    }

    public function getFixedRrp()
    {
        return $this->fixed_rrp;
    }

    public function setFixedRrp($fixed_rrp)
    {
        $this->fixed_rrp = $fixed_rrp;
    }

    public function getRrpFactor()
    {
        return $this->rrp_factor;
    }

    public function setRrpFactor($rrp_factor)
    {
        $this->rrp_factor = $rrp_factor;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        $this->warranty_in_month = $warranty_in_month;
    }

    public function getDeliveryScenarioid()
    {
        return $this->delivery_scenarioid;
    }

    public function setDeliveryScenarioid($delivery_scenarioid)
    {
        $this->delivery_scenarioid = $delivery_scenarioid;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatId($sub_cat_id)
    {
        $this->sub_cat_id = $sub_cat_id;
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setSubSubCatId($sub_sub_cat_id)
    {
        $this->sub_sub_cat_id = $sub_sub_cat_id;
    }

    public function getProductUrl()
    {
        return $this->productUrl;
    }

    public function setProductUrl($product_url)
    {
        $this->productUrl = $product_url;
    }
}
