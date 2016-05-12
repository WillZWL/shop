<?php

class FestiveDealVo extends \BaseVo
{
    private $id;
    private $link_name;
    private $display_name;
    private $display = 'Y';
    private $start_date = '0000-00-00 00:00:00';
    private $end_date = '0000-00-00 00:00:00';
    private $banner_file;
    private $banner_link;

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

    public function setLinkName($link_name)
    {
        if ($link_name !== null) {
            $this->link_name = $link_name;
        }
    }

    public function getLinkName()
    {
        return $this->link_name;
    }

    public function setDisplayName($display_name)
    {
        if ($display_name !== null) {
            $this->display_name = $display_name;
        }
    }

    public function getDisplayName()
    {
        return $this->display_name;
    }

    public function setDisplay($display)
    {
        if ($display !== null) {
            $this->display = $display;
        }
    }

    public function getDisplay()
    {
        return $this->display;
    }

    public function setStartDate($start_date)
    {
        if ($start_date !== null) {
            $this->start_date = $start_date;
        }
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setEndDate($end_date)
    {
        if ($end_date !== null) {
            $this->end_date = $end_date;
        }
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setBannerFile($banner_file)
    {
        if ($banner_file !== null) {
            $this->banner_file = $banner_file;
        }
    }

    public function getBannerFile()
    {
        return $this->banner_file;
    }

    public function setBannerLink($banner_link)
    {
        if ($banner_link !== null) {
            $this->banner_link = $banner_link;
        }
    }

    public function getBannerLink()
    {
        return $this->banner_link;
    }

}
