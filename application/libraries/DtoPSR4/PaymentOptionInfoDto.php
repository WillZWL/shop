<?php
class PaymentOptionInfoDto
{
    protected $platform_id;
    protected $set_id;
    protected $set_name;
    protected $card_code;
    protected $payment_gateway_id;
    protected $card_id;
    protected $card_name;
    protected $card_image;

    public function getPlatformId() {
        return $this->platform_id;
    }

    public function setPlatformId($platform_id) {
        $this->platform_id = $platform_id;
    }

    public function getSetId() {
        return $this->set_id;
    }

    public function setSetId($set_id) {
        $this->set_id = $set_id;
    }

    public function getSetName() {
        return $this->set_name;
    }

    public function setSetName($set_name) {
        $this->set_name = $set_name;
    }

    public function getCardCode() {
        return $this->card_code;
    }

    public function setCardCode($card_code) {
        $this->card_code = $card_code;
    }

    public function getPaymentGatewayId() {
        return $this->payment_gateway_id;
    }

    public function setPaymentGatewayId($payment_gateway_id) {
        $this->payment_gateway_id = $payment_gateway_id;
    }

    public function getCardId() {
        return $this->card_id;
    }

    public function setCardId($card_id) {
        $this->card_id = $card_id;
    }

    public function getCardName() {
        return $this->card_name;
    }

    public function setCardName($card_name) {
        $this->card_name = $card_name;
    }

    public function getCardImage() {
        return "/images/card/" . $this->card_image;
    }

    public function setCardImage($card_image) {
        $this->card_image = $card_image;
    }
}