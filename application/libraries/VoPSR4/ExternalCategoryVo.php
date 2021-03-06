<?php

class ExternalCategoryVo extends \BaseVo
{
    private $id;
    private $ext_party;
    private $level;
    private $ext_id;
    private $ext_name;
    private $lang_id;
    private $country_id;
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

    public function setExtParty($ext_party)
    {
        if ($ext_party !== null) {
            $this->ext_party = $ext_party;
        }
    }

    public function getExtParty()
    {
        return $this->ext_party;
    }

    public function setLevel($level)
    {
        if ($level !== null) {
            $this->level = $level;
        }
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setExtId($ext_id)
    {
        if ($ext_id !== null) {
            $this->ext_id = $ext_id;
        }
    }

    public function getExtId()
    {
        return $this->ext_id;
    }

    public function setExtName($ext_name)
    {
        if ($ext_name !== null) {
            $this->ext_name = $ext_name;
        }
    }

    public function getExtName()
    {
        return $this->ext_name;
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

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
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
