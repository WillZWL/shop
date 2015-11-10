<?php
class BannerCatListDto
{
    private $id;
    private $name;
    private $level;
    private $pv_cnt;
    private $pb_cnt;
    private $status;
    private $count_row;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setPvCnt($pv_cnt)
    {
        $this->pv_cnt = $pv_cnt;
    }

    public function getPvCnt()
    {
        return $this->pv_cnt;
    }

    public function setPbCnt($pb_cnt)
    {
        $this->pb_cnt = $pb_cnt;
    }

    public function getPbCnt()
    {
        return $this->pb_cnt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCountRow($count_row)
    {
        $this->count_row = $count_row;
    }

    public function getCountRow()
    {
        return $this->count_row;
    }

}
