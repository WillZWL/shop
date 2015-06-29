<?php

include_once "Connector.php";

class Ftp_connector extends Connector
{
    private $fcs;
    private $logheader;
    private $loglevel = array();
    private $logger;

    public function __construct()
    {
        parent::__construct();

        //using ftpconnetion_service
        include_once "Ftpconnection_service.php";
        $this->fcs = new Ftpconnection_service();

        //using log service
        include_once "Log_service.php";
        $this->logger = new Log_service();
        $this->loglevel = $this->logger->get_loglevel();
        $this->logheader = $this->logger->get_log_header();
    }

    public function set_username($value)
    {
        $this->fcs->set_username($value);
    }

    public function set_password($value)
    {
        $this->fcs->set_password($value);
    }

    public function set_remote_site($value)
    {
        $this->fcs->set_remote_site($value);
    }

    public function set_port($value)
    {
        $this->fcs->set_port($value);
    }

    public function set_is_passive($value)
    {
        $this->fcs->set_is_passive($value);
    }

    public function set_timeout($value)
    {
        $this->fcs->set_timeout($value);
    }

    public function connect($passive = true)
    {
        $conn_id = FALSE;
        try {
            if (!$passive) {
                $this->fcs->set_is_passive($passive);
            }
            if ($this->fcs->get_timeout() == "") {
                $this->fcs->set_timeout(90);
            }
            if ($this->fcs->get_port() == "") {
                $this->fcs->set_port(21);
            }
            $conn_id = $this->fcs->connect();
            $conn_id = TRUE;
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
            $conn_id = FALSE;
        }

        return $conn_id;
    }

    public function login()
    {
        $login = FALSE;
        try {
            $login = $this->fcs->login();
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }
        return $login;
    }

    public function ftplist($remotepath)
    {
        $list = FALSE;
        try {
            $list = $this->fcs->listfile($remotepath);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $list;
    }

    public function getfile($localfile, $remotefile)
    {
        $getfile = FALSE;
        try {
            $getfile = $this->fcs->getfile($localfile, $remotefile);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $getfile;
    }

    public function putfile($localfile, $remotefile)
    {
        $putfile = FALSE;
        try {
            $putfile = $this->fcs->putfile($localfile, $remotefile);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $putfile;
    }

    public function delfile($remotefile)
    {
        $delfile = FALSE;
        try {
            $delfile = $this->fcs->remove($remotefile);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $delfile;
    }

    public function renamefile($oldname, $newname)
    {
        $renamefile = FALSE;
        try {
            $renamefile = $this->fcs->renamefile($oldname, $newname);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $renamefile;
    }

    function is_ftp_dir($remotefile)
    {
        return $this->fcs->is_directory($remotefile);
    }

    public function close()
    {
        try {
            $rs = $this->fcs->quit();
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logger->write_log($this->logheader);
        }

        return $rs;
    }

}

?>