<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ClientDao;
use ESG\Panther\Dao\ClientLogDao;
use ESG\Panther\Dao\CountryDao;
use ESG\Panther\Service\EventService;
use ESG\Panther\Service\ContextConfigService;
use ESG\Panther\Service\ValidationService;


class ClientService extends BaseService
{
    private $clientLogDao;
    private $countryDao;
    private $reset_length = 10;

    private $p_enc = NULL;
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->setDao(new ClientDao);
        $this->setClientLogDao(new ClientLogDao);
        $this->setCountryDao(new CountryDao);
        $this->eventService = new EventService;
        $this->contextConfigService = new ContextConfigService;
        $this->validationService = new ValidationService;
        $CI->load->library('encryption');
        $this->encryption = $CI->encryption;
    }

    public function login($email, $password)
    {
        if ($this->validateField($email, array("valid_email"))) {
            $dao = $this->getDao();
            if ($client_obj = $dao->get(array("email" => $email, "status" => 1))) {
                $client_password = $client_obj->getPassword();
                if ($this->encryption->decrypt($client_password) === trim($password)) {
                    $this->objectLogin($client_obj, TRUE);
                    return TRUE;
                }
            }
            $this->loginLog($email, 0);
            return FALSE;
        }
        return FALSE;
    }

    public function validateField($val, $rules)
    {
        $valid = $this->validationService;
        $valid->setData($val);
        $valid->setRules($rules);
        try {
            return $valid->run();
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    public function objectLogin(\BaseVo $obj, $loggedIn = FALSE)
    {
        unset($_SESSION["client"]);
        $class_methods = get_class_methods($obj);
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 3) == "get") {
                $rskey = substr($fct_name, 3);
                if ($rskey == "password")
                    continue;
                $rsvalue = call_user_func(array($obj, $fct_name));
                $_SESSION["client"][$rskey] = $rsvalue;
            }
        }
        if ($loggedIn) {
//            $_SESSION["client"]["obj"] = $obj;
            $_SESSION["client"]["loggedIn"] = 1;
        }
        $this->getClientPlatform();
        $this->loginLog($obj->getEmail(), 1);
    }

    public function getClientPlatform()
    {
        $platform_id = $_SESSION["domain_platform"]["platform_id"];
        if (isset($_SESSION["client"])) {
            //reserved
            if ($this->p_enc) {
                $_SESSION["client"]["platform_id"] = $this->encryption->decrypt($this->p_enc);
            } else {
                $_SESSION["client"]["platform_id"] = $_SESSION["domain_platform"]["platform_id"];
            }
        }
        return $platform_id;
    }

    public function loginLog($email, $status)
    {
        $cl_vo = $this->getClientLogDao()->get();
        $cl_vo->setEmail($email);
        $cl_vo->setIpAddress($_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "0.0.0.0");
        $cl_vo->setStatus($status);
        $cl_vo = $this->getClientLogDao()->insert($cl_vo);
    }

    public function getClientLogDao()
    {
        return $this->clientLogDao;
    }

    public function setClientLogDao($dao)
    {
        $this->clientLogDao = $dao;
    }

    public function getClientLastOrder($email)
    {
        $where = array("email" => $email, "so.status >=" => 2);
        $option = array("limit" => 1, "orderby" => "so.create_on desc");
        $last_order = $this->getDao()->getClientLastOrder($where, $option);
        return $last_order;
    }
/***************************************************
**  createClient use by SoFactory
**  We will better to use the same function for MyAcct page to create Client
****************************************************/
    public function createClient($clientInfo = [], $delegate = null, $requireLogin = false) {
        $email = $clientInfo["email"];
        if ($clientObj = $this->getDao()->get(array("email" => $email))) {
            $action = "update";
        } else {
            $clientObj = $this->getDao()->get();
            $action = "insert";
        }
        $this->setClientDetail($clientObj, $clientInfo);

        if ($delegate instanceof CreateClientInterface)
            $delegate->clientBeforeUpdateEvent($clientObj);

        $actionResult = $this->getDao()->$action($clientObj);
        if ($actionResult === false) {
            $subject = "[Panther] Cannot create/update client:" . __METHOD__ . __LINE__;
            $message = $this->getDao()->db->last_query() . "," . $this->getDao()->db->_error_message();
            $this->sendAlert($subject, $message, "oswald-alert@eservicesgroup.com", BaseService::ALERT_HAZARD_LEVEL);
        } else {
            if ($delegate instanceof CreateClientInterface)
                $delegate->clientCreateSuccessEvent($clientObj);

//            if ($requireLogin)
//                $this->objectLogin($clientObj, $_SESSION["client"]["logged_in"]);
            return $clientObj;
        }
        return false;
    }

    private function setClientDetail($clientObj, $clientInfo = []) {
        $clientObj->setStatus(1);
        if (isset($clientInfo["email"]))
            $clientObj->setEmail($clientInfo["email"]);

        if (isset($clientInfo["billPassword"])) {
            $clientObj->setPassword($this->encryption->encrypt($clientInfo["billPassword"]));
        } elseif (!$clientObj->getPassword())
            $clientObj->setPassword($this->encryption->encrypt(mktime()));

        if (isset($clientInfo["extClientId"]))
            $clientObj->setExtClientId($clientInfo["extClientId"]);
        if (isset($clientInfo["clientIdNo"]))
            $clientObj->setClientIdNo($clientInfo["clientIdNo"]);
//billing info
        if (isset($clientInfo["billFirstName"]))
            $clientObj->setForename($clientInfo["billFirstName"]);
        if (isset($clientInfo["billLastName"]))
            $clientObj->setSurname($clientInfo["billLastName"]);
        if (isset($clientInfo["billCompany"]))
            $clientObj->setCompanyname($clientInfo["billCompany"]);
        if (isset($clientInfo["billCountry"]))
            $clientObj->setCountryId($clientInfo["billCountry"]);
        if (isset($clientInfo["billAddress1"]))
            $clientObj->setAddress1($clientInfo["billAddress1"]);
        if (isset($clientInfo["billAddress2"]))
            $clientObj->setAddress2($clientInfo["billAddress2"]);
        if (isset($clientInfo["billAddress3"]))
            $clientObj->setAddress3($clientInfo["billAddress3"]);
        if (isset($clientInfo["billCity"]))
            $clientObj->setCity($clientInfo["billCity"]);
        if (isset($clientInfo["billPostal"]))
            $clientObj->setPostcode($clientInfo["billPostal"]);
        if (isset($clientInfo["billState"]))
            $clientObj->setState($clientInfo["billState"]);
        if (isset($clientInfo["billTelCountryCode"]))
            $clientObj->setTel1($clientInfo["billTelCountryCode"]);
        if (isset($clientInfo["billTelAreaCode"]))
            $clientObj->setTel2($clientInfo["billTelAreaCode"]);
        if (isset($clientInfo["billTelNumber"]))
            $clientObj->setTel3($clientInfo["billTelNumber"]);
        if (isset($clientInfo["billTelNumber"]))
            $clientObj->setTel3($clientInfo["billTelNumber"]);
//shipping info
        $shipName = "";
        if (isset($clientInfo["shipFirstName"]))
            $shipName .= $clientInfo["shipFirstName"];
        if (isset($clientInfo["shipLastName"]))
        {
            if ($shipName != "")
                $shipName .= " ";
            $shipName .= $clientInfo["shipLastName"];
        }
        if ($shipName != "")
            $clientObj->setDelName($shipName);
        if (isset($clientInfo["shipCompany"]))
            $clientObj->setDelCompany($clientInfo["shipCompany"]);
        if (isset($clientInfo["shipAddress1"]))
            $clientObj->setDelAddress1($clientInfo["shipAddress1"]);
        if (isset($clientInfo["shipAddress2"]))
            $clientObj->setDelAddress2($clientInfo["shipAddress2"]);
        if (isset($clientInfo["shipAddress3"]))
            $clientObj->setDelAddress3($clientInfo["shipAddress3"]);
        if (isset($clientInfo["shipCity"]))
            $clientObj->setDelCity($clientInfo["shipCity"]);
        if (isset($clientInfo["shipPostal"]))
            $clientObj->setDelPostcode($clientInfo["shipPostal"]);
        if (isset($clientInfo["shipState"]))
            $clientObj->setDelState($clientInfo["shipState"]);
        if (isset($clientInfo["shipCountry"]))
            $clientObj->setDelCountryId($clientInfo["shipCountry"]);
        if (isset($clientInfo["shipTelCountryCode"]))
            $clientObj->setDelTel1($clientInfo["shipTelCountryCode"]);
        if (isset($clientInfo["shipTelAreaCode"]))
            $clientObj->setDelTel2($clientInfo["shipTelAreaCode"]);
        if (isset($clientInfo["shipTelNumber"]))
            $clientObj->setDelTel3($clientInfo["shipTelNumber"]);
//other info
        if (isset($clientInfo["subscriber"]))
            $clientObj->setSubscriber($clientInfo["subscriber"]);
        else
            $clientObj->setSubscriber(1);
        if (isset($clientInfo["subscriber"]))
            $clientObj->setSubscriber($clientInfo["subscriber"]);
        else
            $clientObj->setSubscriber(1);
        if (isset($clientInfo["partySubscriber"]))
            $clientObj->setPartySubscriber($clientInfo["partySubscriber"]);
        else
            $clientObj->setPartySubscriber(0);
        if (isset($clientInfo["vip"]))
            $clientObj->setVip($clientInfo["vip"]);
        else
            $clientObj->setVip(0);

        return $clientObj;
    }

    public function checkEmailLogin($vars)
    {
        $vars["email"] = trim($vars["email"]);
        $this->p_enc = $vars["p_enc"];
        include_once(APPPATH . "helpers/object_helper.php");
        $dao = $this->getDao();
        if ($client_obj = $dao->get(array("email" => $vars["email"]))) {
            $action = "update";
            if ($vars["password"]) {
                $vars["password"] = $this->encryption->encrypt(strtolower($vars["password"]));
            } else
                $vars["password"] = $client_obj->getPassword();
        } else {
            $client_obj = $dao->get();
            $action = "insert";
            if ($vars["password"]) {
                $vars["password"] = $this->encryption->encrypt(strtolower($vars["password"]));
            } else {
                $vars["password"] = $this->encryption->encrypt(mktime());
            }
        }

        if (!$vars["billaddr"]) {
            $vars["forename"] = $vars["del_first_name"];
            $vars["surname"] = $vars["del_last_name"];
            $vars["address_1"] = $vars["del_address_1"];
            $vars["address_2"] = $vars["del_address_2"];
            $vars["city"] = $vars["del_city"];
            $vars["state"] = $vars["del_state"];
            $vars["postcode"] = $vars["del_postcode"];
            $vars["country_id"] = $vars["del_country_id"];
            $vars["companyname"] = $vars["del_company"];
            #SBF 2958
            if ($vars["client_id_no"]) {
                $vars["client_id_no"] = $vars["client_id_no"];
            }
        }
        set_value($client_obj, $vars);
        $client_obj->setDelName($vars["del_first_name"] . " " . $vars["del_last_name"]);
        $client_obj->setPartySubscriber(0);
        $client_obj->setStatus(1);
        if ($dao->$action($client_obj)) {
            $this->objectLogin($client_obj, $_SESSION["client"]["logged_in"]);
            return TRUE;
        }
//      print "ERROR:" . $dao->db->_error_message();
        return FALSE;
    }

    public function resetPassword($email = '')
    {
        $newPassword = substr(md5(time()), 0, $this->reset_length);

        $client_obj = null;

        $result = $this->updatePassword($email, $newPassword, '', $client_obj);

        if ($result) {
            $client_name = implode(' ', array($client_obj->getForename(), $client_obj->getSurname()));

            $email_dto = $this->getEmailDto();
            $replace = array('password' => $newPassword, 'mail_from' => $email_dto->get_mail_from(), 'client name' => $client_name, 'base_url' => base_url());
            // switch (get_lang_id()) {
            //     default:
            //         include_once(APPPATH . "hooks/country_selection.php");
            //         $replace = array_merge($replace, Country_selection::get_template_require_text(get_lang_id(), PLATFORMCOUNTRYID));
            //         $email_sender = "no-reply@" . strtolower($replace["site_name"]);
            // }
            $email_sender = "no-reply@digitaldiscount.co.uk";
            $email_dto->setLangId(get_lang_id());
            $email_dto->setEventId('forget_password');
            $email_dto->setTplId('forget_password');
            $email_dto->setMailTo($client_obj->getEmail());
            $email_dto->setMailFrom($email_sender);
            $email_dto->setReplace($replace);
            $this->eventService->fireEvent($email_dto);
        }

        return $result;
    }

    public function updatePassword($email = '', $newPassword = '', $oldPassword = '', &$client_obj = '')
    {
        if (empty($email) || empty($newPassword)) {
            return 0; // Means fail
        }
        if (empty($oldPassword)) {
            $where = array('email' => $email);
        } else {
            $encrypted_oldPassword = $this->encryption->encrypt($oldPassword);
            $where = array('email' => $email, 'password' => $encrypted_oldPassword);
        }

        $client_obj = $this->getDao()->get($where);

        if (!$client_obj) {
            return 0; // Means fail
        } else if ($client_obj->getStatus() == 0) {
            return 0; // not allow to update
        }

        $client_obj->setPassword($this->encryption->encrypt($newPassword));
        $result = $this->getDao()->update($client_obj);
        return $result;
    }

    private function getEmailDto()
    {
        include_once APPPATH . "libraries/dto/event_email_dto.php";
        return new Event_email_dto();
    }


    public function registerSuccessEvent($obj)
    {
        $replace["name"] = $obj->getForename();
        $replace["email"] = $obj->getEmail();
        $replace["password"] = $this->encryption->encrypt($obj->getPassword());
        $replace["default_url"] = $this->contextConfigService->valueOf("default_url");
        $replace["site_name"] = $this->contextConfigService->valueOf("site_name");

        switch (get_lang_id()) {
            case 'en':
            case 'es':
            case 'de':
            case 'fr':
            case 'nl':
            case 'pt':
            case 'ja':
            case 'kr':
            default:
                $support_email = 'no-reply@valuebasket.com';
                break;
        }
        $replace["support_email"] = $support_email;
        $dto = new \EventEmailDto();
        $dto->setEventId("register_success");
        $dto->setMailFrom($support_email);
        $dto->setMailTo($obj->getEmail());
        //$dto->set_mail_to('steven@eservicesgroup.net');
        $dto->setTplId("register_success");
        $dto->setLangId(get_lang_id());
        $dto->setReplace($replace);
        $this->eventService->fireEvent($dto);
    }

    public function get_config()
    {
        return $this->config;
    }

    public function get_new_vip_customer_list()
    {
        return $this->get_dao()->get_new_vip_customer_list();
    }

    public function getCountryDao()
    {
        return $this->country_dao;
    }

    public function setCountryDao($dao)
    {
        $this->country_dao = $dao;
    }

    public function get_encrypt()
    {
        return $this->encrypt;
    }

    public function set_encrypt($value)
    {
        $this->encrypt = $value;
    }

    public function include_vo()
    {
        $this->get_dao()->include_vo();
    }
}