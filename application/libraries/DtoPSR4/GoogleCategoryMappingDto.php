<?php
class GoogleCategoryMappingDto
{
    private $category_id;
    private $name;
    private $ext_id;
    private $country_id;
    private $google_category_name;
    private $main_category;

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setExtId($ext_id)
    {
        $this->ext_id = $ext_id;
    }

    public function getExtId()
    {
        return $this->ext_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setGoogleCategoryName($google_category_name)
    {
        $this->google_category_name = $google_category_name;
    }

    public function getGoogleCategoryName()
    {
        return $this->google_category_name;
    }

    public function setMainCategory($main_category)
    {
        $this->main_category = $main_category;
    }

    public function getMainCategory()
    {
        return $this->main_category;
    }

}
