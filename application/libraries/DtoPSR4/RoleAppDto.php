<?php
class RoleAppDto
{
    private $id;
    private $url;
    private $app_name;
    private $parent_app_id;
    private $description;
    private $display_order;
    private $role_id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setAppName($app_name)
    {
        $this->app_name = $app_name;
    }

    public function getAppName()
    {
        return $this->app_name;
    }

    public function setParentAppId($parent_app_id)
    {
        $this->parent_app_id = $parent_app_id;
    }

    public function getParentAppId()
    {
        return $this->parent_app_id;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDisplayOrder($display_order)
    {
        $this->display_order = $display_order;
    }

    public function getDisplayOrder()
    {
        return $this->display_order;
    }

    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

}
