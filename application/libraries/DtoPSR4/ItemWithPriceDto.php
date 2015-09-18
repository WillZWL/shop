<?php
class ItemWithPriceDto
{
    private $prod_sku;
    private $discount;
    private $item_sku;
    private $price;
    private $listing_status;

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setListingStatus($listing_status)
    {
        $this->listing_status = $listing_status;
    }

    public function getListingStatus()
    {
        return $this->listing_status;
    }

}
