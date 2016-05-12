<?php

class CategoryBannerVo extends \BaseVo
{
    private $id;
    private $cat_id;
    private $lang_id;
    private $country_id;
    private $image;
    private $flash;
    private $priority;

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

    public function setFlash($flash)
    {
        if ($flash !== null) {
            $this->flash = $flash;
        }
    }

    public function getFlash()
    {
        return $this->flash;
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

}
