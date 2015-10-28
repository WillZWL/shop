<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use ESG\Panther\Service\VbDataTransferPricesService;

use ESG\Panther\Service\VbDataTransferBrandService;
use ESG\Panther\Service\VbDataTransferCategoryExtendService;
use ESG\Panther\Service\VbDataTransferCategoryService;
use ESG\Panther\Service\VbDataTransferColourExtendService;
use ESG\Panther\Service\VbDataTransferColourService;
use ESG\Panther\Service\VbDataTransferFreightCatService;
use ESG\Panther\Service\VbDataTransferVersionService;

use ESG\Panther\Service\VbDataTransferProductContentExtendService;
use ESG\Panther\Service\VbDataTransferProductContentService;
use ESG\Panther\Service\VbDataTransferProductCustomClassService;
use ESG\Panther\Service\VbDataTransferProductIdentifierService;
use ESG\Panther\Service\VbDataTransferProductKeywordService;
use ESG\Panther\Service\VbDataTransferProductNoteService;
use ESG\Panther\Service\VbDataTransferProductsService;
use ESG\Panther\Service\VbDataTransferProductWarrantyService;
use ESG\Panther\Service\VbDataTransferSupplierProductService;
use ESG\Panther\Service\VbProductImageService;

use ESG\Panther\Service\VbDataTransferRaGroupContentService;
use ESG\Panther\Service\VbDataTransferRaGroupProductService;
use ESG\Panther\Service\VbDataTransferRaGroupService;
use ESG\Panther\Service\VbDataTransferRaProdCatService;
use ESG\Panther\Service\VbDataTransferRaProductService;

class Vbdatatransfer extends PUB_Controller
{

	public function  __construct()
	{
        parent::__construct();
		//price
        $this->vbDataTransferPricesService = new VbDataTransferPricesService;

		//product
        $this->vbDataTransferProductsService = new VbDataTransferProductsService;
        $this->vbDataTransferProductContentExtendService = new VbDataTransferProductContentExtendService;
        $this->vbDataTransferProductContentService = new VbDataTransferProductContentService;
        $this->vbDataTransferProductCustomClassService = new VbDataTransferProductCustomClassService;
        $this->vbDataTransferProductIdentifierService = new VbDataTransferProductIdentifierService;
        $this->vbDataTransferProductKeywordService = new VbDataTransferProductKeywordService;
        $this->vbDataTransferProductNoteService = new VbDataTransferProductNoteService;
        $this->vbDataTransferProductWarrantyService = new VbDataTransferProductWarrantyService;
        $this->vbDataTransferSupplierProductService = new VbDataTransferSupplierProductService;
        $this->vbProductImageService = new VbProductImageService;

		//master tables
        $this->vbDataTransferBrandService = new VbDataTransferBrandService;
        $this->vbDataTransferCategoryService = new VbDataTransferCategoryService;
        $this->vbDataTransferCategoryExtendService = new VbDataTransferCategoryExtendService;
        $this->vbDataTransferColourService = new VbDataTransferColourService;
        $this->vbDataTransferColourExtendService = new VbDataTransferColourExtendService;
        $this->vbDataTransferFreightCatService = new VbDataTransferFreightCatService;
        $this->vbDataTransferVersionService = new VbDataTransferVersionService;

		//RA
        $this->vbDataTransferRaGroupContentService = new VbDataTransferRaGroupContentService;
        $this->vbDataTransferRaGroupProductService = new VbDataTransferRaGroupProductService;
        $this->vbDataTransferRaGroupService = new VbDataTransferRaGroupService;
        $this->vbDataTransferRaProdCatService = new VbDataTransferRaProdCatService;
        $this->vbDataTransferRaProductService = new VbDataTransferRaProductService;
	}

	public function price()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		//$feed =$this->vb_data_transfer_prices_service->start_process($xml);
		$feed = $this->sc['VbDataTransferPricesService']->processVbData($xml);
		print $feed;
	}

	/********************** start product tables **********************/
	public function product()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['vbDataTransferProductsService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_products_service->start_process($xml);
		print $feed;
	}

	public function productcontent()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductContentService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_content_service->start_process($xml);
		print $feed;
	}

	public function productcontentextend()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductContentExtendService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_content_extend_service->start_process($xml);
		print $feed;
	}

	public function productcustomclass()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductCustomClassService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_custom_class_service->start_process($xml);
		print $feed;
	}

	public function productidentifier()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductIdentifierService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_identifier_service->start_process($xml);
		print $feed;
	}

	public function productimage()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductImageService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_image_service->start_process($xml);
		print $feed;
	}

	public function productimagetransfer()
	{
		$num_img =$this->sc['VbProductImageService']->transferImages();
		//$num_img =$this->vb_product_image_service->transfer_images();
		print $num_img;
	}

	public function productkeyword()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductKeywordService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_keyword_service->start_process($xml);
		print $feed;
	}

	public function productnote()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductNoteService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_note_service->start_process($xml);
		print $feed;
	}

	public function productwarranty()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferProductWarrantyService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_product_warranty_service->start_process($xml);
		print $feed;
	}

	public function supplierproduct()
	{
		$xml = file_get_contents('php://input');
		/*header('content-type: text/xml');
		print $xml;
		exit;*/
		$feed = $this->sc['VbDataTransferSupplierProductService']->processVbData($xml);
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
		$feed = $this->sc['VbDataTransferCategoryService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_category_service->start_process($xml);
		print $feed;
	}

	public function categoryextend()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferCategoryExtendService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_category_extend_service->start_process($xml);
		print $feed;
	}

	public function brand()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferBrandService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_brand_service->start_process($xml);
		print $feed;
	}

	public function colour()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferColourService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_colour_service->start_process($xml);
		print $feed;
	}

	public function colourextend()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferColourExtendService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_colour_extend_service->start_process($xml);
		print $feed;
	}

	public function version()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferVersionService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_version_service->start_process($xml);
		print $feed;
	}

	public function freightcat()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferFreightCatService']->processVbData($xml);
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
		$feed = $this->sc['VbDataTransferRaGroupService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_group_service->start_process($xml);
		print $feed;
	}

	public function ragroupcontent()
	{
		$xml = file_get_contents('php://input');
		$feed = $this->sc['VbDataTransferRaGroupContentService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_group_content_service->start_process($xml);
		print $feed;
	}

	public function ragroupproduct()
	{
		$xml = file_get_contents('php://input');
		// header('content-type: text/xml');
		// print $xml;
		// exit;
		$feed = $this->sc['VbDataTransferRaGroupProductService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_group_product_service->start_process($xml);
		print $feed;
	}

	public function raproduct()
	{
		$xml = file_get_contents('php://input');
		$feed = $this->sc['VbDataTransferRaProductService']->processVbData($xml);
		//$feed =$this->vb_data_transfer_ra_product_service->start_process($xml);
		print $feed;
	}

	public function raprodcat()
	{
		$xml = file_get_contents('php://input');
		$feed = $this->sc['VbDataTransferRaProdCatService']->processVbData($xml);
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

