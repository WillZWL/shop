<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends MY_Controller
{
    private $appId = "XFER0001";

    public function getAppId()
    {
        return $this->appId;
    }

    public function product()
    {
        $start = memory_get_usage();

        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProducts']->startProcess($xml);
        print $feed;

        $end = memory_get_usage();

        echo ($end - $start), '/', memory_get_peak_usage();

    }

    public function price()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->vb_data_transfer_prices_service->startProcess($xml);
        print $feed;
    }

    /********************** start product tables **********************/

    public function productContent()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->sc['VbDataTransferProductContent']->startProcess($xml);
        print $feed;
    }

    public function productcontentextend()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_content_extend_service->startProcess($xml);
        print $feed;
    }

    public function productcustomclass()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_custom_class_service->startProcess($xml);
        print $feed;
    }

    public function productidentifier()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_identifier_service->startProcess($xml);
        print $feed;
    }

    public function productimage()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_image_service->startProcess($xml);
        print $feed;
    }

    public function productimagetransfer()
    {
        $num_img =$this->vb_product_image_service->transfer_images();
        print $num_img;
    }

    public function productkeyword()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_keyword_service->startProcess($xml);
        print $feed;
    }

    public function productnote()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_note_service->startProcess($xml);
        print $feed;
    }

    public function productwarranty()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_product_warranty_service->startProcess($xml);
        print $feed;
    }

    public function supplierproduct()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_supplier_product_service->startProcess($xml);
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
        $feed =$this->vb_data_transfer_category_service->startProcess($xml);
        print $feed;
    }

    public function categoryextend()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_category_extend_service->startProcess($xml);
        print $feed;
    }

    public function brand()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_brand_service->startProcess($xml);
        print $feed;
    }

    public function colour()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_colour_service->startProcess($xml);
        print $feed;
    }

    public function colourextend()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_colour_extend_service->startProcess($xml);
        print $feed;
    }

    public function version()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_version_service->startProcess($xml);
        print $feed;
    }

    public function freightcat()
    {
        $xml = file_get_contents('php://input');
        // header('content-type: text/xml');
        // print $xml;
        // exit;
        $feed =$this->vb_data_transfer_freight_cat_service->startProcess($xml);
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
        $feed =$this->vb_data_transfer_ra_group_service->startProcess($xml);
        print $feed;
    }

    public function ragroupcontent()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->vb_data_transfer_ra_group_content_service->startProcess($xml);
        print $feed;
    }

    public function ragroupproduct()
    {
        $xml = file_get_contents('php://input');
        header('content-type: text/xml');
        print $xml;
        exit;
        $feed =$this->vb_data_transfer_ra_group_product_service->startProcess($xml);
        print $feed;
    }

    public function raproduct()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->vb_data_transfer_ra_product_service->startProcess($xml);
        print $feed;
    }

    public function raprodcat()
    {
        $xml = file_get_contents('php://input');
        $feed =$this->vb_data_transfer_ra_prod_cat_service->startProcess($xml);
        print $feed;
    }

    /********************** end RA tables **********************/

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

