<?php

class BatchImportVo extends \BaseVo
{
    private $batch_id;
    private $function_name;
    private $status;
    private $remark;
    private $end_time = '0000-00-00 00:00:00';

    protected $primary_key = ['batch_id'];
    protected $increment_field = 'batch_id';

    public function setBatchId($batch_id)
    {
        if ($batch_id !== null) {
            $this->batch_id = $batch_id;
        }
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setFunctionName($function_name)
    {
        if ($function_name !== null) {
            $this->function_name = $function_name;
        }
    }

    public function getFunctionName()
    {
        return $this->function_name;
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
