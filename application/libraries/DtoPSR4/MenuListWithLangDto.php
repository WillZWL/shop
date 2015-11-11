<?php
class MenuListWithLangDto
{
    private $id;
    private $name;
    private $lang_id;
    private $level;
    private $parent_cat_id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setParentCatId($parent_cat_id)
    {
        $this->parent_cat_id = $parent_cat_id;
    }

    public function getParentCatId()
    {
        return $this->parent_cat_id;
    }

}
