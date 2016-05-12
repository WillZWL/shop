<?php

class DeliveryOptionVo extends \BaseVo
{
    private $id;
    private $lang_id;
    private $courier_id;
    private $display_name;

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

    public function setCourierId($courier_id)
    {
        if ($courier_id !== null) {
            $this->courier_id = $courier_id;
        }
    }

    public function getCourierId()
    {
        return $this->courier_id;
    }

    public function setDisplayName($display_name)
    {
        if ($display_name !== null) {
            $this->display_name = $display_name;
        }
    }

    public function getDisplayName()
    {
        return $this->display_name;
    }

}
