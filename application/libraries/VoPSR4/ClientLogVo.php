<?php
class ClientLogVo extends \BaseVo
{
    private $email;
    private $ip_address;
    private $status = '0';

    protected $primary_key = ['email', 'ip_address', 'create_on'];
    protected $increment_field = '';

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
