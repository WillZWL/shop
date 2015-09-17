<?php
class ProductShippingOverrideDto
{
    private $platform_id;
    private $sku;
    private $ship_option;
    private $do_not_ship;
    private $type;
    private $shipping_charge;

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setShipOption($ship_option)
    {
        $this->ship_option = $ship_option;
    }

    public function getShipOption()
    {
        return $this->ship_option;
    }

    public function setDoNotShip($do_not_ship)
    {
        $this->do_not_ship = $do_not_ship;
    }

    public function getDoNotShip()
    {
        return $this->do_not_ship;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setShippingCharge($shipping_charge)
    {
        $this->shipping_charge = $shipping_charge;
    }

    public function getShippingCharge()
    {
        return $this->shipping_charge;
    }

}
