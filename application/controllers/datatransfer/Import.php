<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends MY_Controller
{
    private $appId = "XFER0001";

    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '2048M');
        set_time_limit(1800);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function product()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferProducts']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function price()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferPrices']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function supplierProd()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferSupplierProduct']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    /********************** start product tables **********************/

    public function productContent()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductContent']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productContentExtend()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductContentExtend']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productCustomClass()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductCustomClass']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productIdentifier()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductIdentifier']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productImage()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferProductImage']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productImageTransfer()
    {
        $num_img = $this->sc['ProductImage']->transferImages();
        print $num_img;
    }

    public function productKeyword()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferProductKeyword']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productnote()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductNote']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productwarranty()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductWarranty']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function productComplementaryAcc()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferProductComplementaryAcc']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    /********************** end product tables **********************/

    /********************** start master tables **********************/
    public function category()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferCategory']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function categoryExtend()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferCategoryExtend']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function brand()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferBrand']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function colour()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferColour']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function colourExtend()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferColourExtend']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function version()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferVersion']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function freightCat()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferFreightCat']->startProcess($xml);
        unset($xml);
        print $feed;
    }
    /********************** end master tables **********************/


    /********************** start RA tables **********************/


    public function raGroup()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferRaGroup']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function raGroupContent()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferRaGroupContent']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function raGroupProduct()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferRaGroupProduct']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function raProduct()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferRaProduct']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function raProdCat()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferRaProdCat']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    /********************** end RA tables **********************/


    /********************** start google mapping tables **********************/

    public function categoryMapping()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferCategoryMapping']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function extCategoryMapping()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferExtCategoryMapping']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    public function externalCategory()
    {
        $xml = file_get_contents('php://input');
        $feed = $this->sc['VbDataTransferExternalCategory']->startProcess($xml);
        unset($xml);
        print $feed;
    }

    /********************** end google mapping tables **********************/

     public function index()
     {
        die('debug');
        // $xml = file_get_contents('php://input');
        // // header('content-type: text/xml');
        // // print $xml;
        // // exit;
        // $feed =$this->vb_data_transfer_prices_service->startProcess($xml);
        // print $feed;
        // //return $feed;
        print base_url();
     }
}

