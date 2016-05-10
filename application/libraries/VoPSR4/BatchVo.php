<?php
class BatchVo extends \BaseVo
{
    private $id;
    private $func_name;
    private $status = 'N';
    private $listed = '1';
    private $remark;
    private $end_time;

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

    public function setFuncName($func_name)
    {
        if ($func_name !== null) {
            $this->func_name = $func_name;
        }
    }

    public function getFuncName()
    {
        return $this->func_name;
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

    public function setListed($listed)
    {
        if ($listed !== null) {
            $this->listed = $listed;
        }
    }

    public function getListed()
    {
        return $this->listed;
    }

    public function setRemark($remark)
    {
        if ($remark !== null) {
            $this->remark = $remark;
        }
    }

    public function getRemark()
    {
        return $this->remark;
    }

    public function setEndTime($end_time)
    {
        if ($end_time !== null) {
            $this->end_time = $end_time;
        }
    }

    public function getEndTime()
    {
        return $this->end_time;
    }

}
