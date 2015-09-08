<?php
namespace AtomV2\Service;

class FtpconnectionService extends ConnectionService
{
    //class variable
    private $username;
    private $password;
    private $is_passive = true;
    private $conn_id;
    private $sftp;

    public function __construct()
    {
        parent::__construct();
        $this->port = 21;
        $this->timeout = 90;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function setIsPassive($value)
    {
        $this->is_passive = $value;
    }

    public function connect()
    {
        $ret = FALSE;
        $conn_id = NULL;

        if (!($this->conn_id = ($this->getPort() == 22 ? ssh2_connect($this->getRemoteSite(), $this->getPort()) : ftp_connect($this->getRemoteSite(), $this->getPort(), $this->getTimeout())))) {
            throw new Exception("Cannot Connect to " . $this->getRemoteSite() . " on port " . $this->getPort());
            $ret = FALSE;
        } else {
            $ret = TRUE;
        }

        return $ret;
    }

    public function login()
    {
        $ret = FALSE;

        if (!($login_status = ($this->getPort() == 22 ? ssh2_auth_password($this->conn_id, $this->username, $this->password) : ftp_login($this->conn_id, $this->username, $this->password)))) {
            if ($this->sslConnect() !== FALSE) {
                if (!($login_status = ($this->getPort() == 22 ? ssh2_auth_password($this->conn_id, $this->username, $this->password) : ftp_login($this->conn_id, $this->username, $this->password)))) {
                    throw new Exception("Login or password is incorrect while connecting to " . $this->getRemoteSite() . " on port " . $this->getPort() . ". Please check and try again");
                    $ret = FALSE;
                } else {
                    if ($this->getPort() == 22) {
                        $this->sftp = ssh2_sftp($this->conn_id);
                    } elseif ($this->is_passive) {
                        ftp_pasv($this->conn_id, true);
                    }
                    $ret = $login_status;
                }
            }
        } else {
            if ($this->getPort() == 22) {
                $this->sftp = ssh2_sftp($this->conn_id);
            } elseif ($this->is_passive) {
                ftp_pasv($this->conn_id, true);
            }
            $ret = $login_status;
        }

        return $ret;
    }

    public function sslConnect()
    {
        $ret = FALSE;
        $conn_id = NULL;

        if (!($this->conn_id = ($this->getPort() == 22 ? ssh2_connect($this->getRemoteSite(), $this->getPort()) : ftp_ssl_connect($this->getRemoteSite(), $this->getPort(), $this->getTimeout())))) {
            throw new Exception("Cannot Connect to " . $this->getRemoteSite() . " on port " . $this->getPort());
            $ret = FALSE;
        } else {
            $ret = TRUE;
        }

        return $ret;
    }

    public function listfile($remotepath)
    {
        if ($this->getPort() == 22) {
            $rmotedir = "ssh2.sftp://" . $this->sftp . "/" . $remotepath;
            if (is_dir($rmotedir)) {
                $contents = array();
                $handle = opendir($rmotedir);
                while (false !== ($rsfile = readdir($handle))) {
                    $contents[] = $rsfile;
                }
                closedir($handle);
            } else {
                throw new Exception("Fail to list file in remote folder $remotepath at " . $this->getRemoteSite());
                $contents = FALSE;
            }
        } elseif (!($contents = ftp_nlist($this->conn_id, $remotepath))) {
            throw new Exception("Fail to list file in remote folder $remotepath at " . $this->getRemoteSite());
        }

        return $contents;
    }

    public function putfile($localfilename, $remotefilename)
    {
        if (!($upload = ($this->getPort() == 22 ? file_put_contents("ssh2.sftp://" . $this->sftp . "/{$remotefilename}", file_get_contents($localfilename)) : ftp_put($this->conn_id, $remotefilename, $localfilename, FTP_BINARY)))) {
            $ret = false;
            throw new Exception("Fail to upload $localfilename to be $remotefilename on " . $this->getRemoteSite());
        } else {
            $ret = $upload;
        }
        return $ret;
    }

    public function getfile($localfilename, $remotefilename)
    {
        if (!($download = ($this->getPort() == 22 ? file_put_contents($localfilename, file_get_contents("ssh2.sftp://" . $this->sftp . "/{$remotefilename}")) : ftp_get($this->conn_id, $localfilename, $remotefilename, FTP_BINARY)))) {
            throw new Exception("Fail to download $remotefilename to $localfilename from " . $this->getRemoteSite());
            $ret = false;
        } else {
            $ret = $download;
        }
        return $ret;
    }

    public function remove($remotefilename)
    {
        if (!($delete = ($this->getPort() == 22 ? ssh2_sftp_unlink($this->sftp, $remotefilename) : ftp_delete($this->conn_id, $remotefilename)))) {
            throw new Exception("Fail to remove $remotefilename on " . $this->getRemoteSite());
            $ret = false;
        } else {
            $ret = $delete;
        }
        return $ret;
    }

    public function renamefile($oldname, $newname)
    {
        if (!($rename = ($this->getPort() == 22 ? ssh2_sftp_rename($this->sftp, $oldname, $newname) : ftp_rename($this->conn_id, $oldname, $newname)))) {
            throw new Exception("Fail to rename $oldname to $newname on " . $this->getRemoteSite());
            $ret = false;
        } else {
            $ret = $rename;
        }
        return $ret;
    }

    public function isDirectory($remotefilename)
    {
        if (ftp_size($this->conn_id, $remotefilename) == '-1') {
            return true; // Is directory
        } else {
            return false; // Is file
        }
    }

    public function quit()
    {
        if ($this->getPort() == 22) {
            return TRUE;
        }
        if (!($rs = ftp_quit($this->conn_id))) {
            throw new Exception("Fail to quit on " . $this->getRemoteSite());
            $ret = false;
        } else {
            $ret = $rs;
        }
        return $ret;
    }

}
