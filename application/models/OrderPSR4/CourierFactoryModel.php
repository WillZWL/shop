<?php
namespace ESG\Panther\Models\Order;

use ESG\Panther\Service\CourierApi\CourierFactoryService;

class CourierFactoryModel extends \CI_Model
{   
    public $_courierFactoryService;
    private $courierServiceArr=array(
        "Asendia"=> \ESG\Panther\Service\CourierApi\AsendiaApiService::class,
        "ChinaPost"=> \ESG\Panther\Service\CourierApi\ChinaPostApiService::class,
    );
   
    public function __construct()
    {   
        parent::__construct();     
    }

    public function initCourierFactoryService($courierId,$formValue,$function)
    {
        $courierApiService= new $this->courierServiceArr[$courierId]($formValue);
        $this->_courierFactoryService=new CourierFactoryService($courierApiService,$courierId,$formValue);
        return $this->_courierFactoryService->$function();
    } 

    public function addCourierOrder($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'addCourierOrder'); 
    }

    public function applyCourierTracking($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'applyCourierTracking');
    }

    public function printCourierOrder($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'printCourierOrder');
    }

    public function deleteCourierOrder($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'deleteCourierOrder');
    }

    public function findCourierOrder($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'findCourierOrder');
    }

    public function getCourierTrackingNo($courierId,$formValue)
    {  
        return $this->initCourierFactoryService($courierId,$formValue,'getCourierTrackingNo');
    }

    public function getAllShipway($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'getAllShipway');
    }
    
    public function updateInterfacePendingOrder($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'updateInterfacePendingOrder');
    }

    public function addCourierManifest($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'addCourierManifest');
    }

    public function deleteCourierManifest($courierId,$formValue)
    {   
        return $this->initCourierFactoryService($courierId,$formValue,'deleteCourierManifest');
    }

    public function getBarcode($code = '')
    {
        if($code == '') exit();
        include_once(BASEPATH . "plugins/barcode/barcode.php");

        DEFINE('CANVAS_WIDTH', 280);
        DEFINE('CANVAS_HEIGHT', 70);
        DEFINE('BARCODE_HEIGHT', 30);
        DEFINE('BARCODE_WIDTH', 1);

        $x = CANVAS_WIDTH / 2; // barcode center
        $y = CANVAS_HEIGHT / 2 -10; // barcode center
        $height = BARCODE_HEIGHT; // barcode height in 1D ; module size in 2D
        $width = BARCODE_WIDTH; // barcode height in 1D ; not use in 2D
        $angle = 0; // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
        $type_list = array('int25','std25','ean8','ean13','upc','code11','code39','code93','code128','codabar','msi','datamatrix');
        //6, 8, 11 work at this task
        $type = $type_list[8];

        $im = imagecreatetruecolor(CANVAS_WIDTH, CANVAS_HEIGHT);
        $black = ImageColorAllocate($im,0x00,0x00,0x00);
        $white = ImageColorAllocate($im,0xff,0xff,0xff);
        $red = ImageColorAllocate($im,0xff,0x00,0x00);
        $blue = ImageColorAllocate($im,0x00,0x00,0xff);
        imagefilledrectangle($im, 0, 0, 300, 300, $white);

        // BARCODE
        $data = \Barcode::gd($im, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
        imagestring($im, 70, 60, 52, $code, $black);
        ob_start();
        imagepng($im);
        $imagedata = ob_get_contents();
        ob_end_clean();
        imagedestroy($im);
        return $imagedata;
    }

}
