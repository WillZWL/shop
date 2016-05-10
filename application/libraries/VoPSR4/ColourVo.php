<?php
class ColourVo extends \BaseVo
{
    private $id;
    private $colour_id;
    private $colour_name;
    private $status = '1';

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

    public function setColourId($colour_id)
    {
        if ($colour_id !== null) {
            $this->colour_id = $colour_id;
        }
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourName($colour_name)
    {
        if ($colour_name !== null) {
            $this->colour_name = $colour_name;
        }
    }

    public function getColourName()
    {
        return $this->colour_name;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
