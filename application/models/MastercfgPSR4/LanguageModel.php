<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\LanguageService;

class LanguageModel extends \CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // $this->languageService = new LanguageService;
    }

    public function getNameWIdKey()
    {
        return $this->sc['Language']->getNameWIdKey();
    }

    public function getList()
    {
        return $this->sc['Language']->getDao('Language')->getList(["status" => 1], ["limit" => -1]);
    }
}
