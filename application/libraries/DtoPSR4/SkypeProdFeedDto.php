<?php
class SkypeProdFeedDto
{
    private $sku;
    private $name;
    private $price;
    private $qty;
    private $in_stock;
    private $delivery_cost;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setInStock($in_stock)
    {
        $this->in_stock = $in_stock;
    }

    public function getInStock()
    {
        return $this->in_stock;
    }

    public function setDeliveryCost($delivery_cost)
    {
        $this->delivery_cost = $delivery_cost;
    }

    public function getDeliveryCost()
    {
        return $this->delivery_cost;
    }

}
