<?php
include_once 'Base_vo.php';

class PlatformPmgwVo extends \Base_vo
{
    private $id;
    private $platform_id;
    private $sequence;
    private $payment_gateway_id = 'paypal';
    private $pmgw_ref_currency_id;
    private $ref_from_amt;
    private $ref_to_amt_exclusive;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at;
    private $modify_by;

    private $primary_key = ['id'];
    private $increment_field = 'id';

    //instance method
    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setPlatformId($value)
    {
        $this->platform_id = $value;
        return $this;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($value)
    {
        $this->sequence = $value;
        return $this;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPaymentGatewayId($value)
    {
        $this->payment_gateway_id = $value;
        return $this;
    }

    public function getPmgwRefCurrencyId()
    {
        return $this->pmgw_ref_currency_id;
    }

    public function setPmgwRefCurrencyId($value)
    {
        $this->pmgw_ref_currency_id = $value;
        return $this;
    }

    public function getRefFromAmt()
    {
        return $this->ref_from_amt;
    }

    public function setRefFromAmt($value)
    {
        $this->ref_from_amt = $value;
        return $this;
    }

    public function getFefToAmtExclusive()
    {
        return $this->ref_to_amt_exclusive;
    }

    public function setFefToAmtExclusive($value)
    {
        $this->ref_to_amt_exclusive = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
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

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
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

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}

?>