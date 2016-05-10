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

    public function setSetId($set_id)
    {
        if ($set_id !== null) {
            $this->set_id = $set_id;
        }
    }

    public function getSetId()
    {
        return $this->set_id;
    }

    public function setCardCode($card_code)
    {
        if ($card_code !== null) {
            $this->card_code = $card_code;
        }
    }

    public function getCardCode()
    {
        return $this->card_code;
    }

    public function setRefCurrency($ref_currency)
    {
        if ($ref_currency !== null) {
            $this->ref_currency = $ref_currency;
        }
    }

    public function getRefCurrency()
    {
        return $this->ref_currency;
    }

    public function setRefFromAmt($ref_from_amt)
    {
        if ($ref_from_amt !== null) {
            $this->ref_from_amt = $ref_from_amt;
        }
    }

    public function getRefFromAmt()
    {
        return $this->ref_from_amt;
    }

    public function setRefToAmtExclusive($ref_to_amt_exclusive)
    {
        if ($ref_to_amt_exclusive !== null) {
            $this->ref_to_amt_exclusive = $ref_to_amt_exclusive;
        }
    }

    public function getRefToAmtExclusive()
    {
        return $this->ref_to_amt_exclusive;
    }

    public function setPriority($priority)
    {
        if ($priority !== null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
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
