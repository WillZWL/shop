<?php

class DisplayBannerConfigVo extends \BaseVo
{
    private $id;
    private $display_id;
    private $usage = 'PV';
    private $country_id;
    private $lang_id;
    private $position_id = '0';
    private $banner_type = 'I';
    private $height;
    private $width;
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setDisplayId($display_id)
    {
        if ($display_id !== null) {
            $this->display_id = $display_id;
        }
    }

    public function getDisplayId()
    {
        return $this->display_id;
    }

    public function setUsage($usage)
    {
        if ($usage !== null) {
            $this->usage = $usage;
        }
    }

    public function getUsage()
    {
        return $this->usage;
    }

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setLangId($lang_id)
    {
        if ($lang_id !== null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setPositionId($position_id)
    {
        if ($position_id !== null) {
            $this->position_id = $position_id;
        }
    }

    public function getPositionId()
    {
        return $this->position_id;
    }

    public function setBannerType($banner_type)
    {
        if ($banner_type !== null) {
            $this->banner_type = $banner_type;
        }
    }

    public function getBannerType()
    {
        return $this->banner_type;
    }

    public function setHeight($height)
    {
        if ($height !== null) {
            $this->height = $height;
        }
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWidth($width)
    {
        if ($width !== null) {
            $this->width = $width;
        }
    }

    public function getWidth()
    {
        return $this->width;
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
