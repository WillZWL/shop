<?php
class ImportInfoDto
{
    private $trans_id;
    private $batch_id;
    private $status;
    private $failed_reason;
    private $has_error;
    private $column;
    private $error_code;
    private $error_message;

    public function setTransId($trans_id)
    {
        $this->trans_id = $trans_id;
    }

    public function getTransId()
    {
        return $this->trans_id;
    }

    public function setBatchId($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function getBatchId()
    {
        return $this->batch_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setFailedReason($failed_reason)
    {
        $this->failed_reason = $failed_reason;
    }

    public function getFailedReason()
    {
        return $this->failed_reason;
    }

    public function setHasError($has_error)
    {
        $this->has_error = $has_error;
    }

    public function getHasError()
    {
        return $this->has_error;
    }

    public function setColumn($column)
    {
        $this->column = $column;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function setErrorCode($error_code)
    {
        $this->error_code = $error_code;
    }

    public function getErrorCode()
    {
        return $this->error_code;
    }

    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

}
