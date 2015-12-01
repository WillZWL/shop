<?php
class PaymentOptionCardVo extends \BaseVo
{
    private $id;
    private $code;
    private $payment_gateway_id = 'paypal';
    private $card_id;
    private $card_name;
    private $card_image;
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

    public function setCode($code)
    {
        if ($code != null) {
            $this->code = $code;
        }
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id != null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setCardId($card_id)
    {
        if ($card_id != null) {
            $this->card_id = $card_id;
        }
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function setCardName($card_name)
    {
        if ($card_name != null) {
            $this->card_name = $card_name;
        }
    }

    public function getCardName()
    {
        return $this->card_name;
    }

    public function setCardImage($card_image)
    {
        if ($card_image != null) {
            $this->card_image = $card_image;
        }
    }

    public function getCardImage()
    {
        return $this->card_image;
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
