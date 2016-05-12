<?php

class DisplayQtyFactorVo extends \BaseVo
{
    private $id;
    private $cat_id;
    private $class_id;
    private $factor = '1.00';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setCatId($cat_id)
    {
        if ($cat_id !== null) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setClassId($class_id)
    {
        if ($class_id !== null) {
            $this->class_id = $class_id;
        }
    }

    public function getClassId()
    {
        return $this->class_id;
    }

    public function setFactor($factor)
    {
        if ($factor !== null) {
            $this->factor = $factor;
        }
    }

    public function getFactor()
    {
        return $this->factor;
    }

}
