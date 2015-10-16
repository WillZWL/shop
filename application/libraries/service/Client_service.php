<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Client_service extends Base_service
{

    private $cl_dao;
    private $country_dao;
    private $event_srv;
    private $reset_length = 10;
    //private $encrypt;

    private $p_enc = NULL;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Client_dao.php");
        $this->set_dao(new Client_dao());
        include_once(APPPATH . "libraries/dao/Client_log_dao.php");
        $this->set_cl_dao(new Client_log_dao());
        include_once(APPPATH . "libraries/dao/Country_dao.php");
        $this->set_country_dao(new Country_dao());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_event_srv(new Event_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        include_once(APPPATH . "libraries/service/Validation_service.php");
        $this->set_valid(new Validation_service());
        $CI->load->library('encryption');
        $this->encryption = $CI->encryption;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function set_valid($value)
    {
        $this->valid = $value;
    }

    public function login($email, $password)
    {
        if ($this->validate_field($email, array("valid_email"))) {
            $dao = $this->get_dao();
            if ($client_obj = $dao->get(array("email" => $email, "status" => 1))) {
                $_SESSION['client_obj'] = serialize($client_obj);
                $client_password = $client_obj->getPassword();
                if ($this->encryption->decrypt($client_password) === trim($password)) {
                    $this->objectLogin($client_obj, TRUE);
                    return TRUE;
                }
            } else {
                $this->login_log($email, 0);
            }
        }
        return FALSE;
    }

    public function validate_field($val, $rules)
    {
        $valid = $this->get_valid();
        $valid->set_data($val);
        $valid->set_rules($rules);
        try {
            return $valid->run();
        } catch(Exception $e) {
            return false;
        }
        return false;
    }

    public function get_valid()
    {
        return $this->valid;
    }

    public function object_login(Base_vo $obj, $logged_in = FALSE)
    {
        unset($_SESSION["client"]);
        $class_methods = get_class_methods($obj);
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 4) == "get_") {
                $rskey = substr($fct_name, 4);
                if ($rskey == "password")
                    continue;
                $rsvalue = call_user_func(array($obj, $fct_name));
                $_SESSION["client"][$rskey] = $rsvalue;
            }
        }
        if ($logged_in) {
            $_SESSION["client"]["logged_in"] = 1;
        }
        $this->get_client_platform();
        $this->login_log($obj->get_email(), 1);
    }

    public function get_client_platform()
    {
        $platform_id = $_SESSION["domain_platform"]["platform_id"];
        if (isset($_SESSION["client"])) {
            //reserved
            if ($this->p_enc) {
                include_once(BASEPATH . "libraries/Encrypt.php");
                $encrypt = new CI_Encrypt();
                $_SESSION["client"]["platform_id"] = $encrypt->decode($this->p_enc);
            } else {
                $_SESSION["client"]["platform_id"] = $_SESSION["domain_platform"]["platform_id"];
            }
        }
        return $platform_id;
    }

    public function login_log($email, $status)
    {
        $cl_vo = $this->get_cl_dao()->get();
        $cl_vo->set_email($email);
        $cl_vo->set_ip_address($_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "0.0.0.0");
        $cl_vo->set_status($status);
        $cl_vo = $this->get_cl_dao()->insert($cl_vo);
    }

    public function get_cl_dao()
    {
        return $this->cl_dao;
    }

    public function set_cl_dao(Base_dao $dao)
    {
        $this->cl_dao = $dao;
    }

    public function get_client_last_order($email)
    {
        $where = array("email" => $email, "so.status >=" => 2);
        $option = array("limit" => 1, "orderby" => "so.create_on desc");
        $last_order = $this->get_dao()->get_client_last_order($where, $option);
        return $last_order;
    }

    public function check_email_login($vars)
    {
        $vars["email"] = trim($vars["email"]);
        $this->p_enc = $vars["p_enc"];

        include_once(APPPATH . "helpers/object_helper.php");
        $dao = $this->get_dao();
        if ($client_obj = $dao->get(array("email" => $vars["email"]))) {
            $action = "update";
            if ($vars["password"]) {
                include_once(BASEPATH . "libraries/Encrypt.php");
                $encrypt = new CI_Encrypt();
                $vars["password"] = $encrypt->encode(strtolower($vars["password"]));
            } else
                $vars["password"] = $client_obj->get_password();
        } else {
            $client_obj = $dao->get();
            $action = "insert";
            include_once(BASEPATH . "libraries/Encrypt.php");
            $encrypt = new CI_Encrypt();
            if ($vars["password"]) {
                $vars["password"] = $encrypt->encode(strtolower($vars["password"]));
            } else {
                $vars["password"] = $encrypt->encode(mktime());
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
            if ($vars["client_id_no"])
                $vars["client_id_no"] = $vars["client_id_no"];
        }


        set_value($client_obj, $vars);
        $client_obj->set_del_name($vars["del_first_name"] . " " . $vars["del_last_name"]);
        $client_obj->set_party_subscriber(0);
        $client_obj->set_status(1);
        if ($dao->$action($client_obj)) {
            $this->object_login($client_obj, $_SESSION["client"]["logged_in"]);
            return TRUE;
        }
//      print "ERROR:" . $dao->db->_error_message();
        return FALSE;
    }

    public function reset_password($email = '')
    {
        $new_password = substr(md5(time()), 0, $this->reset_length);

        $client_obj = null;

        $result = $this->update_password($email, $new_password, '', $client_obj);

        if ($result) {
            $client_name = implode(' ', array($client_obj->get_forename(), $client_obj->get_surname()));
            $email_dto = $this->_get_email_dto();
            $replace = array('password' => $new_password, 'mail_from' => $email_dto->get_mail_from(), 'client name' => $client_name, 'base_url' => base_url());
            // switch (get_lang_id()) {
            //     default:
            //         include_once(APPPATH . "hooks/Country_selection.php");
            //         $replace = array_merge($replace, Country_selection::get_template_require_text(get_lang_id(), PLATFORMCOUNTRYID));
            //         $email_sender = "no-reply@" . strtolower($replace["site_name"]);
            // }
            $email_sender = "no-reply@digitaldiscount.co.uk";
            $email_dto->set_lang_id(get_lang_id());
            $email_dto->set_event_id('forget_password');
            $email_dto->set_tpl_id('forget_password');
            $email_dto->set_mail_to($client_obj->get_email());
            $email_dto->set_mail_from($email_sender);
            $email_dto->set_replace($replace);
            $this->get_event_srv()->fire_event($email_dto);
        }

        return $result;
    }

    public function update_password($email = '', $new_password = '', $old_password = '', &$client_obj = '')
    {
        if (empty($email) || empty($new_password)) {
            return 0; // Means fail
        }

        $where = array('email' => $email);
        $client_obj = $this->get_dao()->get($where);
        if (!$client_obj) {
            return 0; // Means fail
        } else {
            if ($client_obj->get_status() == 0) {
                return 0;
            } else {
                $client_obj->set_password($this->encryption->encrypt($new_password));
//               $client_obj->set_password($new_password_hash);
                $result = $this->get_dao()->update($client_obj);
                return $result;
            }
        }
    }

    private function _get_email_dto()
    {
        include_once APPPATH . "libraries/dto/Event_email_dto.php";
        return new Event_email_dto();
    }

    public function get_event_srv()
    {
        return $this->event_srv;
    }

    public function set_event_srv($srv)
    {
        $this->event_srv = $srv;
    }

    public function register_success_event($obj)
    {
        include_once(BASEPATH . "libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();

        $replace["name"] = $obj->get_forename();
        $replace["email"] = $obj->get_email();
        $replace["password"] = $encrypt->decode($obj->get_password());
        $replace["default_url"] = $this->get_config()->value_of("default_url");
        $replace["site_name"] = $this->get_config()->value_of("site_name");

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
        $this->include_dto("Event_email_dto");
        $dto = new Event_email_dto();
        $dto->set_event_id("register_success");
        $dto->set_mail_from($support_email);
        $dto->set_mail_to($obj->get_email());
        //$dto->set_mail_to('steven@eservicesgroup.net');
        $dto->set_tpl_id("register_success");
        $dto->set_lang_id(get_lang_id());
        $dto->set_replace($replace);

        $this->get_event_srv()->fire_event($dto);
    }

    public function get_config()
    {
        return $this->config;
    }

    public function get_new_vip_customer_list()
    {
        return $this->get_dao()->get_new_vip_customer_list();
    }

    public function get_country_dao()
    {
        return $this->country_dao;
    }

    public function set_country_dao(Base_dao $dao)
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