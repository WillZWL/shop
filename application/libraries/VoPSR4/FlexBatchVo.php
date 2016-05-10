<?php
class FlexBatchVo extends \BaseVo
{
    private $id;
    private $gateway_id;
    private $filename;
    private $status = 'N';

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setGatewayId($value)
    {
        $this->gateway_id = $value;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($value)
    {
        $this->filename = $value;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($value)
    {
        $this->status = $value;
        return $this;
    }




}

?>