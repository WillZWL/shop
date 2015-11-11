<?php
class PriceMarginVo extends \BaseVo
{
    private $sku;
    private $platform_id;
    private $selling_price;
    private $profit = '0.00';
    private $margin = '0.00';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at;
    private $modify_by;

    private $primary_key = ['sku', 'platform_id'];

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSellingPrice($selling_price)
    {
        $this->selling_price = $selling_price;
    }

    public function getSellingPrice()
    {
        return $this->selling_price;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    public function getMargin()
    {
        return $this->margin;
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
}
