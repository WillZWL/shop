<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\FtpconnectionService;
use ESG\Panther\Service\LogService;

class FtpConnector extends Connector
{
    private $fcs;
    private $logheader;
    private $loglevel = [];
    private $logService;

    public function __construct()
    {
        parent::__construct();
        $this->ftpconnectionService = new FtpconnectionService;
        $this->logService = new LogService;
        $this->loglevel = $this->logService->getLoglevel();
        $this->logheader = $this->logService->getLogHeader();
    }

    public function setUsername($value)
    {
        $this->ftpconnectionService->setUsername($value);
    }

    public function setPassword($value)
    {
        $this->ftpconnectionService->setPassword($value);
    }

    public function setRemoteSite($value)
    {
        $this->ftpconnectionService->setRemoteSite($value);
    }

    public function setPort($value)
    {
        $this->ftpconnectionService->setPort($value);
    }

    public function setIsPassive($value)
    {
        $this->ftpconnectionService->setIsPassive($value);
    }

    public function setTimeout($value)
    {
        $this->ftpconnectionService->setTimeout($value);
    }

    public function connect($passive = true)
    {
        $conn_id = FALSE;
        try {
            if (!$passive) {
                $this->ftpconnectionService->setIsPassive($passive);
            }
            if ($this->ftpconnectionService->getTimeout() == "") {
                $this->ftpconnectionService->setTimeout(90);
            }
            if ($this->ftpconnectionService->getPort() == "") {
                $this->ftpconnectionService->setPort(21);
            }
            $conn_id = $this->ftpconnectionService->connect();
            $conn_id = TRUE;
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
            $conn_id = FALSE;
        }

        return $conn_id;
    }

    public function login()
    {
        $login = FALSE;
        try {
            $login = $this->ftpconnectionService->login();
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }
        return $login;
    }

    public function ftplist($remotepath)
    {
        $list = FALSE;
        try {
            $list = $this->ftpconnectionService->listfile($remotepath);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }

        return $list;
    }

    public function getfile($localfile, $remotefile)
    {
        $getfile = FALSE;
        try {
            $getfile = $this->ftpconnectionService->getfile($localfile, $remotefile);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }

        return $getfile;
    }

    public function putfile($localfile, $remotefile)
    {
        $putfile = FALSE;
        try {
            $putfile = $this->ftpconnectionService->putfile($localfile, $remotefile);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }

        return $putfile;
    }

    public function delfile($remotefile)
    {
        $delfile = FALSE;
        try {
            $delfile = $this->ftpconnectionService->remove($remotefile);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }

        return $delfile;
    }

    public function renamefile($oldname, $newname)
    {
        $renamefile = FALSE;
        try {
            $renamefile = $this->ftpconnectionService->renamefile($oldname, $newname);
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }

        return $renamefile;
    }

    function isFtpDir($remotefile)
    {
        return $this->ftpconnectionService->isDirectory($remotefile);
    }

    public function close()
    {
        try {
            $rs = $this->ftpconnectionService->quit();
        } catch (Exception $e) {
            $this->logheader["type"] = $this->loglevel["ERROR"];
            $this->logheader["user"] = $_SESSION["user"]["id"];
            $this->logheader["userip"] = $_SERVER["REMOTE_ADDR"];
            $this->logheader["file"] = __FILE__;
            $this->logheader["linenumber"] = __LINE__ - 9;
            $this->logheader["message"] = addslashes($e->getMessage());
            $this->logService->writeLog($this->logheader);
        }

        return $rs;
    }

}
