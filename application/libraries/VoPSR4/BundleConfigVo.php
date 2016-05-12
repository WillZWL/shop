<?php

class BundleConfigVo extends \BaseVo
{
    private $id;
    private $country_id;
    private $discount_1_item = '0.00';
    private $discount_2_item = '0.00';
    private $discount_3_more_item = '0.00';
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setDiscount1Item($discount_1_item)
    {
        if ($discount_1_item !== null) {
            $this->discount_1_item = $discount_1_item;
        }
    }

    public function getDiscount1Item()
    {
        return $this->discount_1_item;
    }

    public function setDiscount2Item($discount_2_item)
    {
        if ($discount_2_item !== null) {
            $this->discount_2_item = $discount_2_item;
        }
    }

    public function getDiscount2Item()
    {
        return $this->discount_2_item;
    }

    public function setDiscount3MoreItem($discount_3_more_item)
    {
        if ($discount_3_more_item !== null) {
            $this->discount_3_more_item = $discount_3_more_item;
        }
    }

    public function getDiscount3MoreItem()
    {
        return $this->discount_3_more_item;
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

}
