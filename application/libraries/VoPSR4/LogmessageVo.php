<?php
class LogmessageVo extends \BaseVo
{
    private $id;
    private $type;
    private $file;
    private $linenumber;
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

    public function setLinenumber($linenumber)
    {
        if ($linenumber !== null) {
            $this->linenumber = $linenumber;
        }
    }

    public function getLinenumber()
    {
        return $this->linenumber;
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
