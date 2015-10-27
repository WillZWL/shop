<?php
class ProductVo extends \BaseVo
{
    private $id;
    private $sku;
    private $sku_vb = '';
    private $prod_grp_cd;
    private $colour_id;
    private $version_id;
    private $name;
    private $freight_cat_id;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id = '0';
    private $brand_id = '0';
    private $clearance = '0';
    private $surplus_quantity = '0';
    private $slow_move_7_days = '0';
    private $quantity = '0';
    private $display_quantity = '0';
    private $website_quantity = '0';
    private $ex_demo = '0';
    private $china_oem = '0';
    private $rrp = '0.00';
    private $image = '';
    private $flash = '';
    private $youtube_id = '';
    private $ean = '';
    private $mpn = '';
    private $upc = '';
    private $discount = '0.00';
    private $proc_status = '0';
    private $website_status = 'I';
    private $sourcing_status = 'A';
    private $expected_delivery_date = '0000-00-00 00:00:00';
    private $warranty_in_month = '0';
    private $cat_upselling = '0';
    private $lang_restricted = '1';
    private $shipment_restricted_type = '0';
    private $status = '1';
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
        if ($id) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSkuVb($sku_vb)
    {
        if ($sku_vb) {
            $this->sku_vb = $sku_vb;
        }
    }

    public function getSkuVb()
    {
        return $this->sku_vb;
    }

    public function setProdGrpCd($prod_grp_cd)
    {
        if ($prod_grp_cd) {
            $this->prod_grp_cd = $prod_grp_cd;
        }
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
    }

    public function setColourId($colour_id)
    {
        if ($colour_id) {
            $this->colour_id = $colour_id;
        }
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setVersionId($version_id)
    {
        if ($version_id) {
            $this->version_id = $version_id;
        }
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setName($name)
    {
        if ($name) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFreightCatId($freight_cat_id)
    {
        if ($freight_cat_id) {
            $this->freight_cat_id = $freight_cat_id;
        }
    }

    public function getFreightCatId()
    {
        return $this->freight_cat_id;
    }

    public function setCatId($cat_id)
    {
        if ($cat_id) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setSubCatId($sub_cat_id)
    {
        if ($sub_cat_id) {
            $this->sub_cat_id = $sub_cat_id;
        }
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubSubCatId($sub_sub_cat_id)
    {
        if ($sub_sub_cat_id) {
            $this->sub_sub_cat_id = $sub_sub_cat_id;
        }
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setBrandId($brand_id)
    {
        if ($brand_id) {
            $this->brand_id = $brand_id;
        }
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setClearance($clearance)
    {
        if ($clearance) {
            $this->clearance = $clearance;
        }
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setSurplusQuantity($surplus_quantity)
    {
        if ($surplus_quantity) {
            $this->surplus_quantity = $surplus_quantity;
        }
    }

    public function getSurplusQuantity()
    {
        return $this->surplus_quantity;
    }

    public function setSlowMove7Days($slow_move_7_days)
    {
        if ($slow_move_7_days) {
            $this->slow_move_7_days = $slow_move_7_days;
        }
    }

    public function getSlowMove7Days()
    {
        return $this->slow_move_7_days;
    }

    public function setQuantity($quantity)
    {
        if ($quantity) {
            $this->quantity = $quantity;
        }
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setDisplayQuantity($display_quantity)
    {
        if ($display_quantity) {
            $this->display_quantity = $display_quantity;
        }
    }

    public function getDisplayQuantity()
    {
        return $this->display_quantity;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        if ($website_quantity) {
            $this->website_quantity = $website_quantity;
        }
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setExDemo($ex_demo)
    {
        if ($ex_demo) {
            $this->ex_demo = $ex_demo;
        }
    }

    public function getExDemo()
    {
        return $this->ex_demo;
    }

    public function setChinaOem($china_oem)
    {
        if ($china_oem) {
            $this->china_oem = $china_oem;
        }
    }

    public function getChinaOem()
    {
        return $this->china_oem;
    }

    public function setRrp($rrp)
    {
        if ($rrp) {
            $this->rrp = $rrp;
        }
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setImage($image)
    {
        if ($image) {
            $this->image = $image;
        }
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setFlash($flash)
    {
        if ($flash) {
            $this->flash = $flash;
        }
    }

    public function getFlash()
    {
        return $this->flash;
    }

    public function setYoutubeId($youtube_id)
    {
        if ($youtube_id) {
            $this->youtube_id = $youtube_id;
        }
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    public function setEan($ean)
    {
        if ($ean) {
            $this->ean = $ean;
        }
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setMpn($mpn)
    {
        if ($mpn) {
            $this->mpn = $mpn;
        }
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setUpc($upc)
    {
        if ($upc) {
            $this->upc = $upc;
        }
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setDiscount($discount)
    {
        if ($discount) {
            $this->discount = $discount;
        }
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setProcStatus($proc_status)
    {
        if ($proc_status) {
            $this->proc_status = $proc_status;
        }
    }

    public function getProcStatus()
    {
        return $this->proc_status;
    }

    public function setWebsiteStatus($website_status)
    {
        if ($website_status) {
            $this->website_status = $website_status;
        }
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setSourcingStatus($sourcing_status)
    {
        if ($sourcing_status) {
            $this->sourcing_status = $sourcing_status;
        }
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setExpectedDeliveryDate($expected_delivery_date)
    {
        if ($expected_delivery_date) {
            $this->expected_delivery_date = $expected_delivery_date;
        }
    }

    public function getExpectedDeliveryDate()
    {
        return $this->expected_delivery_date;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        if ($warranty_in_month) {
            $this->warranty_in_month = $warranty_in_month;
        }
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setCatUpselling($cat_upselling)
    {
        if ($cat_upselling) {
            $this->cat_upselling = $cat_upselling;
        }
    }

    public function getCatUpselling()
    {
        return $this->cat_upselling;
    }

    public function setLangRestricted($lang_restricted)
    {
        if ($lang_restricted) {
            $this->lang_restricted = $lang_restricted;
        }
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setShipmentRestrictedType($shipment_restricted_type)
    {
        if ($shipment_restricted_type) {
            $this->shipment_restricted_type = $shipment_restricted_type;
        }
    }

    public function getShipmentRestrictedType()
    {
        return $this->shipment_restricted_type;
    }

    public function setStatus($status)
    {
        if ($status) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by) {
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
