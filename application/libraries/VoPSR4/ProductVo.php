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
    private $auto_restock = '0';
    private $accelerator_salesrpt_bd;
    private $product_warranty_type = '0';
    private $accelerator = '0';


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

    public function setProdGrpCd($prod_grp_cd)
    {
        if ($prod_grp_cd !== null) {
            $this->prod_grp_cd = $prod_grp_cd;
        }
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
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

    public function setVersionId($version_id)
    {
        if ($version_id !== null) {
            $this->version_id = $version_id;
        }
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setFreightCatId($freight_cat_id)
    {
        if ($freight_cat_id !== null) {
            $this->freight_cat_id = $freight_cat_id;
        }
    }

    public function getFreightCatId()
    {
        return $this->freight_cat_id;
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

    public function setSubCatId($sub_cat_id)
    {
        if ($sub_cat_id !== null) {
            $this->sub_cat_id = $sub_cat_id;
        }
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubSubCatId($sub_sub_cat_id)
    {
        if ($sub_sub_cat_id !== null) {
            $this->sub_sub_cat_id = $sub_sub_cat_id;
        }
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setBrandId($brand_id)
    {
        if ($brand_id !== null) {
            $this->brand_id = $brand_id;
        }
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setClearance($clearance)
    {
        if ($clearance !== null) {
            $this->clearance = $clearance;
        }
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setSurplusQuantity($surplus_quantity)
    {
        if ($surplus_quantity !== null) {
            $this->surplus_quantity = $surplus_quantity;
        }
    }

    public function getSurplusQuantity()
    {
        return $this->surplus_quantity;
    }

    public function setSlowMove7Days($slow_move_7_days)
    {
        if ($slow_move_7_days !== null) {
            $this->slow_move_7_days = $slow_move_7_days;
        }
    }

    public function getSlowMove7Days()
    {
        return $this->slow_move_7_days;
    }

    public function setQuantity($quantity)
    {
        if ($quantity !== null) {
            $this->quantity = $quantity;
        }
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setDisplayQuantity($display_quantity)
    {
        if ($display_quantity !== null) {
            $this->display_quantity = $display_quantity;
        }
    }

    public function getDisplayQuantity()
    {
        return $this->display_quantity;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        if ($website_quantity !== null) {
            $this->website_quantity = $website_quantity;
        }
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setExDemo($ex_demo)
    {
        if ($ex_demo !== null) {
            $this->ex_demo = $ex_demo;
        }
    }

    public function getExDemo()
    {
        return $this->ex_demo;
    }

    public function setChinaOem($china_oem)
    {
        if ($china_oem !== null) {
            $this->china_oem = $china_oem;
        }
    }

    public function getChinaOem()
    {
        return $this->china_oem;
    }

    public function setRrp($rrp)
    {
        if ($rrp !== null) {
            $this->rrp = $rrp;
        }
    }

    public function getRrp()
    {
        return $this->rrp;
    }

    public function setImage($image)
    {
        if ($image !== null) {
            $this->image = $image;
        }
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setFlash($flash)
    {
        if ($flash !== null) {
            $this->flash = $flash;
        }
    }

    public function getFlash()
    {
        return $this->flash;
    }

    public function setYoutubeId($youtube_id)
    {
        if ($youtube_id !== null) {
            $this->youtube_id = $youtube_id;
        }
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
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

    public function setDiscount($discount)
    {
        if ($discount !== null) {
            $this->discount = $discount;
        }
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setProcStatus($proc_status)
    {
        if ($proc_status !== null) {
            $this->proc_status = $proc_status;
        }
    }

    public function getProcStatus()
    {
        return $this->proc_status;
    }

    public function setWebsiteStatus($website_status)
    {
        if ($website_status !== null) {
            $this->website_status = $website_status;
        }
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setSourcingStatus($sourcing_status)
    {
        if ($sourcing_status !== null) {
            $this->sourcing_status = $sourcing_status;
        }
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setExpectedDeliveryDate($expected_delivery_date)
    {
        if ($expected_delivery_date !== null) {
            $this->expected_delivery_date = $expected_delivery_date;
        }
    }

    public function getExpectedDeliveryDate()
    {
        return $this->expected_delivery_date;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        if ($warranty_in_month !== null) {
            $this->warranty_in_month = $warranty_in_month;
        }
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setCatUpselling($cat_upselling)
    {
        if ($cat_upselling !== null) {
            $this->cat_upselling = $cat_upselling;
        }
    }

    public function getCatUpselling()
    {
        return $this->cat_upselling;
    }

    public function setLangRestricted($lang_restricted)
    {
        if ($lang_restricted !== null) {
            $this->lang_restricted = $lang_restricted;
        }
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setShipmentRestrictedType($shipment_restricted_type)
    {
        if ($shipment_restricted_type !== null) {
            $this->shipment_restricted_type = $shipment_restricted_type;
        }
    }

    public function getShipmentRestrictedType()
    {
        return $this->shipment_restricted_type;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAutoRestock($auto_restock)
    {
        if ($auto_restock !== null) {
            $this->auto_restock = $auto_restock;
        }
    }

    public function getAutoRestock()
    {
        return $this->auto_restock;
    }

    public function setProductWarrantyType($product_warranty_type)
    {
        if ($product_warranty_type !== null) {
            $this->product_warranty_type = $product_warranty_type;
        }
    }

    public function getProductWarrantyType()
    {
        return $this->product_warranty_type;
    }

    public function setAcceleratorSalesrptBd($accelerator_salesrpt_bd)
    {
        if ($accelerator_salesrpt_bd !== null) {
            $this->accelerator_salesrpt_bd = $accelerator_salesrpt_bd;
        }
    }

    public function getAcceleratorSalesrptBd()
    {
        return $this->accelerator_salesrpt_bd;
    }

    public function setAccelerator($accelerator)
    {
        if ($accelerator !== null) {
            $this->accelerator = $accelerator;
        }
    }

    public function getAccelerator()
    {
        return $this->accelerator;
    }

}
