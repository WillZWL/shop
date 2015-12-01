<?php
class PaymentOptionSetContentVo extends \BaseVo
{
    private $id;
    private $set_id;
    private $card_code;
    private $ref_currency;
    private $ref_from_amt;
    private $ref_to_amt_exclusive;
    private $priority = '0';
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
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSetId($set_id)
    {
        if ($set_id != null) {
            $this->set_id = $set_id;
        }
    }

    public function getSetId()
    {
        return $this->set_id;
    }

    public function setCardCode($card_code)
    {
        if ($card_code != null) {
            $this->card_code = $card_code;
        }
    }

    public function getCardCode()
    {
        return $this->card_code;
    }

    public function setRefCurrency($ref_currency)
    {
        if ($ref_currency != null) {
            $this->ref_currency = $ref_currency;
        }
    }

    public function getRefCurrency()
    {
        return $this->ref_currency;
    }

    public function setRefFromAmt($ref_from_amt)
    {
        if ($ref_from_amt != null) {
            $this->ref_from_amt = $ref_from_amt;
        }
    }

    public function getRefFromAmt()
    {
        return $this->ref_from_amt;
    }

    public function setRefToAmtExclusive($ref_to_amt_exclusive)
    {
        if ($ref_to_amt_exclusive != null) {
            $this->ref_to_amt_exclusive = $ref_to_amt_exclusive;
        }
    }

    public function getRefToAmtExclusive()
    {
        return $this->ref_to_amt_exclusive;
    }

    public function setPriority($priority)
    {
        if ($priority != null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
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
