<?php
use ESG\Panther\Models\Website\RegisteredTrackingModel;
use ESG\Panther\Service\LoadSiteParameterService; 
use ESG\Panther\Service\AffiliateService; 

class MY_Loader extends CI_Loader 
{
	private $registerTacking;
	public function __construct()
	{
		parent::__construct();
		$this->registerTacking=new RegisteredTrackingModel();
		$this->affiliateService=new AffiliateService();

	}

	public function view($view, $vars = array(), $return = FALSE) 
	{
		
		if($view!="header"){
			$this->affiliateService->addAfCookie($_GET);
			if(empty($vars["platformCountryId"])){
				$this->loadSiteParameterService = new loadSiteParameterService();
				$vars["platformCountryId"]=$this->loadSiteParameterService->initSite()->getPlatformCountryId();
				$vars["searchSpringSiteId"]=$this->registerTacking->getsearchSpringSiteId($vars["platformCountryId"]);
			}
			if(empty($vars["trackingCodeJs"])){
				$vars["trackingCodeJs"] = $this->registerTacking->getTrackingCode($vars["tracking_data"]);
			}
		}
		return parent::view($view, $vars , $return);
	}

}