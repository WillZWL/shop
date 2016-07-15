<?php

class BannerVo extends \BaseVo
{
    private $id;
    private $type = '1';
    private $location = '0';
    private $platform_id = '';
    private $image = '';
    private $image_alt = '';
    private $link = '';
    private $target_type = '1';
    private $line_no = '1';
    private $priority = '1';
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

    public function setLocation($location)
    {
        if ($location !== null) {
            $this->location = $location;
        }
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
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

    public function setImageAlt($image_alt)
    {
        if ($image_alt !== null) {
            $this->image_alt = $image_alt;
        }
    }

    public function getImageAlt()
    {
        return $this->image_alt;
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

    public function setTargetType($target_type)
    {
        if ($target_type !== null) {
            $this->target_type = $target_type;
        }
    }

    public function getTargetType()
    {
        return $this->target_type;
    }

    public function setLineNo($line_no)
    {
        if ($line_no !== null) {
            $this->line_no = $line_no;
        }
    }

    public function getLineNo()
    {
        return $this->line_no;
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
