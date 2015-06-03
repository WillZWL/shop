<?php

include_once "Connection_service.php";

class Httpconnection_service extends Connection_service
{
	private $form_method = 'POST';
	private $noprogress = 1;
	private $cookie;
	private $postfields;
	private $failonerror = 1;
	private $httpheader;

	public function __construct()
	{
		parent::__construct();
	}

	public function set_form_method($value)
	{
		$this->formMethod = $value;
	}

	public function get_content()
	{

		//use curl
		$ch = curl_init($this->get_remote_site());
		if(!$ch)
		{
			throw new exception('Cannot Allocate Resource for Client URL');
			$ret = false;
		}
		else
		{
			$port = $this->get_port();
			if($port)
			{
				curl_setopt($ch, $port);
			}

			if ($cookie = $this->get_cookie())
			{
				curl_setopt($ch, file_exists($cookie)?CURLOPT_COOKIEFILE:CURLOPT_COOKIEJAR, $cookie);
			}

			if ($postfields = $this->get_postfields())
			{
				curl_setopt($ch, CURLOPT_POST,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
			}

			if ($httpheader = $this->get_httpheader())
			{
			    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
			}

			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FAILONERROR, $this->get_failonerror());
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->get_timeout());
			curl_setopt($ch, CURLOPT_NOPROGRESS, $this->get_noprogress());

			$content = curl_exec($ch);
			curl_close($ch);
			if($content === FALSE)
			{
				throw new Exception('Connection fail while connecting to '.$this->get_remote_site().' on port '.$this->get_port());
			}
			$ret = $content;
		}
		return $ret;
	}


	public function set_noprogress($value)
	{
		$this->noprogress = $value;
	}

	public function get_noprogress()
	{
		return $this->noprogress;
	}

	public function set_cookie($value)
	{
		$this->cookie = $value;
	}

	public function get_cookie()
	{
		return $this->cookie;
	}

	public function set_postfields($value)
	{
		$this->postfields = $value;
	}

	public function get_postfields()
	{
		return $this->postfields;
	}

	public function set_failonerror($value)
	{
		$this->failonerror = $value;
	}

	public function get_failonerror()
	{
		return $this->failonerror;
	}

	public function set_httpheader($value)
	{
		$this->httpheader = $value;
	}

	public function get_httpheader()
	{
		return $this->httpheader;
	}
}
?>