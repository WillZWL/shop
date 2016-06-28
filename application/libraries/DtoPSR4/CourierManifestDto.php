<?php

class CourierManifestDto 
{

	//class variable
	private $courier_batch_id;
	private $manifest_id;
	private $max_increment_num;
	private $error_message;
	private $status;
	private $bags;
	//instance method

	public function getCourierBatchId(){
		
		return $this->courier_batch_id;
	}

	public function setCourierBatchId($value){
		
		$this->courier_batch_id = $value;
	}

	public function setManifestId($value){
		
		$this->manifest_id = $value;
	}

	public function getManifestId(){
		
		return $this->manifest_id;
	}

	public function setMaxIncrementNum($value){
		
		$this->max_increment_num = $value;
	}

	public function getMaxIncrementNum(){
		
		return $this->max_increment_num;
	}

	public function getErrorMessage(){
		
		return $this->error_message;
	}

	public function setErrorMessage($value){
		
		$this->error_message = $value;
	}

	public function getStatus(){
		
		return $this->status;
	}

	public function setStatus($value){
		
		$this->status = $value;
	}

	public function getBags(){
		
		return $this->bags;
	}

	public function setBags($value){
		
		$this->bags = $value;
	}

}