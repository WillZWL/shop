<?php
class ProductSpecGroupVo extends \BaseVo
{
    private $id;
    private $func_id = '';
    private $code = '';
    private $name = '';
    private $desc = '';
    private $priority = '9';
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

    public function setFuncId($func_id)
    {
        if ($func_id !== null) {
            $this->func_id = $func_id;
        }
    }

    public function getFuncId()
    {
        return $this->func_id;
    }

    public function setCode($code)
    {
        if ($code !== null) {
            $this->code = $code;
        }
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDesc($desc)
    {
        if ($desc !== null) {
            $this->desc = $desc;
        }
    }

    public function getDesc()
    {
        return $this->desc;
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
