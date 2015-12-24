<?php
namespace ESG\Panther\Service;

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
        $CI->load->library('encryption');
        $this->encryption = $CI->encryption;
    }

    public function login($email, $password)
    {
        if ($this->validateField($email, array("valid_email"))) {
            $dao = $this->getDao('Client');
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
        $valid = $this->getService('Validation');
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
        $cl_vo = $this->getDao('ClientLog')->get();
        $cl_vo->setEmail($email);
        $cl_vo->setIpAddress($_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "0.0.0.0");
        $cl_vo->setStatus($status);
        $cl_vo = $this->getDao('ClientLog')->insert($cl_vo);
    }

    public function getClientLastOrder($email)
    {
        $where = array("email" => $email, "so.status >=" => 2);
        $option = array("limit" => 1, "orderby" => "so.create_on desc");
        $last_order = $this->getDao('Client')->getClientLastOrder($where, $option);
        return $last_order;
    }
/***************************************************
**  createClient use by SoFactory
**  We will better to use the same function for MyAcct page to create Client
****************************************************/
    public function createClient($clientInfo = [], $delegate = null, $requireLogin = false) {
        $email = $clientInfo["email"];
        if ($clientObj = $this->getDao('Client')->get(array("email" => $email))) {
            $action = "update";
        } else {
            $clientObj = $this->getDao('Client')->get();
            $action = "insert";
        }
        $this->setClientDetail($clientObj, $clientInfo);

        if ($delegate instanceof CreateClientInterface)
            $delegate->clientBeforeUpdateEvent($clientObj);

        $actionResult = $this->getDao('Client')->$action($clientObj);
        if ($actionResult === false) {
            $subject = "[Panther] Cannot create/update client:" . __METHOD__ . __LINE__;
            $message = $this->getDao('Client')->db->last_query() . "," . $this->getDao('Client')->db->_error_message();
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
        $dao = $this->getDao('Client');
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
            $email_dto = new \EventEmailDto();
            $replace = array('password' => $newPassword, 'mail_from' => $email_dto->getMailFrom(), 'client name' => $client_name, 'site_name' => 'Digital discount', 'site_url' => base_url());
            $email_sender = "no-reply@digitaldiscount.co.uk";
            $email_dto->setLangId(get_lang_id());
            $email_dto->setEventId('forget_password');
            $email_dto->setTplId('forget_password');
            $email_dto->setMailTo($client_obj->getEmail());
            $email_dto->setMailFrom($email_sender);
            $email_dto->setReplace($replace);
            $email_dto->setPlatformId(PLATFORM);
            $this->getService('Event')->fireEvent($email_dto);
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

        $client_obj = $this->getDao('Client')->get($where);

        if (!$client_obj) {
            return 0;
        } else if ($client_obj->getStatus() == 0) {
            return 0;
        }
        $client_obj->setPassword($this->encryption->encrypt($newPassword));
        $result = $this->getDao('Client')->update($client_obj);
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
        $replace["default_url"] = $this->getService('ContextConfig')->valueOf("default_url");
        $replace["site_name"] = $this->getService('ContextConfig')->valueOf("site_name");
        $dto = new \EventEmailDto();
        $dto->setEventId("register_success");
        $dto->setMailFrom($support_email);
        $dto->setMailTo($obj->getEmail());
        $dto->setTplId("register_success");
        $dto->setLangId(get_lang_id());
        $dto->setReplace($replace);
        $this->getService('Event')->fireEvent($dto);
    }

    public function get_config()
    {
        return $this->config;
    }

    public function get_new_vip_customer_list()
    {
        return $this->get_dao()->get_new_vip_customer_list();
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