<?php

class FlexBatchVo extends \BaseVo
{
    private $id;
    private $gateway_id;
    private $filename;
    private $status = 'N';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setGatewayId($gateway_id)
    {
        if ($gateway_id !== null) {
            $this->gateway_id = $gateway_id;
        }
    }

    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    public function setFilename($filename)
    {
        if ($filename !== null) {
            $this->filename = $filename;
        }
    }

    public function getFilename()
    {
        return $this->filename;
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
