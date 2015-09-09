<?php
namespace ESG\Panther\Service;

class ConnectionService extends BaseService
{
    private $remote_site;
    private $timeout;
    private $port;

    public function __construct()
    {
        parent::__construct();
    }

    public function getRemoteSite()
    {
        return $this->remote_site;
    }

    public function setRemoteSite($value)
    {
        $this->remote_site = $value;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($value)
    {
        $this->timeout = $value;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($value)
    {
        $this->port = $value;
    }
}
