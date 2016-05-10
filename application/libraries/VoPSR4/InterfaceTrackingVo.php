<?php
class InterfaceTrackingVo extends \BaseVo
{
    private $id;
    private $schedule_job_id;
    private $start_time = '0000-00-00 00:00:00';
    private $end_time = '0000-00-00 00:00:00';
    private $status = 'N';


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

    public function setStartTime($start_time)
    {
        if ($start_time !== null) {
            $this->start_time = $start_time;
        }
    }

    public function getStartTime()
    {
        return $this->start_time;
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
