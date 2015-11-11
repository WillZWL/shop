<?php
class RefundReasonReportTop5ReasonsDto
{
    private $id;
    private $reason;
    private $frequency;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($value)
    {
        $this->reason = $value;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setFrequency($value)
    {
        $this->frequency = $value;
    }

}

