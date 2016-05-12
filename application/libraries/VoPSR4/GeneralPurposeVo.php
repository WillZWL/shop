<?php

class GeneralPurposeVo extends \BaseVo
{
    private $so_no;
    private $purpose;
    private $status = '0';
    private $comment;

    protected $primary_key = ['so_no', 'purpose'];
    protected $increment_field = '';

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

    public function setPurpose($purpose)
    {
        if ($purpose !== null) {
            $this->purpose = $purpose;
        }
    }

    public function getPurpose()
    {
        return $this->purpose;
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
