<?php
class CustomClassificationMappingVo extends \BaseVo
{
    private $id;
    private $sub_cat_id;
    private $country_id;
    private $custom_class_id;

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

    public function setSubCatId($sub_cat_id)
    {
        if ($sub_cat_id !== null) {
            $this->sub_cat_id = $sub_cat_id;
        }
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
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

    public function setCustomClassId($custom_class_id)
    {
        if ($custom_class_id !== null) {
            $this->custom_class_id = $custom_class_id;
        }
    }

    public function getCustomClassId()
    {
        return $this->custom_class_id;
    }

}
