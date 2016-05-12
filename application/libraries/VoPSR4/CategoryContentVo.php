<?php

class CategoryContentVo extends \BaseVo
{
    private $id;
    private $cat_id;
    private $lang_id;
    private $image;
    private $flash;
    private $text;
    private $latest_news;
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

    public function setText($text)
    {
        if ($text !== null) {
            $this->text = $text;
        }
    }

    public function getText()
    {
        return $this->text;
    }

    public function setLatestNews($latest_news)
    {
        if ($latest_news !== null) {
            $this->latest_news = $latest_news;
        }
    }

    public function getLatestNews()
    {
        return $this->latest_news;
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
