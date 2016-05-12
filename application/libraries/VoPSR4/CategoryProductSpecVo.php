<?php

class CategoryProductSpecVo extends \BaseVo
{
    private $id;
    private $ps_id = '';
    private $cat_id;
    private $unit_id;
    private $priority = '9';
    private $status = '1';

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

    public function setPsId($ps_id)
    {
        if ($ps_id !== null) {
            $this->ps_id = $ps_id;
        }
    }

    public function getPsId()
    {
        return $this->ps_id;
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

    public function setUnitId($unit_id)
    {
        if ($unit_id !== null) {
            $this->unit_id = $unit_id;
        }
    }

    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setPriority($priority)
    {
        if ($priority !== null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
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
