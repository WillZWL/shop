<?php
class ExchangeRateFlexVo extends \BaseVo
{
    private $from_currency_id;
    private $to_currency_id;
    private $rate = '1.0000';
    private $approvial_status = '1';

    protected $primary_key = array("from_currency_id", "to_currency_id");

    protected $increment_field = "";

    public function getFromCurrencyId()
    {
        return $this->from_currency_id;
    }

    public function setFromCurrencyId($value)
    {
        $this->from_currency_id = $value;
        return $this;
    }

    public function getToCurrencyId()
    {
        return $this->to_currency_id;
    }

    public function setToCurrencyId($value)
    {
        $this->to_currency_id = $value;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($value)
    {
        $this->rate = $value;
        return $this;
    }

    public function getApprovialStatus()
    {
        return $this->approvial_status;
    }

    public function setApprovialStatus($value)
    {
        $this->approvial_status = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function getModify_on()
    {
        return $this->modify_on;
    }

    public function setModify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
        return $this;
    }


}