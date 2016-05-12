<?php
class ProductCostDto
{
    private $platform_id;
    private $vat;
    private $vat_percent;
    private $duty;
    private $cc_code;
    private $cc_desc;
    private $declared_value;
    private $declared_pcent;
    private $payment_charge;
    private $admin_fee;
    private $supplier_cost;
    private $item_cost;
    private $purchaser_updated_date;
    private $logistic_cost;
    private $delivery_cost;
    private $freight_cost;
    private $complementary_acc_cost;
    private $platform_commission;
    private $sales_commission;
    private $master_sku;
    private $sku;
    private $prod_grp_cd;
    private $version_id;
    private $colour_id;
    private $item_vat;
    private $prod_name;
    private $shiptype;
    private $shiptype_name;
    private $cost;
    private $price;
    private $current_platform_price;
    private $default_platform_converted_price;
    private $duty_pcent;
    private $import_percent;
    private $payment_charge_percent;
    private $platform_currency_id;
    private $delivery_charge;
    private $platform_delivery_charge;
    private $default_delivery_charge;
    private $free_delivery_limit;
    private $quantity;
    private $display_quantity;
    private $inventory;
    private $clearance;
    private $listing_status;
    private $website_quantity;
    private $surplus_quantity;
    private $website_status;
    private $sourcing_status;
    private $platform_default_shiptype;
    private $platform_code;
    private $cat_id;
    private $sub_cat_id;
    private $sub_sub_cat_id;
    private $brand_id;
    private $category;
    private $sub_cat;
    private $sub_sub_category;
    private $brand_name;
    private $image;
    private $youtube_id;
    private $prod_weight;
    private $profit;
    private $margin;
    private $profit_raw;
    private $margin_raw;
    private $freight_cat_id;
    private $discount;
    private $detail_desc;
    private $prod_status;
    private $supplier_id;
    private $content_prod_name;
    private $ean;
    private $mpn;
    private $upc;
    private $feeds;
    private $int_price;
    private $latency;
    private $auto_price;
    private $suppfc_cost;
    private $whfc_cost;
    private $amazon_efn_cost;
    private $fccus_cost;
    private $extra_info;
    private $platform_region_id;
    private $platform_country_id;
    private $language_id;
    private $component_order;
    private $with_bundle;
    private $title;
    private $ext_ref_1;
    private $ext_ref_2;
    private $ext_ref_3;
    private $ext_ref_4;
    private $ext_qty;
    private $ext_item_id;
    private $ext_status;
    private $action;
    private $remark;
    private $fulfillment_centre_id;
    private $amazon_reprice_name;
    private $listing_fee;
    private $sub_cat_margin;
    private $forex_fee_percent;
    private $forex_fee;
    private $auto_total_charge;
    private $wms_inv;
    private $expected_delivery_date;
    private $fixed_rrp;
    private $rrp_factor;
    private $warranty_in_month;
    private $handling_time;
    private $ship_day;
    private $delivery_day;
    private $gsc_status;
    private $is_advertised;
    private $gsc_cm_id;
    private $gsc_product_name;
    private $gsc_ext_name;
    private $api_request_result;
    private $comment;
    private $ad_api_request_result;
    private $ad_status;
    private $lang_restricted;
    private $auto_restock;

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($value)
    {
        $this->vat = $value;
    }

    public function getItemVat()
    {
        return $this->item_vat;
    }

    public function setItemVat($value)
    {
        $this->item_vat = $value;
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setVatPercent($value)
    {
        $this->vat_percent = $value;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
    }

    public function getDuty()
    {
        return $this->duty;
    }

    public function setDuty($value)
    {
        $this->duty = $value;
    }

    public function getCcCode()
    {
        return $this->cc_code;
    }

    public function setCcCode($value)
    {
        $this->cc_code = $value;
    }

    public function getCcDesc()
    {
        return $this->cc_desc;
    }

    public function setCcDesc($value)
    {
        $this->cc_desc = $value;
    }

    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    public function setDeclaredValue($value)
    {
        $this->declared_value = $value;
    }

    public function getDeclaredPcent()
    {
        return $this->declared_pcent;
    }

    public function setDeclaredPcent($value)
    {
        $this->declared_pcent = $value;
    }

    public function getPaymentCharge()
    {
        return $this->payment_charge;
    }

    public function setPaymentCharge($value)
    {
        $this->payment_charge = $value;
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setAdminFee($value)
    {
        $this->admin_fee = $value;
    }

    public function getSupplierCost()
    {
        return number_format($this->supplier_cost, 2, ".", "");
    }

    public function setSupplierCost($value)
    {
        $this->supplier_cost = $value;
    }

    public function getItemCost()
    {
        return number_format($this->item_cost, 2, ".", "");
    }

    public function setItemCost($value)
    {
        $this->item_cost = $value;
    }

    public function getPurchaserUpdatedDate()
    {
        return $this->purchaser_updated_date;
    }

    public function setPurchaserUpdatedDate($value)
    {
        $this->purchaser_updated_date = $value;
    }

    public function getLogisticCost()
    {
        return $this->logistic_cost;
    }

    public function setLogisticCost($value)
    {
        $this->logistic_cost = $value;
    }

    public function getDeliveryCost()
    {
        return $this->delivery_cost;
    }

    public function setDeliveryCost($value)
    {
        $this->delivery_cost = $value;
    }

    public function getFreightCost()
    {
        return $this->freight_cost;
    }

    public function setFreightCost($value)
    {
        $this->freight_cost = $value;
    }

    public function getComplementaryAccCost()
    {
        return $this->complementary_acc_cost;
    }

    public function setComplementaryAccCost($value)
    {
        $this->complementary_acc_cost = $value;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setListingStatus($value)
    {
        $this->listing_status = $value;
    }

    public function getPlatformCommission()
    {
        return $this->platform_commission;
    }

    public function setPlatformCommission($value)
    {
        $this->platform_commission = $value;
    }

    public function getSalesCommission()
    {
        return $this->sales_commission;
    }

    public function setSalesCommission($value)
    {
        $this->sales_commission = $value;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($value)
    {
        $this->master_sku = $value;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
    }

    public function setProdGrpCd($value)
    {
        $this->prod_grp_cd = $value;
        return $this;
    }

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setVersionId($value)
    {
        $this->version_id = $value;
        return $this;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourId($value)
    {
        $this->colour_id = $value;
        return $this;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdName($value)
    {
        $this->prod_name = $value;
    }

    public function getShiptype()
    {
        return $this->shiptype;
    }

    public function setShiptype($value)
    {
        $this->shiptype = $value;
    }

    public function getShiptypeName()
    {
        return $this->shiptype_name;
    }

    public function setShiptypeName($value)
    {
        $this->shiptype_name = $value;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($value)
    {
        $this->cost = $value;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($value)
    {
        $this->price = $value;
    }

    public function getCurrentPlatformPrice()
    {
        return $this->current_platform_price;
    }

    public function setCurrentPlatformPrice($value)
    {
        $this->current_platform_price = $value;
    }

    public function getDefaultPlatformConvertedPrice()
    {
        return $this->default_platform_converted_price;
    }

    public function setDefaultPlatformConvertedPrice($value)
    {
        $this->default_platform_converted_price = $value;
    }

    public function getDutyPcent()
    {
        return $this->duty_pcent;
    }

    public function setDutyPcent($value)
    {
        $this->duty_pcent = $value;
    }

    public function getImportPercent()
    {
        return $this->import_percent;
    }

    public function setImportPercent($value)
    {
        $this->import_percent = $value;
    }

    public function getPaymentChargePercent()
    {
        return $this->payment_charge_percent;
    }

    public function setPaymentChargePercent($value)
    {
        $this->payment_charge_percent = $value;
    }

    public function getPlatformCurrencyId()
    {
        return $this->platform_currency_id;
    }

    public function setPlatformCurrencyId($value)
    {
        $this->platform_currency_id = $value;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeliveryCharge($value)
    {
        $this->delivery_charge = $value;
    }

    public function getFreeDeliveryLimit()
    {
        return $this->free_delivery_limit;
    }

    public function setFreeDeliveryLimit($value)
    {
        $this->free_delivery_limit = $value;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($value)
    {
        $this->quantity = $value;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setInventory($value)
    {
        $this->inventory = $value;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setClearance($value)
    {
        $this->clearance = $value;
    }

    public function getDisplayQuantity()
    {
        return $this->display_quantity;
    }

    public function setDisplayQuantity($value)
    {
        $this->display_quantity = $value;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteQuantity($value)
    {
        $this->website_quantity = $value;
    }

    public function getSurplusQuantity()
    {
        return $this->surplus_quantity;
    }

    public function setSurplusQuantity($value)
    {
        $this->surplus_quantity = $value;
        return $this;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($value)
    {
        $this->website_status = $value;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setSourcingStatus($value)
    {
        $this->sourcing_status = $value;
    }

    public function getPlatformDefaultShiptype()
    {
        return $this->platform_default_shiptype;
    }

    public function setPlatformDefaultShiptype($value)
    {
        $this->platform_default_shiptype = $value;
    }

    public function getDefaultDeliveryCharge()
    {
        return $this->default_delivery_charge;
    }

    public function setDefaultDeliveryCharge($value)
    {
        $this->default_delivery_charge = $value;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($value)
    {
        $this->cat_id = $value;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatId($value)
    {
        $this->sub_cat_id = $value;
    }

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setSubSubCatId($value)
    {
        $this->sub_sub_cat_id = $value;
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setBrandId($value)
    {
        $this->brand_id = $value;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($value)
    {
        $this->category = $value;
    }

    public function getSubCategory()
    {
        return $this->sub_category;
    }

    public function setSubCategory($value)
    {
        $this->sub_category = $value;
    }

    public function getSubSubCategory()
    {
        return $this->sub_sub_category;
    }

    public function setSubSubCategory($value)
    {
        $this->sub_sub_category = $value;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setBrandName($value)
    {
        $this->brand_name = $value;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($value)
    {
        $this->image = $value;
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    public function setYoutubeId($value)
    {
        $this->youtube_id = $value;
    }

    public function getPlatformDeliveryCharge()
    {
        return $this->platform_delivery_charge;
    }

    public function setPlatformDeliveryCharge($value)
    {
        $this->platform_delivery_charge = $value;
    }

    public function getPlatformCode()
    {
        return $this->platform_code;
    }

    public function setPlatformCode($value)
    {
        $this->platform_code = $value;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfit($value)
    {
        $this->profit = $value;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($value)
    {
        $this->margin = $value;
    }


    public function getProfitRaw()
    {
        return $this->profit_raw;
    }

    public function setProfitRaw($value)
    {
        $this->profit_raw = $value;
        return $this;
    }

    public function getMarginRaw()
    {
        return $this->margin_raw;
    }

    public function setMarginRaw($value)
    {
        $this->margin_raw = $value;
        return $this;
    }


    public function getFreightCatId()
    {
        return $this->freight_cat_id;
    }

    public function setFreightCatId($value)
    {
        $this->freight_cat_id = $value;
    }

    public function getProdWeight()
    {
        return $this->prod_weight;
    }

    public function setProdWeight($value)
    {
        $this->prod_weight = $value;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($value)
    {
        $this->discount = $value;
    }

    public function getBundleName()
    {
        return $this->bundle_name;
    }

    public function setBundleName($value)
    {
        $this->bundle_name = $value;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setDetailDesc($value)
    {
        $this->detail_desc = $value;
    }

    public function getProdStatus()
    {
        return $this->prod_status;
    }

    public function setProdStatus($value)
    {
        $this->prod_status = $value;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierId($value)
    {
        $this->supplier_id = $value;
    }

    public function getContentProdName()
    {
        return $this->content_prod_name;
    }

    public function setContentProdName($value)
    {
        $this->content_prod_name = $value;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setEan($value)
    {
        $this->ean = $value;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setMpn($value)
    {
        $this->mpn = $value;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setUpc($value)
    {
        $this->upc = $value;
    }

    public function getFeeds()
    {
        return $this->feeds;
    }

    public function setFeeds($value)
    {
        $this->feeds = $value;
        return $this;
    }

    public function getIntPrice()
    {
        return $this->int_price;
    }

    public function setIntPrice($value)
    {
        $this->int_price = $value;
        return $this;
    }

    public function getLatency()
    {
        return $this->latency;
    }

    public function setLatency($value)
    {
        $this->latency = $value;
        return $this;
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
    }

    public function setAutoPrice($value)
    {
        $this->auto_price = $value;
        return $this;
    }

    public function getSuppfcCost()
    {
        return number_format($this->suppfc_cost, 2, ".", "");
    }

    public function setSuppfcCost($value)
    {
        $this->suppfc_cost = $value;
        return $this;
    }

    public function getWhfcCost()
    {
        return number_format($this->whfc_cost, 2, ".", "");
    }

    public function setWhfcCost($value)
    {
        $this->whfc_cost = $value;
        return $this;
    }

    public function getAmazonEfnCost()
    {
        return number_format($this->amazon_efn_cost, 2, ".", "");
    }

    public function setAmazonEfnCost($value)
    {
        $this->amazon_efn_cost = $value;
        return $this;
    }

    public function getFccusCost()
    {
        return number_format($this->fccus_cost, 2, ".", "");
    }

    public function setFccusCost($value)
    {
        $this->fccus_cost = $value;
        return $this;
    }

    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    public function setExtraInfo($value)
    {
        $this->extra_info = $value;
        return $this;
    }

    public function getPlatformRegionId()
    {
        return $this->platform_region_id;
    }

    public function setPlatformRegionId($value)
    {
        $this->platform_region_id = $value;
        return $this;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setPlatformCountryId($value)
    {
        $this->platform_country_id = $value;
        return $this;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setLanguageId($value)
    {
        $this->language_id = $value;
        return $this;
    }

    public function getComponentOrder()
    {
        return $this->component_order;
    }

    public function setComponentOrder($value)
    {
        $this->component_order = $value;
        return $this;
    }


    public function getWithBundle()
    {
        return $this->with_bundle;
    }

    public function setWithBundle($value)
    {
        $this->with_bundle = $value;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    public function getExtRef1()
    {
        return $this->ext_ref_1;
    }

    public function setExtRef1($value)
    {
        $this->ext_ref_1 = $value;
        return $this;
    }

    public function getExtRef2()
    {
        return $this->ext_ref_2;
    }

    public function setExtRef2($value)
    {
        $this->ext_ref_2 = $value;
        return $this;
    }

    public function getExtRef3()
    {
        return $this->ext_ref_3;
    }

    public function setExtRef3($value)
    {
        $this->ext_ref_3 = $value;
        return $this;
    }

    public function getExtRef4()
    {
        return $this->ext_ref_4;
    }

    public function setExtRef4($value)
    {
        $this->ext_ref_4 = $value;
        return $this;
    }

    public function getExtQty()
    {
        return $this->ext_qty;
    }

    public function setExtQty($value)
    {
        $this->ext_qty = $value;
        return $this;
    }

    public function getExtItemId()
    {
        return $this->ext_item_id;
    }

    public function setExtItemId($value)
    {
        $this->ext_item_id = $value;
        return $this;
    }

    public function getExtStatus()
    {
        return $this->ext_status;
    }

    public function setExtStatus($value)
    {
        $this->ext_status = $value;
        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($value)
    {
        $this->action = $value;
        return $this;
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setRemark($value)
    {
        $this->remark = $value;
    }

    public function getFulfillmentCentreId()
    {
        return $this->fulfillment_centre_id;
    }

    public function setFulfillmentCentreId($value)
    {
        $this->fulfillment_centre_id = $value;
    }

    public function getAmazonRepriceName()
    {
        return $this->amazon_reprice_name;
    }

    public function setAmazonRepriceName($value)
    {
        $this->amazon_reprice_name = $value;
    }

    public function getListingFee()
    {
        return $this->listing_fee;
    }

    public function setListingFee($value)
    {
        $this->listing_fee = $value;
    }

    public function getSubCatMargin()
    {
        return $this->sub_cat_margin;
    }

    public function setSubCatMargin($value)
    {
        $this->sub_cat_margin = $value;
    }

    public function getForexFeePercent()
    {
        return $this->forex_fee_percent;
    }

    public function setForexFeePercent($value)
    {
        $this->forex_fee_percent = $value;
    }

    public function getForexFee()
    {
        return $this->forex_fee;
    }

    public function setForexFee($value)
    {
        $this->forex_fee = $value;
    }

    public function getAutoTotalCharge()
    {
        return $this->auto_total_charge;
    }

    public function setAutoTotalCharge($value)
    {
        $this->auto_total_charge = $value;
    }

    public function getWmsInv()
    {
        return $this->wms_inv;
    }

    public function setWmsInv($value)
    {
        $this->wms_inv = $value;
    }

    public function getExpectedDeliveryDate()
    {
        return $this->expected_delivery_date;
    }

    public function setExpectedDeliveryDate($value)
    {
        $this->expected_delivery_date = $value;
    }

    public function getFixedRrp()
    {
        return $this->fixed_rrp;
    }

    public function setFixedRrp($value)
    {
        $this->fixed_rrp = $value;
    }

    public function getRrpFactor()
    {
        return $this->rrp_factor;
    }

    public function setRrpFactor($value)
    {
        $this->rrp_factor = $value;
        return $this;
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

    public function setWarrantyInMonth($value)
    {
        $this->warranty_in_month = $value;
        return $this;
    }

    public function getValueToDeclare()
    {
        return $this->value_to_declare;
    }

    public function setValueToDeclare($value)
    {
        $this->value_to_declare = $value;
    }

    public function getHandlingTime()
    {
        return $this->handling_time;
    }

    public function setHandlingTime($value)
    {
        $this->handling_time = $value;
    }

    public function getShipDay()
    {
        return $this->ship_day;
    }

    public function setShipDay($value)
    {
        $this->ship_day = $value;
    }

    public function getDeliveryDay()
    {
        return $this->delivery_day;
    }

    public function setDeliveryDay($value)
    {
        $this->delivery_day = $value;
    }

    public function getGscStatus()
    {
        return $this->gsc_status;
    }

    public function setGscStatus($value)
    {
        $this->gsc_status = $value;
    }

    public function getIsAdvertised()
    {
        return $this->is_advertised;
    }

    public function setIsAdvertised($value)
    {
        $this->is_advertised = $value;
    }

    public function getGscCmId()
    {
        return $this->gsc_cm_id;
    }

    public function setGscCmId($value)
    {
        $this->gsc_cm_id = $value;
    }

    public function getGscProductName()
    {
        return $this->gsc_product_name;
    }

    public function setGscProductName($value)
    {
        $this->gsc_product_name = $value;
    }

    public function getGscExtName()
    {
        return $this->gsc_ext_name;
    }

    public function setGscExtName($value)
    {
        $this->gsc_ext_name = $value;
    }

    public function getApiRequestResult()
    {
        return $this->api_request_result;
    }

    public function setApiRequestResult($value)
    {
        $this->api_request_result = $value;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($value)
    {
        $this->comment = $value;
    }

    public function getAdApiRequestResult()
    {
        return $this->ad_api_request_result;
    }

    public function setAdApiRequestResult($value)
    {
        $this->ad_api_request_result = $value;
    }

    public function getAdStatus()
    {
        return $this->ad_status;
    }

    public function setAdStatus($value)
    {
        $this->ad_status = $value;
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setLangRestricted($value)
    {
        $this->lang_restricted = $value;
    }

    public function getAutoRestock()
    {
        return $this->auto_restock;
    }

    public function setAutoRestock($value)
    {
        $this->auto_restock = $value;
    }
}
