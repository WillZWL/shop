<?php

include_once "Base_service.php";

class Connection_service extends Base_service
{
    private $remote_site;
    private $timeout;
    private $port;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_remote_site($value)
    {
        $this->remote_site = $value;
    }

    public function get_remote_site()
    {
        return $this->remote_site;
    }

    public function set_timeout($value)
    {
        $this->timeout = $value;
    }

    public function get_timeout()
    {
        return $this->timeout;
    }

    public function set_port($value)
    {
        $this->port = $value;
    }

    public function get_port()
    {
        return $this->port;
    }
}
?>