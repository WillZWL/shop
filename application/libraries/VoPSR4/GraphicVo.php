<?php

class GraphicVo extends \BaseVo
{
    private $id;
    private $type;
    private $location;
    private $file;
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = '';

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

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setLocation($location)
    {
        if ($location !== null) {
            $this->location = $location;
        }
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setFile($file)
    {
        if ($file !== null) {
            $this->file = $file;
        }
    }

    public function getFile()
    {
        return $this->file;
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
