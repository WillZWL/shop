<?php
class DisplayQtyFactorVo extends \BaseVo
{
    private $id;
    private $cat_id;
    private $class_id;
    private $factor = '1.00';


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setClassId($class_id)
    {
        $this->class_id = $class_id;
    }

    public function getClassId()
    {
        return $this->class_id;
    }

    public function setFactor($factor)
    {
        $this->factor = $factor;
    }

    public function getFactor()
    {
        return $this->factor;
    }



}
