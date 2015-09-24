<?php
class PaymentOptionSetContentVo extends \BaseVo
{
    private $id;
    private $set_id;
    private $card_code;
    private $ref_currency;
    private $ref_from_amt;
    private $ref_to_amt_exclusive;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = '';
    private $modify_at;
    private $modify_by;

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

    public function setSetId($set_id)
    {
        $this->set_id = $set_id;
    }

    public function getSetId()
    {
        return $this->set_id;
    }

    public function setCardCode($card_code)
    {
        $this->card_code = $card_code;
    }

    public function getCardCode()
    {
        return $this->card_code;
    }

    public function setRefCurrency($ref_currency)
    {
        $this->ref_currency = $ref_currency;
    }

    public function getRefCurrency()
    {
        return $this->ref_currency;
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
