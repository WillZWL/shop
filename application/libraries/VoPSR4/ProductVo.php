<?php
class ProductVo extends \BaseVo
{
    private $id;
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
    private $warranty_in_month;
    private $expected_delivery_date;
    private $lang_restricted;
    private $cat_upselling;

    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    private $primary_key = array("id");

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
    }

    public function setProdGrpCd($prod_grp_cd)
    {
        $this->prod_grp_cd = $prod_grp_cd;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourId($colour_id)
    {
        $this->colour_id = $colour_id;
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setVersionId($version_id)
    {
        $this->version_id = $version_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFreightCatId()
    {
        return $this->freight_cat_id;
    }

    public function setFreightCatId($freight_cat_id)
    {
        $this->freight_cat_id = $freight_cat_id;
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

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getDisplayQuantity()
    {
        return $this->display_quantity;
    }

    public function setDisplayQuantity($display_quantity)
    {
        $this->display_quantity = $display_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getExDemo()
    {
        return $this->ex_demo;
    }

    public function setExDemo($ex_demo)
    {
        $this->ex_demo = $ex_demo;
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setRrp($rrp)
    {
        $this->rrp = $rrp;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getFlash()
    {
        return $this->flash;
    }

    public function setFlash($flash)
    {
        $this->flash = $flash;
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    public function setYoutubeId($youtube_id)
    {
        $this->youtube_id = $youtube_id;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getProcStatus()
    {
        return $this->proc_status;
    }

    public function setProcStatus($proc_status)
    {
        $this->proc_status = $proc_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setSourcingStatus($sourcing_status)
    {
        $this->sourcing_status = $sourcing_status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        $this->warranty_in_month = $warranty_in_month;
    }

    public function getExpectedDeliveryDate()
    {
        return $this->expected_delivery_date;
    }

    public function setExpectedDeliveryDate($expected_delivery_date)
    {
        $this->expected_delivery_date = $expected_delivery_date;
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setLangRestricted($lang_restricted)
    {
        $this->lang_restricted = $lang_restricted;
    }

    public function getCatUpselling()
    {
        return $this->cat_upselling;
    }

    public function setCatUpselling($cat_upselling)
    {
        $this->cat_upselling = $cat_upselling;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }
}
