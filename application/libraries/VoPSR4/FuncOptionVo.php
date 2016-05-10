<?php
class FuncOptionVo extends \BaseVo
{
    private $id;
    private $func_id;
    private $lang_id = 'en';
    private $text;
    private $value;
    private $priority = '0';
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

    public function setLangId($lang_id)
    {
        if ($lang_id !== null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setText($text)
    {
        if ($text !== null) {
            $this->text = $text;
        }
    }

    public function getText()
    {
        return $this->text;
    }

    public function setValue($value)
    {
        if ($value !== null) {
            $this->value = $value;
        }
    }

    public function getValue()
    {
        return $this->value;
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
