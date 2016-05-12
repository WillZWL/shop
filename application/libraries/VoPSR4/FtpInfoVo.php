<?php

class FtpInfoVo extends \BaseVo
{
    private $id;
    private $name;
    private $server;
    private $username;
    private $password;
    private $port;
    private $pasv;

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

    public function setPort($port)
    {
        if ($port !== null) {
            $this->port = $port;
        }
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPasv($pasv)
    {
        if ($pasv !== null) {
            $this->pasv = $pasv;
        }
    }

    public function getPasv()
    {
        return $this->pasv;
    }

}
