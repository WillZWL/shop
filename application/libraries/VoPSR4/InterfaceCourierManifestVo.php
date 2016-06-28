<?php

class InterfaceCourierManifestVo extends \BaseVo
{

	//class variable
	private $id;
	private $manifest_id;
	private $courier_batch_id;
	private $max_increment_num = '0';
	private $error_message = '0';
	private $status = '1';
	private $bags;

	//primary key
	protected $primary_key = array("id");
	//auo increment
	protected $increment_field = "id";

	//instance method
	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getManifestId()
	{
		return $this->manifest_id;
	}

	public function setManifestId($value)
	{
		$this->manifest_id = $value;
	}

	public function getCourierBatchId()
	{
		return $this->courier_batch_id;
	}

	public function setCourierBatchId($value)
	{
		$this->courier_batch_id = $value;
	}

	public function getMaxIncrementNum()
	{
		return $this->max_increment_num;
	}

	public function setMaxIncrementNum($value)
	{
		$this->max_increment_num = $value;
	}

	public function getErrorMessage()
	{
		return $this->error_message;
	}

	public function setErrorMessage($value)
	{
		$this->error_message = $value;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($value)
	{
		$this->status = $value;
	}

	public function getBags()
	{
		return $this->bags;
	}

	public function setBags($value)
	{
		$this->bags = $value;
	}

}
?>