<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vbdatatransfer extends PUB_Controller
{

	public function  __construct()
	{
        parent::__construct();
	}

	public function price()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		//$feed =$this->vb_data_transfer_prices_service->start_process($xml);
		$feed = $this->sc['VbDataTransferPrices']->processVbData($xml);
		print $feed;
	}

	/********************** start product tables **********************/
	public function product()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProducts']->processVbData($xml);
		//$feed =$this->vb_data_transfer_products_service->start_process($xml);
		print $feed;
	}

	public function productcontent()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductContent']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_content_service->start_process($xml);
		print $feed;
	}

	public function productcontentextend()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductContentExtend']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_content_extend_service->start_process($xml);
		print $feed;
	}

	public function productcustomclass()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductCustomClass']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_custom_class_service->start_process($xml);
		print $feed;
	}

	public function productidentifier()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductIdentifier']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_identifier_service->start_process($xml);
		print $feed;
	}

	public function productimage()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductImage']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_image_service->start_process($xml);
		print $feed;
	}

	public function productimagetransfer()
	{
		$num_img =$this->sc['VbProductImage']->transferImages();
		//$num_img =$this->vb_product_image_service->transfer_images();
		print $num_img;
	}

	public function productkeyword()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductKeyword']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_keyword_service->start_process($xml);
		print $feed;
	}

	public function productnote()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductNote']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_note_service->start_process($xml);
		print $feed;
	}

	public function productwarranty()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductWarranty']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_warranty_service->start_process($xml);
		print $feed;
	}

	public function supplierproduct()
	{
		$xml = file_get_contents('php://input');
		/*header('content-type: text/xml');
		print $xml;
		exit;*/
		$feed = $this->sc['VbDataTransferSupplierProduct']->processVbData($xml);
		//$feed =$this->vb_data_transfer_supplier_product_service->start_process($xml);
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
		$feed = $this->sc['VbDataTransferCategory']->processVbData($xml);
		//$feed =$this->vb_data_transfer_category_service->start_process($xml);
		print $feed;
	}

	public function categoryextend()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferCategoryExtend']->processVbData($xml);
		//$feed =$this->vb_data_transfer_category_extend_service->start_process($xml);
		print $feed;
	}

	public function brand()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferBrand']->processVbData($xml);
		//$feed =$this->vb_data_transfer_brand_service->start_process($xml);
		print $feed;
	}

	public function colour()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferColour']->processVbData($xml);
		//$feed =$this->vb_data_transfer_colour_service->start_process($xml);
		print $feed;
	}

	public function colourextend()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferColourExtend']->processVbData($xml);
		//$feed =$this->vb_data_transfer_colour_extend_service->start_process($xml);
		print $feed;
	}

	public function version()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferVersion']->processVbData($xml);
		//$feed =$this->vb_data_transfer_version_service->start_process($xml);
		print $feed;
	}

	public function freightcat()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferFreightCat']->processVbData($xml);
		//$feed =$this->vb_data_transfer_freight_cat_service->start_process($xml);
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
		$feed = $this->sc['VbDataTransferRaGroup']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_group_service->start_process($xml);
		print $feed;
	}

	public function ragroupcontent()
	{
		$xml = file_get_contents('php://input');
		$feed = $this->sc['VbDataTransferRaGroupContent']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_group_content_service->start_process($xml);
		print $feed;
	}

	public function ragroupproduct()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferRaGroupProduct']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_group_product_service->start_process($xml);
		print $feed;
	}

	public function raproduct()
	{
		$xml = file_get_contents('php://input');
		$feed = $this->sc['VbDataTransferRaProduct']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_product_service->start_process($xml);
		print $feed;
	}

	public function raprodcat()
	{
		$xml = file_get_contents('php://input');
		$feed = $this->sc['VbDataTransferRaProdCat']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_prod_cat_service->start_process($xml);
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

