<?php
class ShipmentCsvDto
{
    private $shipment_id;
    private $sku;
    private $prod_name;
    private $qty;
    private $courier;
    private $tracking_no;

    public function setShipmentId($shipment_id)
    {
        $this->shipment_id = $shipment_id;
    }

    public function getShipmentId()
    {
        return $this->shipment_id;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setCourier($courier)
    {
        $this->courier = $courier;
    }

    public function getCourier()
    {
        return $this->courier;
    }

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

}
