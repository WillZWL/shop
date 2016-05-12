<?php

class AdbannerVo extends \BaseVo
{
    private $id;
    private $platform_id;
    private $cat_id;
    private $bannerfile;
    private $bannerlink;

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

    public function setBannerfile($bannerfile)
    {
        if ($bannerfile !== null) {
            $this->bannerfile = $bannerfile;
        }
    }

    public function getBannerfile()
    {
        return $this->bannerfile;
    }

    public function setBannerlink($bannerlink)
    {
        if ($bannerlink !== null) {
            $this->bannerlink = $bannerlink;
        }
    }

    public function getBannerlink()
    {
        return $this->bannerlink;
    }

}
