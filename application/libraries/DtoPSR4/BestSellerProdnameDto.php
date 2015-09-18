<?php
class BestSellerProdnameDto
{
    private $catid;
    private $rank;
    private $selection;
    private $name;
    private $mode;
    private $image;
    private $image_file;
    private $quantity;
    private $display_quantity;
    private $website_quantity;
    private $price;
    private $website_status;

    public function setCatid($catid)
    {
        $this->catid = $catid;
    }

    public function getCatid()
    {
        return $this->catid;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function setSelection($selection)
    {
        $this->selection = $selection;
    }

    public function getSelection()
    {
        return $this->selection;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImageFile($image_file)
    {
        $this->image_file = $image_file;
    }

    public function getImageFile()
    {
        return $this->image_file;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setDisplayQuantity($display_quantity)
    {
        $this->display_quantity = $display_quantity;
    }

    public function getDisplayQuantity()
    {
        return $this->display_quantity;
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

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

}
