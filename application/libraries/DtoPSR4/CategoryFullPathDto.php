<?php
class CategoryFullPathDto extends \BaseDto
{
    private $cat_id;
    private $name;
    private $level;
    private $top_name;
    private $top_level;
    private $top_top_name;
    private $top_top_level;

    public function setCatId($cat_id)
    {
        $this->cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setName($name)
    {
        $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLevel($level)
    {
        $this->level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setTopName($top_name)
    {
        $this->top_name;
    }

    public function getTopName()
    {
        return $this->top_name;
    }

    public function setTopLevel($top_level)
    {
        $this->top_level;
    }

    public function getTopLevel()
    {
        return $this->top_level;
    }

    public function setTopTopName($top_top_name)
    {
        $this->top_top_name;
    }

    public function getTopTopName()
    {
        return $this->top_top_name;
    }

    public function setTopTopLevel($top_top_level)
    {
        $this->top_top_level;
    }

    public function getTopTopLevel()
    {
        return $this->top_top_level;
    }
}
