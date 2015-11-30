<?php
class ProdOverviewWoShiptypeDto
{
    private $cat_id;
    private $website_quantity;
    private $price;
    private $prev_price;

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrevPrice($prev_price)
    {
        $this->prev_price = $prev_price;
    }

    public function getPrevPrice()
    {
        return $this->prev_price;
    }

}
