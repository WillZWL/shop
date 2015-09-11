<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vbdatatransfer extends PUB_Controller
{
	
	public function  __construct()
	{		
        parent::__construct();
		//price
		$this->load->library('service/vb_data_transfer_prices_service');
		
		//product
		$this->load->library('service/vb_data_transfer_products_service');
		$this->load->library('service/vb_data_transfer_product_content_service');
		$this->load->library('service/vb_data_transfer_product_content_extend_service');		
		$this->load->library('service/vb_data_transfer_product_custom_class_service');			
		$this->load->library('service/vb_data_transfer_product_keyword_service');	
		$this->load->library('service/vb_data_transfer_product_note_service');	
		$this->load->library('service/vb_data_transfer_product_warranty_service');		
		$this->load->library('service/vb_data_transfer_product_identifier_service');	
		$this->load->library('service/vb_data_transfer_product_image_service');
		
		//master tables
		$this->load->library('service/vb_data_transfer_category_service');		
		$this->load->library('service/vb_data_transfer_category_extend_service');
		$this->load->library('service/vb_data_transfer_brand_service');		
		$this->load->library('service/vb_data_transfer_colour_service');		
		$this->load->library('service/vb_data_transfer_colour_extend_service');
		$this->load->library('service/vb_data_transfer_version_service');
		$this->load->library('service/vb_data_transfer_freight_cat_service');		
		
		//RA
		$this->load->library('service/vb_data_transfer_ra_group_content_service');
		$this->load->library('service/vb_data_transfer_ra_group_service');
		$this->load->library('service/vb_data_transfer_ra_group_product_service');				
		//$this->load->library('service/vb_data_transfer_ra_prod_prod_service');
		$this->load->library('service/vb_data_transfer_ra_product_service');
		$this->load->library('service/vb_data_transfer_ra_prod_cat_service');	
	}
	
	public function price()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_prices_service->start_process($xml);
		print $feed;
	}	
	
	/********************** start product tables **********************/
	public function product()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_products_service->start_process($xml);
		print $feed;
	}
	
	public function productcontent()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_content_service->start_process($xml);
		print $feed;
	}
	
	public function productcontentextend()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_content_extend_service->start_process($xml);
		print $feed;
	}
	
	public function productcustomclass()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_custom_class_service->start_process($xml);
		print $feed;
	}
	
	public function productidentifier()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_identifier_service->start_process($xml);
		print $feed;
	}
	
	public function productimage()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_image_service->start_process($xml);
		print $feed;
	}
	
	public function productkeyword()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_keyword_service->start_process($xml);
		print $feed;
	}
	
	public function productnote()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_note_service->start_process($xml);
		print $feed;
	}
	
	public function productwarranty()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_product_warranty_service->start_process($xml);
		print $feed;
	}
	/********************** end product tables **********************/
	
	/********************** start master tables **********************/
	public function category()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_category_service->start_process($xml);
		print $feed;
	}	
	
	public function categoryextend()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_category_extend_service->start_process($xml);
		print $feed;
	}
	
	public function brand()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_brand_service->start_process($xml);
		print $feed;
	}
	
	public function colour()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_colour_service->start_process($xml);
		print $feed;
	}
	
	public function colourextend()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_colour_extend_service->start_process($xml);
		print $feed;
	}
	
	public function version()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_version_service->start_process($xml);
		print $feed;
	}
	
	public function freightcat()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_freight_cat_service->start_process($xml);
		print $feed;
	}
	
	/********************** end master tables **********************/
	
	
	/********************** start RA tables **********************/
	
	
	public function ragroup()
	{			
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed =$this->vb_data_transfer_ra_group_service->start_process($xml);
		print $feed;
	}
	
	public function ragroupcontent()
	{			
		$xml = file_get_contents('php://input');
		$feed =$this->vb_data_transfer_ra_group_content_service->start_process($xml);
		print $feed;
	}
	
	public function ragroupproduct()
	{			
		$xml = file_get_contents('php://input');
		header('content-type: text/xml');
		print $xml;
		exit;
		$feed =$this->vb_data_transfer_ra_group_product_service->start_process($xml);
		print $feed;
	}
	
	public function raproduct()
	{			
		$xml = file_get_contents('php://input');
		$feed =$this->vb_data_transfer_ra_product_service->start_process($xml);
		print $feed;
	}
	
	public function raprodcat()
	{			
		$xml = file_get_contents('php://input');
		$feed =$this->vb_data_transfer_ra_prod_cat_service->start_process($xml);
		print $feed;
	}
	
	/********************** end RA tables **********************/
	
	 public function index()
	 {	
		// $xml = file_get_contents('php://input');
		// // header('content-type: text/xml');
		// // print $xml;
		// // exit;
		// $feed =$this->vb_data_transfer_prices_service->start_process($xml);
		// print $feed;
		// //return $feed;
		print base_url();
	 }
}

