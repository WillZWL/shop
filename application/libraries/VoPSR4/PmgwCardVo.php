<?php
class PmgwCardVo extends \BaseVo
{
    private $id;
    private $code;
    private $payment_gateway_id = 'moneybookers';
    private $card_id;
    private $card_name;
    private $card_image = '';
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

    public function setCode($code)
    {
        if ($code !== null) {
            $this->code = $code;
        }
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id !== null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setCardId($card_id)
    {
        if ($card_id !== null) {
            $this->card_id = $card_id;
        }
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function setCardName($card_name)
    {
        if ($card_name !== null) {
            $this->card_name = $card_name;
        }
    }

    public function getCardName()
    {
        return $this->card_name;
    }

    public function setCardImage($card_image)
    {
        if ($card_image !== null) {
            $this->card_image = $card_image;
        }
    }

    public function getCardImage()
    {
        return $this->card_image;
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
