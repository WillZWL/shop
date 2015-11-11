<?php
class RaGroupContentVo extends  \BaseVo
{

    //class variable
    private $group_id;
    private $lang_id;
    private $group_display_name;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

	//private $primary_key = ['id'];
    //private $increment_field = 'id';
	
    //primary key
    private $primary_key = array("group_id", "lang_id");

    //auo increment
    private $increment_field = "";

    //instance method
    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupId($value)
    {
        $this->group_id = $value;
        return $this;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setLangId($value)
    {
        $this->lang_id = $value;
        return $this;
    }

    public function getGroupDisplayName()
    {
        return $this->group_display_name;
    }

    public function setGroupDisplayName($value)
    {
        $this->group_display_name = $value;
        return $this;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}

?>