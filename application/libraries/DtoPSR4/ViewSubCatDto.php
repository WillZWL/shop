<?php
class ViewSubCatDto
{
    private $sub_sub_cat_id;
    private $sub_cat_name;
    private $sub_cat_id;
    private $name;
    private $description;
    private $cat_name;
    private $cat_id;
    private $status;
    private $create_on;
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function getSubSubCatId()
    {
        return $this->sub_sub_cat_id;
    }

    public function setSubSubCatId($value)
    {
        $this->sub_sub_cat_id = $value;
    }

    public function getSubCatId()
    {
        return $this->sub_cat_id;
    }

    public function setSubCatId($value)
    {
        $this->sub_cat_id = $value;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatId($value)
    {
        $this->cat_id = $value;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setSubCatName($value)
    {
        $this->sub_cat_name = $value;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setCatName($value)
    {
        $this->cat_name = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($value)
    {
        $this->create_by = $value;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($value)
    {
        $this->modify_on = $value;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($value)
    {
        $this->modify_at = $value;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($value)
    {
        $this->modify_by = $value;
    }
}
