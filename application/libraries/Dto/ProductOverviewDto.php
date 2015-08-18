<?php
class ProductOverviewDto
{
    private $platform;
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
    private $name;
    private $shiptype;
    private $shiptype_name;
    private $cost;
    private $price;
    private $current_platform_price;
    private $default_platform_converted_price;
    private $duty_pcent;
    private $import_percent;
    private $payment_charge_percent;
    private $platform_currency;
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
    private $status;

    public function getPlatform()
    {
        return $this->platform;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    public function getVatPercent()
    {
        return $this->vat_percent;
    }

    public function setVatPercent($vat_percent)
    {
        $this->vat_percent = $vat_percent;
    }

    public function getDuty()
    {
        return $this->duty;
    }

    public function setDuty($duty)
    {
        $this->duty = $duty;
    }

    public function getCcCode()
    {
        return $this->cc_code;
    }

    public function setCcCode($cc_code)
    {
        $this->cc_code = $cc_code;
    }

    public function getCcDesc()
    {
        return $this->cc_desc;
    }

    public function setCcDesc($cc_desc)
    {
        $this->cc_desc = $cc_desc;
    }

    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    public function setDeclaredValue($declared_value)
    {
        $this->declared_value = $declared_value;
    }

    public function getDeclaredPcent()
    {
        return $this->declared_pcent;
    }

    public function setDeclaredPcent($declared_pcent)
    {
        $this->declared_pcent = $declared_pcent;
    }

    public function getPaymentCharge()
    {
        return $this->payment_charge;
    }

    public function setPaymentCharge($payment_charge)
    {
        $this->payment_charge = $payment_charge;
    }

    public function getAdminFee()
    {
        return $this->admin_fee;
    }

    public function setAdminFee($admin_fee)
    {
        $this->admin_fee = $admin_fee;
    }

    public function getSupplierCost()
    {
        return $this->supplier_cost;
    }

    public function setSupplierCost($supplier_cost)
    {
        $this->supplier_cost = $supplier_cost;
    }

    public function getItemCost()
    {
        return $this->item_cost;
    }

    public function setItemCost($item_cost)
    {
        $this->item_cost = $item_cost;
    }

    public function getPurchaserUpdatedDate()
    {
        return $this->purchaser_updated_date;
    }

    public function setPurchaserUpdatedDate($purchaser_updated_date)
    {
        $this->purchaser_updated_date = $purchaser_updated_date;
    }

    public function getLogisticCost()
    {
        return $this->logistic_cost;
    }

    public function setLogisticCost($logistic_cost)
    {
        $this->logistic_cost = $logistic_cost;
    }

    public function getDeliveryCost()
    {
        return $this->delivery_cost;
    }

    public function setDeliveryCost($delivery_cost)
    {
        $this->delivery_cost = $delivery_cost;
    }

    public function getFreightCost()
    {
        return $this->freight_cost;
    }

    public function setFreightCost($freight_cost)
    {
        $this->freight_cost = $freight_cost;
    }

    public function getComplementaryAccCost()
    {
        return $this->complementary_acc_cost;
    }

    public function setComplementaryAccCost($complementary_acc_cost)
    {
        $this->complementary_acc_cost = $complementary_acc_cost;
    }

    public function getPlatformCommission()
    {
        return $this->platform_commission;
    }

    public function setPlatformCommission($platform_commission)
    {
        $this->platform_commission = $platform_commission;
    }

    public function getSalesCommission()
    {
        return $this->sales_commission;
    }

    public function setSalesCommission($sales_commission)
    {
        $this->sales_commission = $sales_commission;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($master_sku)
    {
        $this->master_sku = $master_sku;
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

    public function getVersionId()
    {
        return $this->version_id;
    }

    public function setVersionId($version_id)
    {
        $this->version_id = $version_id;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourId($colour_id)
    {
        $this->colour_id = $colour_id;
    }

    public function getItemVat()
    {
        return $this->item_vat;
    }

    public function setItemVat($item_vat)
    {
        $this->item_vat = $item_vat;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShiptype()
    {
        return $this->shiptype;
    }

    public function setShiptype($shiptype)
    {
        $this->shiptype = $shiptype;
    }

    public function getShiptypeName()
    {
        return $this->shiptype_name;
    }

    public function setShiptypeName($shiptype_name)
    {
        $this->shiptype_name = $shiptype_name;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getCurrentPlatformPrice()
    {
        return $this->current_platform_price;
    }

    public function setCurrentPlatformPrice($current_platform_price)
    {
        $this->current_platform_price = $current_platform_price;
    }

    public function getDefaultPlatformConvertedPrice()
    {
        return $this->default_platform_converted_price;
    }

    public function setDefaultPlatformConvertedPrice($default_platform_converted_price)
    {
        $this->default_platform_converted_price = $default_platform_converted_price;
    }

    public function getDutyPcent()
    {
        return $this->duty_pcent;
    }

    public function setDutyPcent($duty_pcent)
    {
        $this->duty_pcent = $duty_pcent;
    }

    public function getImportPercent()
    {
        return $this->import_percent;
    }

    public function setImportPercent($import_percent)
    {
        $this->import_percent = $import_percent;
    }

    public function getPaymentChargePercent()
    {
        return $this->payment_charge_percent;
    }

    public function setPaymentChargePercent($payment_charge_percent)
    {
        $this->payment_charge_percent = $payment_charge_percent;
    }

    public function getPlatformCurrency()
    {
        return $this->platform_currency;
    }

    public function setPlatformCurrency($platform_currency)
    {
        $this->platform_currency = $platform_currency;
    }

    public function getDeliveryCharge()
    {
        return $this->delivery_charge;
    }

    public function setDeliveryCharge($delivery_charge)
    {
        $this->delivery_charge = $delivery_charge;
    }

    public function getPlatformDeliveryCharge()
    {
        return $this->platform_delivery_charge;
    }

    public function setPlatformDeliveryCharge($platform_delivery_charge)
    {
        $this->platform_delivery_charge = $platform_delivery_charge;
    }

    public function getDefaultDeliveryCharge()
    {
        return $this->default_delivery_charge;
    }

    public function setDefaultDeliveryCharge($default_delivery_charge)
    {
        $this->default_delivery_charge = $default_delivery_charge;
    }

    public function getFreeDeliveryLimit()
    {
        return $this->free_delivery_limit;
    }

    public function setFreeDeliveryLimit($free_delivery_limit)
    {
        $this->free_delivery_limit = $free_delivery_limit;
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

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getSurplusQuantity()
    {
        return $this->surplus_quantity;
    }

    public function setSurplusQuantity($surplus_quantity)
    {
        $this->surplus_quantity = $surplus_quantity;
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

    public function getPlatformDefaultShiptype()
    {
        return $this->platform_default_shiptype;
    }

    public function setPlatformDefaultShiptype($platform_default_shiptype)
    {
        $this->platform_default_shiptype = $platform_default_shiptype;
    }

    public function getPlatformCode()
    {
        return $this->platform_code;
    }

    public function setPlatformCode($platform_code)
    {
        $this->platform_code = $platform_code;
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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getSubCat()
    {
        return $this->sub_cat;
    }

    public function setSubCat($sub_cat)
    {
        $this->sub_cat = $sub_cat;
    }

    public function getSubSubCategory()
    {
        return $this->sub_sub_category;
    }

    public function setSubSubCategory($sub_sub_category)
    {
        $this->sub_sub_category = $sub_sub_category;
    }

    public function getBrandName()
    {
        return $this->brand_name;
    }

    public function setBrandName($brand_name)
    {
        $this->brand_name = $brand_name;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getYoutubeId()
    {
        return $this->youtube_id;
    }

    public function setYoutubeId($youtube_id)
    {
        $this->youtube_id = $youtube_id;
    }

    public function getProdWeight()
    {
        return $this->prod_weight;
    }

    public function setProdWeight($prod_weight)
    {
        $this->prod_weight = $prod_weight;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getMargin()
    {
        return $this->margin;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getProfitRaw()
    {
        return $this->profit_raw;
    }

    public function setProfitRaw($profit_raw)
    {
        $this->profit_raw = $profit_raw;
    }

    public function getMarginRaw()
    {
        return $this->margin_raw;
    }

    public function setMarginRaw($margin_raw)
    {
        $this->margin_raw = $margin_raw;
    }

    public function getFreightCatId()
    {
        return $this->freight_cat_id;
    }

    public function setFreightCatId($freight_cat_id)
    {
        $this->freight_cat_id = $freight_cat_id;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDetailDesc()
    {
        return $this->detail_desc;
    }

    public function setDetailDesc($detail_desc)
    {
        $this->detail_desc = $detail_desc;
    }

    public function getProdStatus()
    {
        return $this->prod_status;
    }

    public function setProdStatus($prod_status)
    {
        $this->prod_status = $prod_status;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getContentProdName()
    {
        return $this->content_prod_name;
    }

    public function setContentProdName($content_prod_name)
    {
        $this->content_prod_name = $content_prod_name;
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

    public function getFeeds()
    {
        return $this->feeds;
    }

    public function setFeeds($feeds)
    {
        $this->feeds = $feeds;
    }

    public function getIntPrice()
    {
        return $this->int_price;
    }

    public function setIntPrice($int_price)
    {
        $this->int_price = $int_price;
    }

    public function getLatency()
    {
        return $this->latency;
    }

    public function setLatency($latency)
    {
        $this->latency = $latency;
    }

    public function getAutoPrice()
    {
        return $this->auto_price;
    }

    public function setAutoPrice($auto_price)
    {
        $this->auto_price = $auto_price;
    }

    public function getSuppfcCost()
    {
        return $this->suppfc_cost;
    }

    public function setSuppfcCost($suppfc_cost)
    {
        $this->suppfc_cost = $suppfc_cost;
    }

    public function getWhfcCost()
    {
        return $this->whfc_cost;
    }

    public function setWhfcCost($whfc_cost)
    {
        $this->whfc_cost = $whfc_cost;
    }

    public function getAmazonEfnCost()
    {
        return $this->amazon_efn_cost;
    }

    public function setAmazonEfnCost($amazon_efn_cost)
    {
        $this->amazon_efn_cost = $amazon_efn_cost;
    }

    public function getFccusCost()
    {
        return $this->fccus_cost;
    }

    public function setFccusCost($fccus_cost)
    {
        $this->fccus_cost = $fccus_cost;
    }

    public function getExtraInfo()
    {
        return $this->extra_info;
    }

    public function setExtraInfo($extra_info)
    {
        $this->extra_info = $extra_info;
    }

    public function getPlatformRegionId()
    {
        return $this->platform_region_id;
    }

    public function setPlatformRegionId($platform_region_id)
    {
        $this->platform_region_id = $platform_region_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getLanguageId()
    {
        return $this->language_id;
    }

    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    public function getComponentOrder()
    {
        return $this->component_order;
    }

    public function setComponentOrder($component_order)
    {
        $this->component_order = $component_order;
    }

    public function getWithBundle()
    {
        return $this->with_bundle;
    }

    public function setWithBundle($with_bundle)
    {
        $this->with_bundle = $with_bundle;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getExtRef1()
    {
        return $this->ext_ref_1;
    }

    public function setExtRef1($ext_ref_1)
    {
        $this->ext_ref_1 = $ext_ref_1;
    }

    public function getExtRef2()
    {
        return $this->ext_ref_2;
    }

    public function setExtRef2($ext_ref_2)
    {
        $this->ext_ref_2 = $ext_ref_2;
    }

    public function getExtRef3()
    {
        return $this->ext_ref_3;
    }

    public function setExtRef3($ext_ref_3)
    {
        $this->ext_ref_3 = $ext_ref_3;
    }

    public function getExtRef4()
    {
        return $this->ext_ref_4;
    }

    public function setExtRef4($ext_ref_4)
    {
        $this->ext_ref_4 = $ext_ref_4;
    }

    public function getExtQty()
    {
        return $this->ext_qty;
    }

    public function setExtQty($ext_qty)
    {
        $this->ext_qty = $ext_qty;
    }

    public function getExtItemId()
    {
        return $this->ext_item_id;
    }

    public function setExtItemId($ext_item_id)
    {
        $this->ext_item_id = $ext_item_id;
    }

    public function getExtStatus()
    {
        return $this->ext_status;
    }

    public function setExtStatus($ext_status)
    {
        $this->ext_status = $ext_status;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    public function getFulfillmentCentreId()
    {
        return $this->fulfillment_centre_id;
    }

    public function setFulfillmentCentreId($fulfillment_centre_id)
    {
        $this->fulfillment_centre_id = $fulfillment_centre_id;
    }

    public function getAmazonRepriceName()
    {
        return $this->amazon_reprice_name;
    }

    public function setAmazonRepriceName($amazon_reprice_name)
    {
        $this->amazon_reprice_name = $amazon_reprice_name;
    }

    public function getListingFee()
    {
        return $this->listing_fee;
    }

    public function setListingFee($listing_fee)
    {
        $this->listing_fee = $listing_fee;
    }

    public function getSubCatMargin()
    {
        return $this->sub_cat_margin;
    }

    public function setSubCatMargin($sub_cat_margin)
    {
        $this->sub_cat_margin = $sub_cat_margin;
    }

    public function getForexFeePercent()
    {
        return $this->forex_fee_percent;
    }

    public function setForexFeePercent($forex_fee_percent)
    {
        $this->forex_fee_percent = $forex_fee_percent;
    }

    public function getForexFee()
    {
        return $this->forex_fee;
    }

    public function setForexFee($forex_fee)
    {
        $this->forex_fee = $forex_fee;
    }

    public function getAutoTotalCharge()
    {
        return $this->auto_total_charge;
    }

    public function setAutoTotalCharge($auto_total_charge)
    {
        $this->auto_total_charge = $auto_total_charge;
    }

    public function getWmsInv()
    {
        return $this->wms_inv;
    }

    public function setWmsInv($wms_inv)
    {
        $this->wms_inv = $wms_inv;
    }

    public function getExpectedDeliveryDate()
    {
        return $this->expected_delivery_date;
    }

    public function setExpectedDeliveryDate($expected_delivery_date)
    {
        $this->expected_delivery_date = $expected_delivery_date;
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

    public function getHandlingTime()
    {
        return $this->handling_time;
    }

    public function setHandlingTime($handling_time)
    {
        $this->handling_time = $handling_time;
    }

    public function getShipDay()
    {
        return $this->ship_day;
    }

    public function setShipDay($ship_day)
    {
        $this->ship_day = $ship_day;
    }

    public function getDeliveryDay()
    {
        return $this->delivery_day;
    }

    public function setDeliveryDay($delivery_day)
    {
        $this->delivery_day = $delivery_day;
    }

    public function getGscStatus()
    {
        return $this->gsc_status;
    }

    public function setGscStatus($gsc_status)
    {
        $this->gsc_status = $gsc_status;
    }

    public function getIsAdvertised()
    {
        return $this->is_advertised;
    }

    public function setIsAdvertised($is_advertised)
    {
        $this->is_advertised = $is_advertised;
    }

    public function getGscCmId()
    {
        return $this->gsc_cm_id;
    }

    public function setGscCmId($gsc_cm_id)
    {
        $this->gsc_cm_id = $gsc_cm_id;
    }

    public function getGscProductName()
    {
        return $this->gsc_product_name;
    }

    public function setGscProductName($gsc_product_name)
    {
        $this->gsc_product_name = $gsc_product_name;
    }

    public function getGscExtName()
    {
        return $this->gsc_ext_name;
    }

    public function setGscExtName($gsc_ext_name)
    {
        $this->gsc_ext_name = $gsc_ext_name;
    }

    public function getApiRequestResult()
    {
        return $this->api_request_result;
    }

    public function setApiRequestResult($api_request_result)
    {
        $this->api_request_result = $api_request_result;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getAdApiRequestResult()
    {
        return $this->ad_api_request_result;
    }

    public function setAdApiRequestResult($ad_api_request_result)
    {
        $this->ad_api_request_result = $ad_api_request_result;
    }

    public function getAdStatus()
    {
        return $this->ad_status;
    }

    public function setAdStatus($ad_status)
    {
        $this->ad_status = $ad_status;
    }

    public function getLangRestricted()
    {
        return $this->lang_restricted;
    }

    public function setLangRestricted($lang_restricted)
    {
        $this->lang_restricted = $lang_restricted;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
