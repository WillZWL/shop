<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\LanguageService;
use ESG\Panther\Service\RegionService;
use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\DeliverytimeService;

class DeliverytimeModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->languageService = new LanguageService;
        $this->regionService = new RegionService;
        $this->countryService = new CountryService;
        $this->deliverytimeService = new DeliverytimeService;
    }

}
