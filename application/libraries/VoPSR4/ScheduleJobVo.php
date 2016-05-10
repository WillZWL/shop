<?php
class ScheduleJobVo extends \BaseVo
{
    private $id;
    private $schedule_job_id;
    private $name = '';
    private $last_access_time = '0000-00-00 00:00:00';
    private $status;


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

    public function setScheduleJobId($schedule_job_id)
    {
        if ($schedule_job_id !== null) {
            $this->schedule_job_id = $schedule_job_id;
        }
    }

    public function getScheduleJobId()
    {
        return $this->schedule_job_id;
    }

    public function setName($name)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLastAccessTime($last_access_time)
    {
        if ($last_access_time !== null) {
            $this->last_access_time = $last_access_time;
        }
    }

    public function getLastAccessTime()
    {
        return $this->last_access_time;
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
