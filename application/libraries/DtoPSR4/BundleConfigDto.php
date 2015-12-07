<?php
class BundleConfigDto
{
    private $id;
    private $country_id;
    private $discount_1_item = '0.00';
    private $discount_2_item = '0.00';
    private $discount_3_more_item = '0.00';
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
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
}
