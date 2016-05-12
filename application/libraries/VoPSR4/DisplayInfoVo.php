<?php

class DisplayInfoVo extends \BaseVo
{
    private $display_id;
    private $lang_id;
    private $page_title;
    private $meta_title;
    private $meta_description;
    private $meta_keyword;

    protected $primary_key = ['display_id', 'lang_id'];
    protected $increment_field = '';

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

    public function setPageTitle($page_title)
    {
        if ($page_title !== null) {
            $this->page_title = $page_title;
        }
    }

    public function getPageTitle()
    {
        return $this->page_title;
    }

    public function setMetaTitle($meta_title)
    {
        if ($meta_title !== null) {
            $this->meta_title = $meta_title;
        }
    }

    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    public function setMetaDescription($meta_description)
    {
        if ($meta_description !== null) {
            $this->meta_description = $meta_description;
        }
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function setMetaKeyword($meta_keyword)
    {
        if ($meta_keyword !== null) {
            $this->meta_keyword = $meta_keyword;
        }
    }

    public function getMetaKeyword()
    {
        return $this->meta_keyword;
    }

}
