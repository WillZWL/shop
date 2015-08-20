<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vbdatatransfer extends PUB_Controller
{
	
	public function  __construct()
	{		
        parent::__construct();
		$this->load->library('service/vb_data_transfer_prices_service');
	}
	
	public function price()
	{	
		//$xml = file_get_contents('php://input');
		 
		//$feed =$this->vb_data_transfer_prices_service->start_process($input_data);
		
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_prices_service->start_process($xml);
		print $feed;
	}
	
	public function index()
	{	
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_prices_service->start_process($xml);
		print $feed;
		//return $feed;
	}
}

