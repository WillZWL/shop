<?php

class AuditLogVo extends \BaseVo
{
    private $id;
    private $user_id;
    private $ip_address;
    private $status = '0';

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

    public function setUserId($user_id)
    {
        if ($user_id !== null) {
            $this->user_id = $user_id;
        }
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setIpAddress($ip_address)
    {
        if ($ip_address !== null) {
            $this->ip_address = $ip_address;
        }
    }

    public function getIpAddress()
    {
        return $this->ip_address;
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
