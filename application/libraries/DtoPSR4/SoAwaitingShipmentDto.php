<?php
class SoAwaitingShipmentDto
{
    private $sku;
    private $qty;
    private $ext_ref_sku;
    private $ext_sys;
    private $warehouse;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setExtRefSku($ext_ref_sku)
    {
        $this->ext_ref_sku = $ext_ref_sku;
    }

    public function getExtRefSku()
    {
        return $this->ext_ref_sku;
    }

    public function setExtSys($ext_sys)
    {
        $this->ext_sys = $ext_sys;
    }

    public function getExtSys()
    {
        return $this->ext_sys;
    }

    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function getWarehouse()
    {
        return $this->warehouse;
    }

}
