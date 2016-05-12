<?php

class ApplicationVo extends \BaseVo
{
    private $id;
    private $app_name;
    private $parent_app_id;
    private $description;
    private $display_order = '0';
    private $status = '0';
    private $display_row = '0';
    private $url;
    private $app_group_id;

    protected $primary_key = ['id'];
    protected $increment_field = '';

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

    public function setAppName($app_name)
    {
        if ($app_name !== null) {
            $this->app_name = $app_name;
        }
    }

    public function getAppName()
    {
        return $this->app_name;
    }

    public function setParentAppId($parent_app_id)
    {
        if ($parent_app_id !== null) {
            $this->parent_app_id = $parent_app_id;
        }
    }

    public function getParentAppId()
    {
        return $this->parent_app_id;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDisplayOrder($display_order)
    {
        if ($display_order !== null) {
            $this->display_order = $display_order;
        }
    }

    public function getDisplayOrder()
    {
        return $this->display_order;
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

    public function setDisplayRow($display_row)
    {
        if ($display_row !== null) {
            $this->display_row = $display_row;
        }
    }

    public function getDisplayRow()
    {
        return $this->display_row;
    }

    public function setUrl($url)
    {
        if ($url !== null) {
            $this->url = $url;
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setAppGroupId($app_group_id)
    {
        if ($app_group_id !== null) {
            $this->app_group_id = $app_group_id;
        }
    }

    public function getAppGroupId()
    {
        return $this->app_group_id;
    }

}
