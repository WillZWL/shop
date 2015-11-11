<?php
class AmazonFulfillmentDto
{
    private $tracking_no = " ";
    private $shippingmethod = "International";
    private $reserve_1 = " ";
    private $reserve_2 = " ";
    private $reserve_3 = " ";
    private $reserve_4 = " ";
    private $shipdate = "2015-09-17 05:00:42";
    private $courier_id = " ";
    private $carriercode = "OTHER";
    private $qty = "-1";
    private $ext_item_cd = " ";
    private $prod_sku = " ";
    private $platform_order_id = " ";
    private $so_no = " ";

    public function setTrackingNo($tracking_no)
    {
        $this->tracking_no = $tracking_no;
    }

    public function getTrackingNo()
    {
        return $this->tracking_no;
    }

    public function setShippingmethod($shippingmethod)
    {
        $this->shippingmethod = $shippingmethod;
    }

    public function getShippingmethod()
    {
        return $this->shippingmethod;
    }

    public function setReserve1($reserve_1)
    {
        $this->reserve_1 = $reserve_1;
    }

    public function getReserve1()
    {
        return $this->reserve_1;
    }

    public function setReserve2($reserve_2)
    {
        $this->reserve_2 = $reserve_2;
    }

    public function getReserve2()
    {
        return $this->reserve_2;
    }

    public function setReserve3($reserve_3)
    {
        $this->reserve_3 = $reserve_3;
    }

    public function getReserve3()
    {
        return $this->reserve_3;
    }

    public function setReserve4($reserve_4)
    {
        $this->reserve_4 = $reserve_4;
    }

    public function getReserve4()
    {
        return $this->reserve_4;
    }

    public function setShipdate($shipdate)
    {
        $this->shipdate = $shipdate;
    }

    public function getShipdate()
    {
        return $this->shipdate;
    }

    public function setCourierId($courier_id)
    {
        $this->courier_id = $courier_id;
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setCarriercode($carriercode)
    {
        $this->carriercode = $carriercode;
    }

    public function getCarriercode()
    {
        return $this->carriercode;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setExtItemCd($ext_item_cd)
    {
        $this->ext_item_cd = $ext_item_cd;
    }

    public function getExtItemCd()
    {
        return $this->ext_item_cd;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setPlatformOrderId($platform_order_id)
    {
        $this->platform_order_id = $platform_order_id;
    }

    public function getPlatformOrderId()
    {
        return $this->platform_order_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

}
