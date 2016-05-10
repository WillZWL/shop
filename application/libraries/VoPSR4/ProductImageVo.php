<?php
class ProductImageVo extends \BaseVo
{
    private $id;
    private $sku;
    private $priority = '1';
    private $image = '';
    private $alt_text = '';
    private $image_saved = '1';
    private $vb_image = '';
    private $stop_sync_image = '0';
    private $vb_alt_text = '';
    private $status = '1';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPriority($priority)
    {
        if ($priority !== null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setImage($image)
    {
        if ($image !== null) {
            $this->image = $image;
        }
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setAltText($alt_text)
    {
        if ($alt_text !== null) {
            $this->alt_text = $alt_text;
        }
    }

    public function getAltText()
    {
        return $this->alt_text;
    }

    public function setImageSaved($image_saved)
    {
        if ($image_saved !== null) {
            $this->image_saved = $image_saved;
        }
    }

    public function getImageSaved()
    {
        return $this->image_saved;
    }

    public function setVbImage($vb_image)
    {
        if ($vb_image !== null) {
            $this->vb_image = $vb_image;
        }
    }

    public function getVbImage()
    {
        return $this->vb_image;
    }

    public function setStopSyncImage($stop_sync_image)
    {
        //if ($stop_sync_image) {
            $this->stop_sync_image = $stop_sync_image;
        //}
    }

    public function getStopSyncImage()
    {
        return $this->stop_sync_image;
    }

    public function setVbAltText($vb_alt_text)
    {
        if ($vb_alt_text !== null) {
            $this->vb_alt_text = $vb_alt_text;
        }
    }

    public function getVbAltText()
    {
        return $this->vb_alt_text;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
