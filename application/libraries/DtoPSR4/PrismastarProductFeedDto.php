<?php
class PrismastarProductFeedDto
{
    private $platform_id;
    private $sku;
    private $prod_grp_cd;
    private $version_id;
    private $colour_id;
    private $colour_name;
    private $platform_country_id;
    private $language_id;
    private $prod_name;
    private $cat_id;
    private $cat_name;
    private $sub_cat_id;
    private $sub_cat_name;
    private $brand_id;
    private $brand_name;
    private $mpn;
    private $upc;
    private $ean;
    private $short_desc;
    private $detail_desc;
    private $contents;
    private $feature;
    private $prod_weight;
    private $image;
    private $platform_currency_id;
    private $price;
    private $default_platform_converted_price;
    private $quantity;
    private $display_quantity;
    private $website_quantity;
    private $website_status;
    private $availability;
    private $prod_status;
    private $listing_status;
    private $ex_demo;
    private $google_ref_id;
    private $product_type;
    private $google_product_category;
    private $product_url;
    private $image_url;
    private $condition;
    private $price_w_curr;
    private $sale_price;
    private $item_group_id;
    private $shipping;
    private $video_1;
    private $video_2;

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProdGrpCd($prod_grp_cd)
    {
        $this->prod_grp_cd = $prod_grp_cd;
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
    }

    public function setVersionId($version_id)
    {
        $this->version_id = $version_id;
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setColourId($colour_id)
    {
        $this->colour_id = $colour_id;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourName($colour_name)
    {
        $this->colour_name = $colour_name;
    }

    public function getColourName()
    {
        return $this->colour_name;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setSubCatId($sub_cat_id)
    {
        $this->sub_cat_id = $sub_cat_id;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatName($sub_cat_name)
    {
        $this->sub_cat_name = $sub_cat_name;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setShortDesc($short_desc)
    {
        $this->short_desc = $short_desc;
    }

    public function getShortDesc()
    {
        return $this->short_desc;
    }

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    public function getFeature()
    {
        return $this->feature;
    }

    public function setProdWeight($prod_weight)
    {
        $this->prod_weight = $prod_weight;
    }

    public function getProdWeight()
    {
        return $this->prod_weight;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setPlatformCurrencyId($platform_currency_id)
    {
        $this->platform_currency_id = $platform_currency_id;
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setDefaultPlatformConvertedPrice($default_platform_converted_price)
    {
        $this->default_platform_converted_price = $default_platform_converted_price;
    }

    public function getDefaultPlatformConvertedPrice()
    {
        return $this->default_platform_converted_price;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setDisplayQuantity($display_quantity)
    {
        $this->display_quantity = $display_quantity;
    }

    public function getDisplayQuantity()
    {
        return $this->display_quantity;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setProdStatus($prod_status)
    {
        $this->prod_status = $prod_status;
    }

    public function getProdStatus()
    {
        return $this->prod_status;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setExDemo($ex_demo)
    {
        $this->ex_demo = $ex_demo;
    }

    public function getExDemo()
    {
        return $this->ex_demo;
    }

    public function setGoogleRefId($google_ref_id)
    {
        $this->google_ref_id = $google_ref_id;
    }

    public function getGoogleRefId()
    {
        return $this->google_ref_id;
    }

    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
    }

    public function getProductType()
    {
        return $this->product_type;
    }

    public function setGoogleProductCategory($google_product_category)
    {
        $this->google_product_category = $google_product_category;
    }

    public function getGoogleProductCategory()
    {
        return $this->google_product_category;
    }

    public function setProductUrl($product_url)
    {
        $this->product_url = $product_url;
    }

    public function getProductUrl()
    {
        return $this->product_url;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setPriceWCurr($price_w_curr)
    {
        $this->price_w_curr = $price_w_curr;
    }

    public function getPriceWCurr()
    {
        return $this->price_w_curr;
    }

    public function setSalePrice($sale_price)
    {
        $this->sale_price = $sale_price;
    }

    public function getSalePrice()
    {
        return $this->sale_price;
    }

    public function setItemGroupId($item_group_id)
    {
        $this->item_group_id = $item_group_id;
    }

    public function getItemGroupId()
    {
        return $this->item_group_id;
    }

    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setVideo1($video_1)
    {
        $this->video_1 = $video_1;
    }

    public function getVideo1()
    {
        return $this->video_1;
    }

    public function setVideo2($video_2)
    {
        $this->video_2 = $video_2;
    }

    public function getVideo2()
    {
        return $this->video_2;
    }

}
