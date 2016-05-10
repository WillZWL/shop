<?php
class CountryExtVo extends \BaseVo
{
    private $id;
    private $cid;
    private $lang_id;
    private $name = '';

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

    public function setCid($cid)
    {
        if ($cid !== null) {
            $this->cid = $cid;
        }
    }

    public function getCid()
    {
        return $this->cid;
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

}
