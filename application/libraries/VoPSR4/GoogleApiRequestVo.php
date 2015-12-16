<?php
class GoogleApiRequestVo extends \BaseVo
{
    private $id;
    private $request_batch_id;
    private $platform_id;
    private $sku;
    private $item_group_id;
    private $google_product_id;
    private $colour_id = '';
    private $colour_name = '';
    private $target_country;
    private $content_language;
    private $title;
    private $google_product_category = '';
    private $product_type;
    private $cat_id;
    private $cat_name = '';
    private $brand_name;
    private $gtin = '';
    private $upc = '';
    private $mpn = '';
    private $ean = '';
    private $shipping_weight_value;
    private $image_link;
    private $link;
    private $currency;
    private $price;
    private $description;
    private $google_promo_id = '';
    private $google_product_status;
    private $custom_attribute_promo_id = '';
    private $ref_website_quantity = '0';
    private $ref_display_quantity = '0';
    private $ref_listing_status;
    private $ref_website_status = '';
    private $ref_exdemo;
    private $availability = '';
    private $condition = '';
    private $result = 'N';
    private $api_response;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRequestBatchId($request_batch_id)
    {
        if ($request_batch_id !== null) {
            $this->request_batch_id = $request_batch_id;
        }
    }

    public function getRequestBatchId()
    {
        return $this->request_batch_id;
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

    public function setItemGroupId($item_group_id)
    {
        if ($item_group_id !== null) {
            $this->item_group_id = $item_group_id;
        }
    }

    public function getItemGroupId()
    {
        return $this->item_group_id;
    }

    public function setGoogleProductId($google_product_id)
    {
        if ($google_product_id !== null) {
            $this->google_product_id = $google_product_id;
        }
    }

    public function getGoogleProductId()
    {
        return $this->google_product_id;
    }

    public function setColourId($colour_id)
    {
        if ($colour_id !== null) {
            $this->colour_id = $colour_id;
        }
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourName($colour_name)
    {
        if ($colour_name !== null) {
            $this->colour_name = $colour_name;
        }
    }

    public function getColourName()
    {
        return $this->colour_name;
    }

    public function setTargetCountry($target_country)
    {
        if ($target_country !== null) {
            $this->target_country = $target_country;
        }
    }

    public function getTargetCountry()
    {
        return $this->target_country;
    }

    public function setContentLanguage($content_language)
    {
        if ($content_language !== null) {
            $this->content_language = $content_language;
        }
    }

    public function getContentLanguage()
    {
        return $this->content_language;
    }

    public function setTitle($title)
    {
        if ($title !== null) {
            $this->title = $title;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setGoogleProductCategory($google_product_category)
    {
        if ($google_product_category !== null) {
            $this->google_product_category = $google_product_category;
        }
    }

    public function getGoogleProductCategory()
    {
        return $this->google_product_category;
    }

    public function setProductType($product_type)
    {
        if ($product_type !== null) {
            $this->product_type = $product_type;
        }
    }

    public function getProductType()
    {
        return $this->product_type;
    }

    public function setCatId($cat_id)
    {
        if ($cat_id !== null) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatName($cat_name)
    {
        if ($cat_name !== null) {
            $this->cat_name = $cat_name;
        }
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setBrandName($brand_name)
    {
        if ($brand_name !== null) {
            $this->brand_name = $brand_name;
        }
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setGtin($gtin)
    {
        if ($gtin !== null) {
            $this->gtin = $gtin;
        }
    }

    public function getGtin()
    {
        return $this->gtin;
    }

    public function setUpc($upc)
    {
        if ($upc !== null) {
            $this->upc = $upc;
        }
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setMpn($mpn)
    {
        if ($mpn !== null) {
            $this->mpn = $mpn;
        }
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setEan($ean)
    {
        if ($ean !== null) {
            $this->ean = $ean;
        }
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setShippingWeightValue($shipping_weight_value)
    {
        if ($shipping_weight_value !== null) {
            $this->shipping_weight_value = $shipping_weight_value;
        }
    }

    public function getShippingWeightValue()
    {
        return $this->shipping_weight_value;
    }

    public function setImageLink($image_link)
    {
        if ($image_link !== null) {
            $this->image_link = $image_link;
        }
    }

    public function getImageLink()
    {
        return $this->image_link;
    }

    public function setLink($link)
    {
        if ($link !== null) {
            $this->link = $link;
        }
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setCurrency($currency)
    {
        if ($currency !== null) {
            $this->currency = $currency;
        }
    }

    public function getCurrency()
    {
        return $this->currency;
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

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setGooglePromoId($google_promo_id)
    {
        if ($google_promo_id !== null) {
            $this->google_promo_id = $google_promo_id;
        }
    }

    public function getGooglePromoId()
    {
        return $this->google_promo_id;
    }

    public function setGoogleProductStatus($google_product_status)
    {
        if ($google_product_status !== null) {
            $this->google_product_status = $google_product_status;
        }
    }

    public function getGoogleProductStatus()
    {
        return $this->google_product_status;
    }

    public function setCustomAttributePromoId($custom_attribute_promo_id)
    {
        if ($custom_attribute_promo_id !== null) {
            $this->custom_attribute_promo_id = $custom_attribute_promo_id;
        }
    }

    public function getCustomAttributePromoId()
    {
        return $this->custom_attribute_promo_id;
    }

    public function setRefWebsiteQuantity($ref_website_quantity)
    {
        if ($ref_website_quantity !== null) {
            $this->ref_website_quantity = $ref_website_quantity;
        }
    }

    public function getRefWebsiteQuantity()
    {
        return $this->ref_website_quantity;
    }

    public function setRefDisplayQuantity($ref_display_quantity)
    {
        if ($ref_display_quantity !== null) {
            $this->ref_display_quantity = $ref_display_quantity;
        }
    }

    public function getRefDisplayQuantity()
    {
        return $this->ref_display_quantity;
    }

    public function setRefListingStatus($ref_listing_status)
    {
        if ($ref_listing_status !== null) {
            $this->ref_listing_status = $ref_listing_status;
        }
    }

    public function getRefListingStatus()
    {
        return $this->ref_listing_status;
    }

    public function setRefWebsiteStatus($ref_website_status)
    {
        if ($ref_website_status !== null) {
            $this->ref_website_status = $ref_website_status;
        }
    }

    public function getRefWebsiteStatus()
    {
        return $this->ref_website_status;
    }

    public function setRefExdemo($ref_exdemo)
    {
        if ($ref_exdemo !== null) {
            $this->ref_exdemo = $ref_exdemo;
        }
    }

    public function getRefExdemo()
    {
        return $this->ref_exdemo;
    }

    public function setAvailability($availability)
    {
        if ($availability !== null) {
            $this->availability = $availability;
        }
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setCondition($condition)
    {
        if ($condition !== null) {
            $this->condition = $condition;
        }
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setResult($result)
    {
        if ($result !== null) {
            $this->result = $result;
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setApiResponse($api_response)
    {
        if ($api_response !== null) {
            $this->api_response = $api_response;
        }
    }

    public function getApiResponse()
    {
        return $this->api_response;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
