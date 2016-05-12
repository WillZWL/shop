<?php

class CustomerServiceInfoVo extends \BaseVo
{
    private $id;
    private $lang_id;
    private $platform_id;
    private $type = 'WEBSITE';
    private $title;
    private $content;
    private $short_text;
    private $long_text;
    private $short_text_status = '1';
    private $long_text_status = '1';
    private $operating_hours;

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

    public function setTitle($title)
    {
        if ($title !== null) {
            $this->title = $title;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent($content)
    {
        if ($content !== null) {
            $this->content = $content;
        }
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setShortText($short_text)
    {
        if ($short_text !== null) {
            $this->short_text = $short_text;
        }
    }

    public function getShortText()
    {
        return $this->short_text;
    }

    public function setLongText($long_text)
    {
        if ($long_text !== null) {
            $this->long_text = $long_text;
        }
    }

    public function getLongText()
    {
        return $this->long_text;
    }

    public function setShortTextStatus($short_text_status)
    {
        if ($short_text_status !== null) {
            $this->short_text_status = $short_text_status;
        }
    }

    public function getShortTextStatus()
    {
        return $this->short_text_status;
    }

    public function setLongTextStatus($long_text_status)
    {
        if ($long_text_status !== null) {
            $this->long_text_status = $long_text_status;
        }
    }

    public function getLongTextStatus()
    {
        return $this->long_text_status;
    }

    public function setOperatingHours($operating_hours)
    {
        if ($operating_hours !== null) {
            $this->operating_hours = $operating_hours;
        }
    }

    public function getOperatingHours()
    {
        return $this->operating_hours;
    }

}
