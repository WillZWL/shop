<?php

class ColourExtendVo extends \BaseVo
{
    private $id;
    private $colour_id;
    private $lang_id = '';
    private $colour_name = '';

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

}
