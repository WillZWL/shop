<?php
use Pimple\Container;
use AtomV2\Service;
use AtomV2\Service\AuthorizationService;
use AtomV2\Service\AuthenticationService;
use AtomV2\Service\LanguageService;
use AtomV2\Models\Mastercfg\ColourModel;

abstract class MY_Controller extends CI_Controller
{
    private $langId = "en";
    protected $container;


    abstract public function getAppId();

    public function __construct($checkAccessRights = TRUE)
    {
        parent::__construct();
        $this->container = new Container();
        $this->loadModelDependcy();
        $this->loadServiceDependcy();

        $this->authenticationService = new AuthenticationService;
        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];
        $currsign = array("GBP" => "£", "EUR" => "€");
        if ($this->config->item('uri_protocol') != "CLI") {
            $this->checkAuthed();
            $this->authorizationService = new AuthorizationService;
            if ($checkAccessRights) {
                $this->authorizationService->checkAccessRights($this->getAppId(), "");
                $feature_list = $this->authorizationService->setApplicationFeatureRight($this->getAppId(), "");
            }
        }
    }

    public function loadModelDependcy()
    {
        $this->container['colourModel'] = function ($c) {
            return new ColourModel;
        };
    }

    private function loadServiceDependcy()
    {
        $this->container['languageService'] = $this->container->factory(function ($c) {
            return new LanguageService();
        });
    }


    public function getLangId()
    {
        return $this->langId;
    }

    private function checkAuthed()
    {
        if (!$this->authenticationService->checkAuthed()) {
            $data["fail_msg"] = $this->getFailMsg();
            redirect($this->getLoginPage());
        }
    }

    public function getFailMsg()
    {
        return "Please login to the system first!";
    }

    public function getLoginPage()
    {
        return "?back=" . urlencode($_SESSION["CURRPAGE"]);
    }

    public function getRu()
    {
        $ru = $_SESSION["CURRPAGE"];
        if ($pru = $this->input->post("ru")) {
            $ru = $this->encrypt->decode($pru);
        }

        return $ru;
    }

    public function setFormRu()
    {
        return "<input type='hidden' name='ru' value='".$this->encrypt->encode($_SESSION["CURRPAGE"])."'>";
    }
}
