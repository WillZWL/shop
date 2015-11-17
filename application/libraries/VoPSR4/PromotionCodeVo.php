<?php
class PromotionCodeVo extends \BaseVo
{
    private $id;
    private $code;
    private $description = '';
    private $disc_type;
    private $over_amount = '0.00';
    private $over_amount_1 = '0.00';
    private $discount_1 = '0.00';
    private $over_amount_2 = '0.00';
    private $discount_2 = '0.00';
    private $over_amount_3 = '0.00';
    private $discount_3 = '0.00';
    private $over_amount_4 = '0.00';
    private $discount_4 = '0.00';
    private $over_amount_5 = '0.00';
    private $discount_5 = '0.00';
    private $region_id = '0';
    private $country_id = '';
    private $currency_id = '';
    private $free_item_sku = '';
    private $cat_id = '0';
    private $sub_cat_id = '0';
    private $sub_sub_cat_id = '0';
    private $brand_id = '0';
    private $relevant_prod = '';
    private $email = '';
    private $disc_level = 'ALL';
    private $disc_level_value = '';
    private $redemption_prod_value = '';
    private $redemption_amount = '0.00';
    private $promo_message = '';
    private $display_message = '1';
    private $expire_date = '0000-00-00';
    private $promotion_schedule = '1';
    private $week_day = '';
    private $start_time = '00:00:00';
    private $end_time = '00:00:00';
    private $time_zone = 'GTM+00:00';
    private $repeat = '1';
    private $redemption = '-1';
    private $total_redemption = '-1';
    private $no_taken = '0';
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
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDiscType($disc_type)
    {
        $this->disc_type = $disc_type;
    }

    public function getDiscType()
    {
        return $this->disc_type;
    }

    public function setOverAmount($over_amount)
    {
        $this->over_amount = $over_amount;
    }

    public function getOverAmount()
    {
        return $this->over_amount;
    }

    public function setOverAmount1($over_amount_1)
    {
        $this->over_amount_1 = $over_amount_1;
    }

    public function getOverAmount1()
    {
        return $this->over_amount_1;
    }

    public function setDiscount1($discount_1)
    {
        $this->discount_1 = $discount_1;
    }

    public function getDiscount1()
    {
        return $this->discount_1;
    }

    public function setOverAmount2($over_amount_2)
    {
        $this->over_amount_2 = $over_amount_2;
    }

    public function getOverAmount2()
    {
        return $this->over_amount_2;
    }

    public function setDiscount2($discount_2)
    {
        $this->discount_2 = $discount_2;
    }

    public function getDiscount2()
    {
        return $this->discount_2;
    }

    public function setOverAmount3($over_amount_3)
    {
        $this->over_amount_3 = $over_amount_3;
    }

    public function getOverAmount3()
    {
        return $this->over_amount_3;
    }

    public function setDiscount3($discount_3)
    {
        $this->discount_3 = $discount_3;
    }

    public function getDiscount3()
    {
        return $this->discount_3;
    }

    public function setOverAmount4($over_amount_4)
    {
        $this->over_amount_4 = $over_amount_4;
    }

    public function getOverAmount4()
    {
        return $this->over_amount_4;
    }

    public function setDiscount4($discount_4)
    {
        $this->discount_4 = $discount_4;
    }

    public function getDiscount4()
    {
        return $this->discount_4;
    }

    public function setOverAmount5($over_amount_5)
    {
        $this->over_amount_5 = $over_amount_5;
    }

    public function getOverAmount5()
    {
        return $this->over_amount_5;
    }

    public function setDiscount5($discount_5)
    {
        $this->discount_5 = $discount_5;
    }

    public function getDiscount5()
    {
        return $this->discount_5;
    }

    public function setRegionId($region_id)
    {
        $this->region_id = $region_id;
    }

    public function getRegionId()
    {
        return $this->region_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currency_id = $currency_id;
    }

    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    public function setFreeItemSku($free_item_sku)
    {
        $this->free_item_sku = $free_item_sku;
    }

    public function getFreeItemSku()
    {
        return $this->free_item_sku;
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

    public function setRelevantProd($relevant_prod)
    {
        $this->relevant_prod = $relevant_prod;
    }

    public function getRelevantProd()
    {
        return $this->relevant_prod;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setDiscLevel($disc_level)
    {
        $this->disc_level = $disc_level;
    }

    public function getDiscLevel()
    {
        return $this->disc_level;
    }

    public function setDiscLevelValue($disc_level_value)
    {
        $this->disc_level_value = $disc_level_value;
    }

    public function getDiscLevelValue()
    {
        return $this->disc_level_value;
    }

    public function setRedemptionProdValue($redemption_prod_value)
    {
        $this->redemption_prod_value = $redemption_prod_value;
    }

    public function getRedemptionProdValue()
    {
        return $this->redemption_prod_value;
    }

    public function setRedemptionAmount($redemption_amount)
    {
        $this->redemption_amount = $redemption_amount;
    }

    public function getRedemptionAmount()
    {
        return $this->redemption_amount;
    }

    public function setPromoMessage($promo_message)
    {
        $this->promo_message = $promo_message;
    }

    public function getPromoMessage()
    {
        return $this->promo_message;
    }

    public function setDisplayMessage($display_message)
    {
        $this->display_message = $display_message;
    }

    public function getDisplayMessage()
    {
        return $this->display_message;
    }

    public function setExpireDate($expire_date)
    {
        $this->expire_date = $expire_date;
    }

    public function getExpireDate()
    {
        return $this->expire_date;
    }

    public function setPromotionSchedule($promotion_schedule)
    {
        $this->promotion_schedule = $promotion_schedule;
    }

    public function getPromotionSchedule()
    {
        return $this->promotion_schedule;
    }

    public function setWeekDay($week_day)
    {
        $this->week_day = $week_day;
    }

    public function getWeekDay()
    {
        return $this->week_day;
    }

    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    public function getStartTime()
    {
        return $this->start_time;
    }

    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    public function getEndTime()
    {
        return $this->end_time;
    }

    public function setTimeZone($time_zone)
    {
        $this->time_zone = $time_zone;
    }

    public function getTimeZone()
    {
        return $this->time_zone;
    }

    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;
    }

    public function getRepeat()
    {
        return $this->repeat;
    }

    public function setRedemption($redemption)
    {
        $this->redemption = $redemption;
    }

    public function getRedemption()
    {
        return $this->redemption;
    }

    public function setTotalRedemption($total_redemption)
    {
        $this->total_redemption = $total_redemption;
    }

    public function getTotalRedemption()
    {
        return $this->total_redemption;
    }

    public function setNoTaken($no_taken)
    {
        $this->no_taken = $no_taken;
    }

    public function getNoTaken()
    {
        return $this->no_taken;
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

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
