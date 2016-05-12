<?php

class CourierFeedVo extends \BaseVo
{
    private $id;
    private $so_no_str;
    private $courier_id;
    private $mawb = '';
    private $exec = '0';
    private $comment = '';

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

    public function setSoNoStr($so_no_str)
    {
        if ($so_no_str !== null) {
            $this->so_no_str = $so_no_str;
        }
    }

    public function getSoNoStr()
    {
        return $this->so_no_str;
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

    public function setMawb($mawb)
    {
        if ($mawb !== null) {
            $this->mawb = $mawb;
        }
    }

    public function getMawb()
    {
        return $this->mawb;
    }

    public function setExec($exec)
    {
        if ($exec !== null) {
            $this->exec = $exec;
        }
    }

    public function getExec()
    {
        return $this->exec;
    }

    public function setComment($comment)
    {
        if ($comment !== null) {
            $this->comment = $comment;
        }
    }

    public function getComment()
    {
        return $this->comment;
    }

}
