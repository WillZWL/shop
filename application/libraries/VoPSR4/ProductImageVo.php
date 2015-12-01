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
    private $vb_alt_text = '';
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku != null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPriority($priority)
    {
        if ($priority != null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setImage($image)
    {
        if ($image != null) {
            $this->image = $image;
        }
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setAltText($alt_text)
    {
        if ($alt_text != null) {
            $this->alt_text = $alt_text;
        }
    }

    public function getAltText()
    {
        return $this->alt_text;
    }

    public function setImageSaved($image_saved)
    {
        if ($image_saved != null) {
            $this->image_saved = $image_saved;
        }
    }

    public function getImageSaved()
    {
        return $this->image_saved;
    }

    public function setVbImage($vb_image)
    {
        if ($vb_image != null) {
            $this->vb_image = $vb_image;
        }
    }

    public function getVbImage()
    {
        return $this->vb_image;
    }

    public function setVbAltText($vb_alt_text)
    {
        if ($vb_alt_text != null) {
            $this->vb_alt_text = $vb_alt_text;
        }
    }

    public function getVbAltText()
    {
        return $this->vb_alt_text;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
