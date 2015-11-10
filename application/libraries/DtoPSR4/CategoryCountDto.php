<?php
class CategoryCountDto
{
    private $id;
    private $name;
    private $count_row;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getCountRow()
    {
        return $this->count_row;
    }

    public function setCountRow($value)
    {
        $this->count_row = $value;
    }

}
