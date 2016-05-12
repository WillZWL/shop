<?php

class DisplayCategoryBannerVo extends \BaseVo
{
    private $id;
    private $catid;
    private $display_banner_config_id;
    private $display_id;
    private $position_id = '1';
    private $slide_id = '0';
    private $country_id;
    private $lang_id;
    private $time_interval = '0';
    private $image_id;
    private $flash_id;
    private $lytebox_content;
    private $height;
    private $width;
    private $usage = 'PV';
    private $link_type = 'E';
    private $link;
    private $priority = '9';
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

    public function setCatid($catid)
    {
        if ($catid !== null) {
            $this->catid = $catid;
        }
    }

    public function getCatid()
    {
        return $this->catid;
    }

    public function setDisplayBannerConfigId($display_banner_config_id)
    {
        if ($display_banner_config_id !== null) {
            $this->display_banner_config_id = $display_banner_config_id;
        }
    }

    public function getDisplayBannerConfigId()
    {
        return $this->display_banner_config_id;
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

    public function setSlideId($slide_id)
    {
        if ($slide_id !== null) {
            $this->slide_id = $slide_id;
        }
    }

    public function getSlideId()
    {
        return $this->slide_id;
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

    public function setTimeInterval($time_interval)
    {
        if ($time_interval !== null) {
            $this->time_interval = $time_interval;
        }
    }

    public function getTimeInterval()
    {
        return $this->time_interval;
    }

    public function setImageId($image_id)
    {
        if ($image_id !== null) {
            $this->image_id = $image_id;
        }
    }

    public function getImageId()
    {
        return $this->image_id;
    }

    public function setFlashId($flash_id)
    {
        if ($flash_id !== null) {
            $this->flash_id = $flash_id;
        }
    }

    public function getFlashId()
    {
        return $this->flash_id;
    }

    public function setLyteboxContent($lytebox_content)
    {
        if ($lytebox_content !== null) {
            $this->lytebox_content = $lytebox_content;
        }
    }

    public function getLyteboxContent()
    {
        return $this->lytebox_content;
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

    public function setLinkType($link_type)
    {
        if ($link_type !== null) {
            $this->link_type = $link_type;
        }
    }

    public function getLinkType()
    {
        return $this->link_type;
    }

    public function setLink($link)
    {
        if ($link !== null) {
            $this->link = $link;
        }
    }

    public function getLink()
    {
        return $this->link;
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
