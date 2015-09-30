<?php
class PlatformPmgwVo extends \BaseVo
{
    private $id;
    private $platform_id;
    private $sequence = '1';
    private $payment_gateway_id = 'paypal';
    private $pmgw_ref_currency_id = '';
    private $ref_from_amt = '0';
    private $ref_to_amt_exclusive = '0';
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

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setPmgwRefCurrencyId($pmgw_ref_currency_id)
    {
        $this->pmgw_ref_currency_id = $pmgw_ref_currency_id;
    }

    public function getPmgwRefCurrencyId()
    {
        return $this->pmgw_ref_currency_id;
    }

    public function setRefFromAmt($ref_from_amt)
    {
        $this->ref_from_amt = $ref_from_amt;
    }

    public function getRefFromAmt()
    {
        return $this->ref_from_amt;
    }

    public function setRefToAmtExclusive($ref_to_amt_exclusive)
    {
        $this->ref_to_amt_exclusive = $ref_to_amt_exclusive;
    }

    public function getRefToAmtExclusive()
    {
        return $this->ref_to_amt_exclusive;
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
