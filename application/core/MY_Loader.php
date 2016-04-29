<?php
use ESG\Panther\Models\Website\RegisteredTrackingModel;
use ESG\Panther\Service\LoadSiteParameterService; 

class MY_Loader extends CI_Loader {

		private $registerTacking;
		public function __construct(){

			parent::__construct();
			$this->registerTacking=new RegisteredTrackingModel();

		}

		public function view($view, $vars = array(), $return = FALSE) {

			if($view!="header"){

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