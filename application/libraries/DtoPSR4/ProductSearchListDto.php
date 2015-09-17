<?php
class ProductSearchListDto
{
    private $sku;
    private $prod_grp_cd;
    private $colour_id;
    private $version_id;
    private $name;
    private $freight_cat_id;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_id;
    private $clearance;
    private $quantity;
    private $display_quantity;
    private $website_quantity;
    private $ex_demo;
    private $rrp;
    private $image;
    private $flash;
    private $youtube_id;
    private $ean;
    private $mpn;
    private $upc;
    private $discount;
    private $proc_status;
    private $website_status;
    private $sourcing_status;
    private $status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;
    private $prod_name;
    private $cat_name;
    private $brand_name;
    private $short_desc;
    private $detail_desc;
    private $price;
    private $sign;
    private $sign_pos;
    private $dec_place;
    private $dec_point;
    private $thousands_sep;
    private $sold_amount;
    private $num;
    private $price_range;
    private $with_bundle;

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

    public function setColourId($colour_id)
    {
        $this->colour_id = $colour_id;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setVersionId($version_id)
    {
        $this->version_id = $version_id;
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFreightCatId($freight_cat_id)
    {
        $this->freight_cat_id = $freight_cat_id;
    }

    public function getFreightCatId()
    {
        return $this->freight_cat_id;
    }

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setSubCatId($sub_cat_id)
    {
        $this->sub_cat_id = $sub_cat_id;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubSubCatId($sub_sub_cat_id)
    {
        $this->sub_sub_cat_id = $sub_sub_cat_id;
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getClearance()
    {
        return $this->clearance;
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

    public function setExDemo($ex_demo)
    {
        $this->ex_demo = $ex_demo;
    }

    public function getExDemo()
    {
        return $this->ex_demo;
    }

    public function setRrp($rrp)
    {
        $this->rrp = $rrp;
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setFlash($flash)
    {
        $this->flash = $flash;
    }

    public function getFlash()
    {
        return $this->flash;
    }

    public function setYoutubeId($youtube_id)
    {
        $this->youtube_id = $youtube_id;
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getEan()
    {
        return $this->ean;
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

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setProcStatus($proc_status)
    {
        $this->proc_status = $proc_status;
    }

    public function getProcStatus()
    {
        return $this->proc_status;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setSourcingStatus($sourcing_status)
    {
        $this->sourcing_status = $sourcing_status;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getBrandName()
    {
        return $this->brand_name;
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

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function setSignPos($sign_pos)
    {
        $this->sign_pos = $sign_pos;
    }

    public function getSignPos()
    {
        return $this->sign_pos;
    }

    public function setDecPlace($dec_place)
    {
        $this->dec_place = $dec_place;
    }

    public function getDecPlace()
    {
        return $this->dec_place;
    }

    public function setDecPoint($dec_point)
    {
        $this->dec_point = $dec_point;
    }

    public function getDecPoint()
    {
        return $this->dec_point;
    }

    public function setThousandsSep($thousands_sep)
    {
        $this->thousands_sep = $thousands_sep;
    }

    public function getThousandsSep()
    {
        return $this->thousands_sep;
    }

    public function setSoldAmount($sold_amount)
    {
        $this->sold_amount = $sold_amount;
    }

    public function getSoldAmount()
    {
        return $this->sold_amount;
    }

    public function setNum($num)
    {
        $this->num = $num;
    }

    public function getNum()
    {
        return $this->num;
    }

    public function setPriceRange($price_range)
    {
        $this->price_range = $price_range;
    }

    public function getPriceRange()
    {
        return $this->price_range;
    }

    public function setWithBundle($with_bundle)
    {
        $this->with_bundle = $with_bundle;
    }

    public function getWithBundle()
    {
        return $this->with_bundle;
    }

}
