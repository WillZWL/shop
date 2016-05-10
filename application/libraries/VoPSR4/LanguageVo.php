<?php
class LanguageVo extends \BaseVo
{
    private $id;
    private $lang_id;
    private $lang_name;
    private $description = '';
    private $status = '1';
    private $char_set = 'UTF8';


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

    public function setLangName($lang_name)
    {
        if ($lang_name !== null) {
            $this->lang_name = $lang_name;
        }
    }

    public function getLangName()
    {
        return $this->lang_name;
    }

    public function setDescription($description)
    {
        if ($description !== null) {
            $this->description = $description;
        }
    }

    public function getDescription()
    {
        return $this->description;
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

    public function setCharSet($char_set)
    {
        if ($char_set !== null) {
            $this->char_set = $char_set;
        }
    }

    public function getCharSet()
    {
        return $this->char_set;
    }

}
