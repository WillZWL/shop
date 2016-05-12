<?php

class HttpInfoVo extends \BaseVo
{
    private $name;
    private $type = 'P';
    private $server;
    private $username;
    private $password;
    private $application_id;
    private $signature;
    private $token;
    private $remark;
    private $status = '1';

    protected $primary_key = ['name', 'type'];
    protected $increment_field = '';

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

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setServer($server)
    {
        if ($server !== null) {
            $this->server = $server;
        }
    }

    public function getServer()
    {
        return $this->server;
    }

    public function setUsername($username)
    {
        if ($username !== null) {
            $this->username = $username;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        if ($password !== null) {
            $this->password = $password;
        }
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setApplicationId($application_id)
    {
        if ($application_id !== null) {
            $this->application_id = $application_id;
        }
    }

    public function getApplicationId()
    {
        return $this->application_id;
    }

    public function setSignature($signature)
    {
        if ($signature !== null) {
            $this->signature = $signature;
        }
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setToken($token)
    {
        if ($token !== null) {
            $this->token = $token;
        }
    }

    public function getToken()
    {
        return $this->token;
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
