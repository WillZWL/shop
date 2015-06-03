<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Tpl_msg_w_att_dto extends Base_dto
{

	//class variable
	private $id;
	private $lang_id = 'en';
	private $platform_id;
	private $name;
	private $description;
	private $tpl_file;
	private $tpl_alt_file;
	private $subject;
	private $message;
	private $alt_message;
	private $pdf_attachment;
	private $message_html;
	private $message_alt;

	function __construct()
	{
		parent::__construct();
	}

	//instance method
	public function get_id()
	{
		return $this->id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	public function set_lang_id($value)
	{
		$this->lang_id = $value;
		return $this;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
		return $this;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($value)
	{
		$this->description = $value;
	}

	public function get_tpl_file()
	{
		return $this->tpl_file;
	}

	public function set_tpl_file($value)
	{
		$this->tpl_file = $value;
	}

	public function get_tpl_alt_file()
	{
		return $this->tpl_alt_file;
	}

	public function set_tpl_alt_file($value)
	{
		$this->tpl_alt_file = $value;
	}

	public function get_subject()
	{
		return $this->subject;
	}

	public function set_subject($value)
	{
		$this->subject = $value;
	}

	public function get_message()
	{
		return $this->message;
	}

	public function set_message($value)
	{
		$this->message = $value;
	}

	public function get_alt_message()
	{
		return $this->alt_message;
	}

	public function set_alt_message($value)
	{
		$this->alt_message = $value;
	}

	public function get_pdf_attachment()
	{
		return $this->pdf_attachment;
	}

	public function set_pdf_attachment($value)
	{
		$this->pdf_attachment = $value;
	}

	public function get_message_html()
	{
		return $this->message_html;
	}

	public function set_message_html($value)
	{
		$this->message_html = $value;
	}

	public function get_message_alt()
	{
		return $this->message_alt;
	}

	public function set_message_alt($value)
	{
		$this->message_alt = $value;
	}
}

/* End of file tpl_msg_w_att_dto.php */
/* Location: ./system/application/libraries/dto/tpl_msg_w_att_dto.php */