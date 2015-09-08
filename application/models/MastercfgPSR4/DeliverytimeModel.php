<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\LanguageService;
use AtomV2\Service\RegionService;
use AtomV2\Service\CountryService;
use AtomV2\Service\DeliverytimeService;

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
