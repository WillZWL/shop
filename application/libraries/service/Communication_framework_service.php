<?php

include_once "Base_service.php";

class Communication_framework_service extends Base_service
{
	const CF_VERSION = "1";

	protected $ch;
	protected $remote_url;
	protected $htaccess = NULL;
	protected $post_para = array();
	protected $remote_return;
	protected $remote_result_xml;
	protected $remote_error;
	protected $remote_warning;
	protected $remote_notice;

	protected $root_node_name = 'cf';
	protected $content = '';
	protected $output = '';

	protected $result = '';
	protected $error = array();
	protected $warning = array();
	protected $notice = array();

	public function __construct()
	{
		parent::__construct();
		$CI =& get_instance();
		$this->load = $CI->load;
		$this->load->helper('string');

		$this->ch = curl_init();
	}

	public function get_cf_version()
	{
		return Communication_framework_service::CF_VERSION;
	}

	public function get_remote_url()
	{
		return $this->remote_url;
	}

	public function set_remote_url($url = '')
	{
		$this->remote_url = $url;
	}

	public function get_htaccess()
	{
		return $this->htaccess;
	}

	public function set_htaccess($htaccess = '')
	{
		$this->htaccess = $htaccess;
	}

	public function get_post_para()
	{
		return $this->post_para;
	}

	public function set_post_para($post_para = array())
	{
		$this->post_para = array_merge($this->post_para, (array)$post_para);
	}

	public function clear_post_para()
	{
		$this->post_para = array();
	}

	public function get_remote_return()
	{
		return $this->remote_return;
	}

	public function call_remote_url($additional_opt = array())
	{
		curl_setopt($this->ch, CURLOPT_URL, $this->get_remote_url());
		curl_setopt($this->ch, CURLOPT_HEADER, false);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, false);

		if (!is_null($this->get_htaccess()))
		{
			curl_setopt($this->ch, CURLOPT_USERPWD, $this->get_htaccess());
			curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		}

		if (count($this->get_post_para()) > 0)
		{
			curl_setopt($this->ch, CURLOPT_POST, count($this->get_post_para()));
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->get_post_para()));
		}

		if (count($additional_opt) > 0)
		{
			foreach ($additional_opt as $opt_name=>$opt_value)
			{
				curl_setopt($this->ch, $opt_name, $opt_value);
			}
		}

		$this->remote_return = curl_exec($this->ch);
		$this->analyze_remote_feedback();

		return $this->remote_return;
	}

	private function analyze_remote_feedback()
	{
		$result = FALSE;

		$xml = simplexml_load_string($this->remote_return, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($xml !== FALSE)
		{
			if (strtolower($xml->getName()) == strtolower($this->get_root_node_name()))
			{
				foreach ($xml->children() as $node)
				{
					if ($node instanceof SimpleXMLElement)
					{
						if (strtolower($node->getName()) == 'cf_message')
						{
							foreach ($node->children() as $cf_message_node)
							{
								if (strtolower($cf_message_node->getName()) == 'result')
								{
									if (strtolower(trim((string) $cf_message_node)) == 'true')
									{
										$result = TRUE;
									}
								}

								if (strtolower($cf_message_node->getName()) == 'notice')
								{
									$this->set_remote_notice((string) $cf_message_node);
								}

								if (strtolower($cf_message_node->getName()) == 'warning')
								{
									$this->set_remote_warning((string) $cf_message_node);
								}

								if (strtolower($cf_message_node->getName()) == 'error')
								{
									$this->set_remote_error((string) $cf_message_node);
								}
							}
						}
						else
						{
							$this->set_remote_result_xml($node);
						}
					}
				}
			}
		}

		$this->set_remote_result($result);
	}

	public function get_remote_result_xml()
	{
		return $this->remote_result_xml;
	}

	public function set_remote_result_xml($result)
	{
		$this->remote_result_xml = $result;
	}

	public function get_remote_result()
	{
		return $this->remote_result;
	}

	public function set_remote_result($result)
	{
		$this->remote_result = $result;
	}

	public function get_remote_notice()
	{
		return $this->remote_notice;
	}

	public function set_remote_notice($notice)
	{
		$this->remote_notice = $notice;
	}

	public function get_remote_warning()
	{
		return $this->remote_warning;
	}

	public function set_remote_warning($warning)
	{
		$this->remote_warning = $warning;
	}

	public function get_remote_error()
	{
		return $this->remote_error;
	}

	public function set_remote_error($error)
	{
		$this->remote_error = $error;
	}

	public function get_root_node_name()
	{
		return $this->root_node_name;
	}

	public function set_root_node_name($name = '')
	{
		if ($name != '')
		{
			$this->root_node_name = $name;
		}
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_content($content = '')
	{
		$this->content = $content;
	}

	public function get_result()
	{
		return $this->result;
	}

	public function set_result($result = '')
	{
		$this->result = $result;
	}

	public function get_error()
	{
		return $this->error;
	}

	public function set_error($error = array())
	{
		$this->error = array_merge($this->error, (array)$error);
	}

	public function clear_error()
	{
		$this->error = array();
	}

	public function get_warning()
	{
		return $this->warning;
	}

	public function set_warning($warning = array())
	{
		$this->warning = array_merge($this->warning, (array)$warning);
	}

	public function clear_warning()
	{
		$this->warning = array();
	}

	public function get_notice()
	{
		return $this->notice;
	}

	public function set_notice($notice = array())
	{
		$this->notice = array_merge($this->notice, (array)$notice);
	}

	public function clear_notice()
	{
		$this->notice = array();
	}

	public function get_message_content()
	{
		$message = array();

		$message[] = '<cf_message>';
		$message[] = '<version>' . $this->get_cf_version() . '</version>';
		$message[] = '<result>' . xmlspecialchars($this->get_result()) . '</result>';
		$message[] = '<notice>' . xmlspecialchars(implode("\n", $this->get_notice())) . '</notice>';
		$message[] = '<warning>' . xmlspecialchars(implode("\n", $this->get_warning())) . '</warning>';
		$message[] = '<error>' . xmlspecialchars(implode("\n", $this->get_error())) . '</error>';
		$message[] = '</cf_message>';

		return implode("\n", $message);
	}

	public function get_output()
	{
		return $this->output;
	}

	public function set_output($output = '')
	{
		$this->output = $output;
	}

	public function generate_output($extra_content = '')
	{
		$xml = array();
		$xml[] = '<?xml version="1.0"?>';
		$xml[] = '<' . $this->get_root_node_name() . '>';
		$xml[] = $this->get_message_content();

		if ($extra_content != '')
		{
			$xml[] = $extra_content;
		}
		$xml[] = $this->get_content();
		$xml[] = '</' . $this->get_root_node_name() . '>';

		$this->set_output(implode("\n", $xml));
	}

	public function output_xml($extra_content = '')
	{
		if ($this->get_output() == '')
		{
			$this->generate_output($extra_content);
		}

		header('Content-Type: text/xml');
		echo  $this->get_output();
	}

	public function return_xml($extra_content = '')
	{
		if ($this->get_output() == '')
		{
			$this->generate_output($extra_content);
		}

		return $this->get_output();
	}
}
?>