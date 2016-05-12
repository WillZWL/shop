<?php

class DisplayVo extends \BaseVo
{
    private $id;
    private $name;
    private $display_name;
    private $banner_status = '0';
    private $lightbox_status = '0';
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

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
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

    public function setBannerStatus($banner_status)
    {
        if ($banner_status !== null) {
            $this->banner_status = $banner_status;
        }
    }

    public function getBannerStatus()
    {
        return $this->banner_status;
    }

    public function setLightboxStatus($lightbox_status)
    {
        if ($lightbox_status !== null) {
            $this->lightbox_status = $lightbox_status;
        }
    }

    public function getLightboxStatus()
    {
        return $this->lightbox_status;
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
