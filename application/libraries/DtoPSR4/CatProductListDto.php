<?php
class CatProductListDto
{
    private $sku;
    private $prod_name;
    private $image_ext;
    private $rrp_price;
    private $price;

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

    public function setImageExt($image_ext)
    {
        $this->image_ext = $image_ext;
    }

    public function getImageExt()
    {
        return $this->image_ext;
    }

    public function setRrpPrice($rrp_price)
    {
        $this->rrp_price = $rrp_price;
    }

    public function getRrpPrice()
    {
        return $this->rrp_price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

}