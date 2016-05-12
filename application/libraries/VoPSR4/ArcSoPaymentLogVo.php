<?php

class ArcSoPaymentLogVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $text_type = 'I';
    private $text;

    protected $primary_key = ['id'];
    protected $increment_field = '';

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

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setTextType($text_type)
    {
        if ($text_type !== null) {
            $this->text_type = $text_type;
        }
    }

    public function getTextType()
    {
        return $this->text_type;
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

}
