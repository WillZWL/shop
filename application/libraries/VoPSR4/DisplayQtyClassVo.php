<?php
class DisplayQtyClassVo extends \BaseVo
{
    private $id;
    private $price;
    private $price2;
    private $qty;
    private $qty2;
    private $drop_qty = '0';
    private $default_factor = '1.00';
    private $status = '1';


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice2($price2)
    {
        $this->price2 = $price2;
    }

    public function getPrice2()
    {
        return $this->price2;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setQty2($qty2)
    {
        $this->qty2 = $qty2;
    }

    public function getQty2()
    {
        return $this->qty2;
    }

    public function setDropQty($drop_qty)
    {
        $this->drop_qty = $drop_qty;
    }

    public function getDropQty()
    {
        return $this->drop_qty;
    }

    public function setDefaultFactor($default_factor)
    {
        $this->default_factor = $default_factor;
    }

    public function getDefaultFactor()
    {
        return $this->default_factor;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }



}
