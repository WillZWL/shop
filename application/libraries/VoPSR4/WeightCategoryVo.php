<?php
class WeightCategoryVo extends \BaseVo
{
    private $id;
    private $weight;

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

    public function setWeight($weight)
    {
        if ($weight !== null) {
            $this->weight = $weight;
        }
    }

    public function getWeight()
    {
        return $this->weight;
    }

}
