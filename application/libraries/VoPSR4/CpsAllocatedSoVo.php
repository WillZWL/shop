<?php

class CpsAllocatedSoVo extends \BaseVo
{
    private $date;
    private $so_no;
    private $score;
    private $status = '1';

    protected $primary_key = ['date', 'so_no'];
    protected $increment_field = '';

    public function setDate($date)
    {
        if ($date !== null) {
            $this->date = $date;
        }
    }

    public function getDate()
    {
        return $this->date;
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

    public function setScore($score)
    {
        if ($score !== null) {
            $this->score = $score;
        }
    }

    public function getScore()
    {
        return $this->score;
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
