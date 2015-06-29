<?php

include_once "Connector.php";

class Http_connector extends Connector
{
    private $hcs;
    private $logheader;
    private $loglevel = array();
    private $logger;

    public function __construct()
    {
        parent::__construct();

        //using httpconnetion_service
        include_once "Httpconnection_service.php";
        $this->hcs = new Httpconnection_service();

        //using log service
        include_once "Log_service.php";
        $this->logger = new Log_service();
        $this->loglevel = $this->logger->get_loglevel();
        $this->logheader = $this->logger->get_log_header();
    }

    public function set_remote_site($value)
    {
        $this->hcs->set_remote_site($value);
    }

    public function set_postfields($value)
    {
        $this->hcs->set_postfields($value);
    }

    public function set_failonerror($value)
    {
        $this->hcs->set_failonerror($value);
    }

    public function set_httpheader($value)
    {
        $this->hcs->set_httpheader($value);
    }

    public function set_cookie($value)
    {
        $this->hcs->set_cookie($value);
    }

    public function set_port($value)
    {
        $this->hcs->set_port($value);
    }

    public function get_hcs()
    {
        return $this->hcs;
    }

    public function set_timeout($value)
    {
        $this->hcs->set_timeout($value);
    }

    public function get_content()
    {

        $content = "";
        try {
            $content = $this->hcs->get_content();
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $content;
    }
}

?>