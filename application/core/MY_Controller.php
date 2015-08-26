<?php
use AtomV2\Service\AuthorizationService;
use AtomV2\Service\AuthenticationService;

abstract class MY_Controller extends CI_Controller
{
    private $langId = "en";

    public function __construct($checkAccessRights = TRUE)
    {
        parent::__construct();
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

    private function checkAuthed()
    {
        if (!$this->authenticationService->checkAuthed()) {
            $data["fail_msg"] = $this->getFailMsg();
            redirect($this->getLoginPage());
        }
    }

    function getFailMsg()
    {
        return "Please login to the system first!";
    }

    function getLoginPage()
    {
        return "?back=" . urlencode($_SESSION["CURRPAGE"]);
    }

    abstract public function getAppId();

    public function getLangId()
    {
        return $this->langId;
    }

    function getRu()
    {
        $ru = $_SESSION["CURRPAGE"];
        if ($pru = $this->input->post("ru")) {
            $ru = $this->encrypt->decode($pru);
        }
        return $ru;
    }

    function setFormRu()
    {
        return "<input type='hidden' name='ru' value='".$this->encrypt->encode($_SESSION["CURRPAGE"])."'>";
    }
}
