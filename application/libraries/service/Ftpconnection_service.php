<?php

include_once "Connection_service.php";


class Ftpconnection_service extends Connection_service
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

	public function set_username($value)
	{
		$this->username = $value;
	}

	public function set_password($value)
	{
		$this->password = $value;
	}

	public function set_is_passive($value)
	{
		$this->is_passive = $value;
	}

	public function connect()
	{
		$ret = FALSE;
		$conn_id = NULL;

		if(!($this->conn_id = ($this->get_port()==22?ssh2_connect($this->get_remote_site(), $this->get_port()):ftp_connect($this->get_remote_site(),$this->get_port(),$this->get_timeout()))))
		{
			throw new Exception("Cannot Connect to ".$this->get_remote_site()." on port ".$this->get_port());
			$ret = FALSE;
		}
		else
		{
			$ret = TRUE;
		}

		return $ret;
	}

	public function ssl_connect()
	{
		$ret = FALSE;
		$conn_id = NULL;

		if(!($this->conn_id = ($this->get_port()==22?ssh2_connect($this->get_remote_site(), $this->get_port()):ftp_ssl_connect($this->get_remote_site(),$this->get_port(),$this->get_timeout()))))
		{
			throw new Exception("Cannot Connect to ".$this->get_remote_site()." on port ".$this->get_port());
			$ret = FALSE;
		}
		else
		{
			$ret = TRUE;
		}

		return $ret;
	}

	public function login()
	{
		$ret = FALSE;

		if(!($login_status = ($this->get_port()==22?ssh2_auth_password($this->conn_id,$this->username,$this->password):ftp_login($this->conn_id,$this->username,$this->password))))
		{
			if ($this->ssl_connect() !== FALSE)
			{
				if(!($login_status = ($this->get_port()==22?ssh2_auth_password($this->conn_id,$this->username,$this->password):ftp_login($this->conn_id,$this->username,$this->password))))
				{
					throw new Exception("Login or password is incorrect while connecting to ".$this->get_remote_site()." on port ".$this->get_port().". Please check and try again");
					$ret = FALSE;
				}
				else
				{
					if ($this->get_port() == 22)
					{
						$this->sftp = ssh2_sftp($this->conn_id);
					}
					elseif ($this->is_passive)
					{
						ftp_pasv($this->conn_id, true);
					}
					$ret = $login_status;
				}
			}
		}
		else
		{
			if ($this->get_port() == 22)
			{
				$this->sftp = ssh2_sftp($this->conn_id);
			}
			elseif ($this->is_passive)
			{
				ftp_pasv($this->conn_id, true);
			}
			$ret = $login_status;
		}
		//return $login_status as result;
		return $ret;
	}

	public function listfile($remotepath)
	{
		if ($this->get_port() == 22)
		{
			$rmotedir = "ssh2.sftp://".$this->sftp."/".$remotepath;
			if (is_dir($rmotedir))
			{
				$contents = array();
				$handle = opendir($rmotedir);
				while (false !== ($rsfile = readdir($handle)))
				{
					$contents[]=$rsfile;
				}
				closedir($handle);
			}
			else
			{
				throw new Exception("Fail to list file in remote folder $remotepath at ".$this->get_remote_site());
				$contents = FALSE;
			}
		}
		elseif(!($contents = ftp_nlist($this->conn_id,$remotepath)))
		{
			throw new Exception("Fail to list file in remote folder $remotepath at ".$this->get_remote_site());
		}

		//return
		return $contents;
	}

	public function putfile($localfilename, $remotefilename)
	{
		if(!($upload = ($this->get_port() == 22?file_put_contents("ssh2.sftp://".$this->sftp."/{$remotefilename}", file_get_contents($localfilename)):ftp_put($this->conn_id, $remotefilename, $localfilename, FTP_BINARY))))
		{
			$ret = false;
			throw new Exception("Fail to upload $localfilename to be $remotefilename on ".$this->get_remote_site());
		}
		else
		{
			$ret = $upload;
		}
		return $ret;
	}


	public function getfile($localfilename, $remotefilename)
	{
		if(!($download = ($this->get_port() == 22?file_put_contents($localfilename, file_get_contents("ssh2.sftp://".$this->sftp."/{$remotefilename}")):ftp_get($this->conn_id, $localfilename, $remotefilename, FTP_BINARY))))
		{
			throw new Exception("Fail to download $remotefilename to $localfilename from ".$this->get_remote_site());
			$ret = false;
		}
		else
		{
			$ret = $download;
		}
		return $ret;
	}

	public function remove($remotefilename)
	{
		if(!($delete = ($this->get_port() == 22?ssh2_sftp_unlink($this->sftp, $remotefilename):ftp_delete($this->conn_id, $remotefilename))))
		{
			throw new Exception("Fail to remove $remotefilename on ".$this->get_remote_site());
			$ret = false;
		}
		else
		{
			$ret = $delete;
		}
		return $ret;
	}

	public function renamefile($oldname,$newname)
	{
		if(!($rename = ($this->get_port() == 22?ssh2_sftp_rename($this->sftp,$oldname,$newname):ftp_rename($this->conn_id,$oldname,$newname))))
		{
			throw new Exception("Fail to rename $oldname to $newname on ".$this->get_remote_site());
			$ret = false;
		}
		else
		{
			$ret = $rename;
		}
		return $ret;
	}

	public function is_directory($remotefilename)
	{
		if(ftp_size($this->conn_id, $remotefilename) == '-1')
		{
			return true; // Is directory
		}
		else
		{
			return false; // Is file
		}
	}

	public function quit()
	{
		if ($this->get_port() == 22)
		{
			return TRUE;
		}
		if(!($rs = ftp_quit($this->conn_id)))
		{
			throw new Exception("Fail to quit on ".$this->get_remote_site());
			$ret = false;
		}
		else
		{
			$ret = $rs;
		}
		return $ret;
	}

}
