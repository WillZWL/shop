<?php
class TopViewVideoProdnameDto
{
    private $catid;
    private $rank;
    private $ref_id;
    private $video_type;
    private $src;
    private $sku;
    private $name;
    private $mode;
    private $image;
    private $image_file;
    private $quantity;
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

    public function setRefId($ref_id)
    {
        $this->ref_id = $ref_id;
    }

    public function getRefId()
    {
        return $this->ref_id;
    }

    public function setVideoType($video_type)
    {
        $this->video_type = $video_type;
    }

    public function getVideoType()
    {
        return $this->video_type;
    }

    public function setSrc($src)
    {
        $this->src = $src;
    }

    public function getSrc()
    {
        return $this->src;
    }

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
