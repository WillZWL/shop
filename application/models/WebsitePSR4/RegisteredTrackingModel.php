<?php
namespace ESG\Panther\Models\Website;
use ESG\Panther\Service\GoogleTagManagerTrackingScriptService;

class RegisteredTrackingModel extends \CI_Model
{
    private $registeredTracking;
    private $trackingCode;

    public function __construct(){

        parent::__construct();

    }

    public function getRegisteredTracking(){

        $registeredTracking=array("GoogleTagManager");

        return $registeredTracking;
    }

    public function getTrackingCode($data){

        foreach($this->getRegisteredTracking() as $tracking){
            
            $trackingService = $tracking . "TrackingScriptService";
            $trackingObj = new GoogleTagManagerTrackingScriptService();

            $page = array(
                "class" => $this->router->class,
                "method" => $this->router->method
            );

            if (count($this->router->uri->rsegments) >= 3)
                $page = array_merge($page, array("method_parameter1" => $this->router->uri->rsegments[3]));

            if ($trackingObj->isRegisteredPage($page)){

                $trackingCode .= $trackingObj->getSpecificCode($page, $data);

            }else if ($trackingObj->needToShowGenericTrackingPage()){

                $trackingCode .= $trackingObj->getAllPageCode($page, $data);
            }

        }
        
        return $trackingCode;
    }

    public function getsearchSpringSiteId($countryId){
        
        $searchSpringSiteId="";
        $searchSpringSiteArr=array(
            'gb'  => 'jdajtq',
            'es'  => '7g2sk7',
            'au'  => 'dkow9j',
            'nz'  => '61jj96',
            'it'  => '1eq9mh',
            'fr'  => 'rtkr86',
            'be'  => 'm15dls',
            'pl'  => 'yf45du',
            'nl'  => 'izisa8',
        );

        if (array_key_exists(strtolower($countryId),$searchSpringSiteArr)){
            $searchSpringSiteId= $searchSpringSiteArr[strtolower($countryId)];
        }

        return $searchSpringSiteId;
    }
}
