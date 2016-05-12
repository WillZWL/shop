<?php
class UserVo extends \BaseVo
{
    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $status = '0';
    protected $failed_attempt = '0';

    protected $increment_field = '';

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

    public function setEmail($email)
    {
        if ($email !== null) {
            $this->email = $email;
        }
    }

    public function getEmail()
    {
        return $this->email;
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

    public function setFailedAttempt($failed_attempt)
    {
        if ($failed_attempt !== null) {
            $this->failed_attempt = $failed_attempt;
        }
    }

    public function getFailedAttempt()
    {
        return $this->failed_attempt;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
