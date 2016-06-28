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
        $this->_courierFactoryService->$function();
    } 

    public function addCourierOrder($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'addCourierOrder');
    }

    public function applyCourierTracking($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'applyCourierTracking');
    }

    public function printCourierOrder($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'printCourierOrder');
    }

    public function deleteCourierOrder($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'deleteCourierOrder');
    }

    public function findCourierOrder($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'findCourierOrder');
    }

    public function getCourierTrackingNo($courierId,$formValue)
    {  
        $this->initCourierFactoryService($courierId,$formValue,'getCourierTrackingNo');
    }

    public function getAllShipway($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'getAllShipway');
    }
    
    public function updateInterfacePendingOrder($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'updateInterfacePendingOrder');
    }

    public function addCourierManifest($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'addCourierManifest');
    }

    public function deleteCourierManifest($courierId,$formValue)
    {   
        $this->initCourierFactoryService($courierId,$formValue,'deleteCourierManifest');
    }

}
