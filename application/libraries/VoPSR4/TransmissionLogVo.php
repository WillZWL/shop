<?php
class TransmissionLogVo extends \BaseVo
{
    private $id;
    private $func_name;
    private $message;

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

    public function setFuncName($func_name)
    {
        if ($func_name !== null) {
            $this->func_name = $func_name;
        }
    }

    public function getFuncName()
    {
        return $this->func_name;
    }

    public function setMessage($message)
    {
        if ($message !== null) {
            $this->message = $message;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

}
