<?php
class SoRefundScoreVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $score;
    private $status = '1';

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
