<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";
include_once "Microsoft_translator/Access_token_authentication.php";

class Translate_service extends Base_service
{
    const SOAP_WSDL_URL = "http://api.microsofttranslator.com/V2/Soap.svc";
    const AUTH_URL = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
    const SCOPE_URL = "http://api.microsofttranslator.com";
    const GRANT_TYPE = "client_credentials";
    public $config;
    private $_soap;
    private $_authObj;
    private $_currentAcct; //= "autotranslation4";
    private $_translateAcct = array(
        array("clientId" => "auto_translation", "secret" => "vT7cYWYv9ds7+MFj7Wo/E22sGAD6iq2lvgKZNgnnOoo=")
    , array("clientId" => "auto_translation2", "secret" => "jeCY45P/FQK17T5rLxIDAcqfJX0P09KrGjkcqnyMP6c=")
    , array("clientId" => "autotranslation3", "secret" => "CWIAgIVahEFthk9irZ9hk/bl8FsYr3C/2WRKwzBKXzY=")
    , array("clientId" => "autotranslation4", "secret" => "92qciysyDsXQqmNewetFzaFn751xw2EaVy3CgP82pAY=")
    , array("clientId" => "autotranslation5", "secret" => "ex7xBIGqeRaFnKleSqL3ep7LnbSEMoISsgPd/shcb3E=")
    , array("clientId" => "autotranslation6", "secret" => "IoDVKSx79ozhYSrHlmq33nGpI2iXkCioFGXR7cRqVpg=")
    , array("clientId" => "autotranslation7", "secret" => "EmcaYcB6pvaYNdvho/AurcidwrQb5exmZrVSG2ggKR8=")
    , array("clientId" => "autotranslation8", "secret" => "BvYIW+WJDctTQz6C5yMxJs8Jt+lzpKFJ0hEoRr7MD9M=")
    , array("clientId" => "autotranslation9", "secret" => "9xbN6S2QwJcZebpd9NYXLmlGtrkLNsHxIUMoBMRdb14=")
    , array("clientId" => "autotranslation10", "secret" => "NJW+MMMLWFiIRlS8urwKrtFhWjtm+FZ7ywJjI8MTPGo=")
    , array("clientId" => "autotranslation11", "secret" => "LTKeovWn49WO/Qr+Zm04OoK3eWR/enwh/CFAQn+eu28=")
//reserve 2 accts to do manual update ,array("clientId" => "autotranslation12" , "secret" => "EUhxFMY2MCUwKPNWgaCjoLFjOlEsXJ3TkAk7EjZ+g00=")
        //,array("clientId" => "autotranslation13" , "secret" => "7M+D+lQlowoFIE6TaDQiJSyXZbeiT6ExCUVuMvMnBHA=")
    );

    public function __construct()
    {
        parent::__construct();
        $this->_authObj = new Access_Token_Authentication();
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        $client_id_from_db = $this->get_config()->value_of("bing_translator_client_id");
        $client_secret_from_db = $this->get_config()->value_of("bing_translator_client_secret");
        $this->_currentAcct = array("clientId" => $client_id_from_db, "secret" => $client_secret_from_db);
    }

    public function get_config()
    {
        return $this->config;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function translate($src_text, &$output_text, $from_lang, $to_lang)
    {
        $this->initSoap();

        $requestArg = array(
            'text' => $src_text,
            'from' => $from_lang,
            'to' => $to_lang,
            'contentType' => 'text/html',
            'category' => 'general'
        );
        do {
            $canLoop = false;
            try {
                $responseObj = $this->_soap->Translate($requestArg);
                $output_text = $responseObj->TranslateResult;
            } catch (Exception $ex) {
                if ($ex->getMessage() != "") {
                    if (stristr($ex->getMessage(), "zero balance") !== FALSE) {
                        //Update acct
                        $this->updateTranslateAccount();
                        $canLoop = true;
                    }
                } else {
                    throw $ex;
                }
            }
        } while ($canLoop);
//      var_dump($responseObj);
        return TRUE;
    }

    public function initSoap()
    {
        $accessToken = $this->_authObj->getTokens(Translate_service::GRANT_TYPE, Translate_service::SCOPE_URL, $this->_currentAcct["clientId"], $this->_currentAcct["secret"], Translate_service::AUTH_URL);
        $authHeader = "Authorization: Bearer " . $accessToken;
        $contextArr = array(
            'http' => array(
                'header' => $authHeader
            )
        );
        //Create a streams context.
        $objContext = stream_context_create($contextArr);
        $optionsArr = array(
            'soap_version' => 'SOAP_1_2',
            'encoding' => 'UTF-8',
            'exceptions' => true,
            'trace' => true,
            'cache_wsdl' => 'WSDL_CACHE_NONE',
            'stream_context' => $objContext,
            'user_agent' => 'PHP-SOAP/' . PHP_VERSION . "\r\n" . $authHeader
        );

        $this->_soap = new SoapClient(Translate_service::SOAP_WSDL_URL, $optionsArr);
    }

    public function updateTranslateAccount()
    {
        $numberOfAccts = sizeof($this->_translateAcct);
        for ($i = 0; $i < $numberOfAccts; $i++) {
            if ($this->_translateAcct[$i]["clientId"] == $this->_currentAcct["clientId"]) {
                if ($i == ($numberOfAccts - 1)) {
                    $this->_currentAcct = $this->_translateAcct[0];
                    mail("oswald-alert@eservicesgroup.com", "All accts might be consumed, line:" . __LINE__, "This email is just an indication that the acct has been rotated to the first one.", 'From: website@valuebasket.com');
                } else
                    $this->_currentAcct = $this->_translateAcct[$i + 1];
                break;
            }
        }
        $bing_translator_client_id = $this->get_config()->get_dao()->get(array("variable" => "bing_translator_client_id"));
        $bing_translator_client_id->set_value($this->_currentAcct["clientId"]);
        $bing_translator_client_secret = $this->get_config()->get_dao()->get(array("variable" => "bing_translator_client_secret"));
        $bing_translator_client_secret->set_value($this->_currentAcct["secret"]);

        $ret = $this->get_config()->get_dao()->update($bing_translator_client_id);
        if ($ret === FALSE) {
            mail("oswald-alert@eservicesgroup.com", "Update translation acct error, line:" . __LINE__, $this->get_config()->get_dao()->db->last_query(), 'From: website@valuebasket.com');
        }
        $ret = $this->get_config()->get_dao()->update($bing_translator_client_secret);
        if ($ret === FALSE) {
            mail("oswald-alert@eservicesgroup.com", "Update translation acct error, line:" . __LINE__, $this->get_config()->get_dao()->db->last_query(), 'From: website@valuebasket.com');
        }
        $this->initSoap();
        return true;
    }

    public function getNextClient()
    {

    }
}

?>