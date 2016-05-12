<?php

class BannerVo extends \BaseVo
{
    private $cat_id;
    private $type = '0';
    private $usage = 'PV';
    private $image_file;
    private $flash_file;
    private $link;
    private $link_type = 'E';
    private $status = 'A';

    protected $primary_key = ['cat_id', 'type', 'usage'];
    protected $increment_field = '';

    public function setCatId($cat_id)
    {
        if ($cat_id !== null) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
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

    public function setImageFile($image_file)
    {
        if ($image_file !== null) {
            $this->image_file = $image_file;
        }
    }

    public function getImageFile()
    {
        return $this->image_file;
    }

    public function setFlashFile($flash_file)
    {
        if ($flash_file !== null) {
            $this->flash_file = $flash_file;
        }
    }

    public function getFlashFile()
    {
        return $this->flash_file;
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
