<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);


use ESG\Panther\Service\CountryService;

class Login extends PUB_Controller
{
    private $lang_id = 'en';
    public function __construct()
    {
        DEFINE("SKIPCUR", 1);
        parent::__construct();
        $this->load->helper(array('url', 'object', 'lang','notice'));
        $this->load->library('encryption');
        $this->load->library('encrypt');
/*
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
            $httpsUrl = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            if ($_SERVER['QUERY_STRING'] != "") {
                $httpsUrl .= "?" . $_SERVER['QUERY_STRING'];
            }
            if (strpos($_SERVER["REQUEST_URI"], "notification") === false) {
                redirect($httpsUrl);
            }
        }
*/
        $this->countryService = new CountryService;
    }

    public function checkout_login()
    {
        $data["back"] = $this->input->get("back");
        if ($this->input->post("posted")) {
            $this->index();
        }
    }

    public function _get_fail_msg()
    {
        $lfw = array("en" => "Login Failed",
            "de" => "Login fehlgeschlagen",
            "fr" => "Echec de la connexion",
            "es" => "Inicio de Sesi&oacute;n Fallido",
            "pt" => "Falha de logon.",
            "nl" => "Inloggen is mislukt",
            "ja" => "ログインに失敗しました",
            "it" => "Accesso non riuscito",
            "pl" => "Logowanie nie powiodło sie",
            "da" => "Login mislykkedes",
            "ko" => "로그인 실패",
            "tr" => "Giriş Başarısız",
            "sv" => "Inloggning misslyckades",
            "no" => "Logg inn mislyktes",
            "pt-br" => "Falha de logon",
            "ru" => "Войти не удалось");
        return $lfw[get_lang_id()];
    }

    public function index()
    {
        $data["back"] = $this->input->get("back");
        $siteobj = \PUB_Controller::$siteInfo;
        $countryid = $siteobj->getPlatformCountryId();
        if ($this->input->post("posted")) {
            if ($this->input->post("page") == "register") {
                $post_data = $this->input->post();
                if ($this->sc['Client']->getDao()->get(array('email'=>$post_data['email'])))
                {
                    $_SESSION['NOTICE'] = 'The Email Exist, Please Login or Change another email to Register';
                } else {
                    $post_data['country_id'] = $countryid;
                    $reg_res = $this->register($post_data);
                    if ($reg_res['res'] === TRUE) {
                        $client_obj = $reg_res['data'];
                        $this->sc['Client']->objectLogin($client_obj, TRUE);
                        $this->sc['Client']->registerSuccessEvent($client_obj);
                        $_SESSION["client_vo"] = serialize($data["client_obj"]);
                        if ($data["back"]) {
                            echo "<script>parent.document.location.href='" . base_url() . urldecode($data["back"]) . "'</script>";
                        } else {
                            redirect(base_url());
                        }
                    } else {
                       $data['reg_failed_msg'] = $_SESSION['NOTICE'] = $reg_res['data'];
                    }
                }
            } else {
                if ($this->input->post("password")) {
                    if ($this->sc['Client']->login($this->input->post("email"), $this->input->post("password"))) {
                        if ($data["back"]) {
                            redirect(base_url() . urldecode($data["back"]));
                        } else {
                            redirect(base_url() . "myaccount");
                        }
                    }
                }
                $data["login_failed_msg"] = $this->_get_fail_msg();
                $_SESSION["NOTICE"] = $this->_get_fail_msg();
            }
        }
        if ($_SESSION["client"]["logged_in"]) {
            redirect(base_url() . ($data["back"] ? urldecode($data["back"]) : ""));
        } else {
            $where = [];
            $option = [];
            $where["c.country_id"] = strtoupper($countryid);
            $where["l.lang_id"] = get_lang_id();
            $where["c.status"] = 1;
            $where["c.allow_sell"] = 1;
            $option["limit"] = 1;
            $data["bill_to_list"] = $this->countryService->getCountryExtDao()->getCountryNameInLang($where, $option);
            //$data["bill_to_list"] = $this->sc['countryModel']->getCountryNameInLang(get_lang_id(), 1);
            $data["lang_id"] = get_lang_id();
            $data["notice"] = notice();
            unset($_SESSION["NOTICE"]);
            $data["step"] = 2;
            $data["ajax"] = $this->input->get("x_sign_in") || strpos($this->input->get("back"), "x_sign_in") !== FALSE;
            $data["trackno"] = $_GET['tracknum'];
            $data['title'] = array('Mr', 'Mrs', 'Miss', 'Dr');
            $this->load->view('myaccount/login.php', $data);
        }
    }

    public function register($data)
    {
        $client_obj = $this->sc['Client']->getDao()->get();
        $client_vo = clone $client_obj;
        $data["password"] = $this->encryption->encrypt(trim($data["password"]));
        if (empty($data["subscriber"])) {
            $data["subscriber"] = 0;
        }

        $client_vo->setEmail($data['email']);
        $client_vo->setPassword($data['password']);

        $depassword = $this->encryption->decrypt($client_vo->getPassword());
        $encryptCode = $this->encrypt->encode($depassword);
        $client_vo->setVerifyCode($encryptCode);

        $client_vo->setTitle($data['title']);
        $client_vo->setForename($data['forename']);
        $client_vo->setSurname($data['surname']);
        $client_vo->setCompanyname($data['companyname']);
        $client_vo->setAddress1($data['address_1']);
        $client_vo->setAddress2($data['address_2']);
        $client_vo->setDelName($data["title"] . " " . $data["forename"] . " " . $data["surname"]);
        $client_vo->setDelCompany($data["company_name"]);
        $client_vo->setDelAddress1($data["address_1"]);
        $client_vo->setDelAddress2($data["address_2"]);
        $client_vo->setDelCity($data["city"]);
        $client_vo->setDelState($data["state"]);
        $client_vo->setDelCountryId($data["country_id"]);
        $client_vo->setDelTel1($data["tel_1"]);
        $client_vo->setDelTel2($data["tel_2"]);
        $client_vo->setDelTel3($data["tel_3"]);
        $client_vo->setTel1($data["tel_1"]);
        $client_vo->setTel2($data["tel_2"]);
        $client_vo->setTel3($data["tel_3"]);
        $client_vo->setState($data['state']);
        $client_vo->setCity($data['city']);
        $client_vo->setCountryId($data['country_id']);
        $client_vo->setPostcode($data['postcode']);
        $client_vo->setClientIdNo($data["client_id_no"]);
        $client_vo->setPartySubscriber(0);
        $client_vo->setSubscriber($data['subscriber']);
        $client_vo->setStatus(1);
        $email = $client_obj->getEmail();
        $proc = $this->sc['Client']->getDao()->get(array("email" => $email));
        $res = [];
        if (empty($proc)) {
            $client_obj = $this->sc['Client']->getDao()->insert($client_vo);
            if ($client_obj) {
               $res = array('res'=>TRUE, 'data'=>$client_obj);
            } else {
                $notice = "Error: " . __LINE__;
                $res = array('res'=>FALSE, 'data'=>$notice);
            }
        } else {
            $notice = $this->get_fail_reg_msg();
            $res = array('res'=>FALSE, 'data'=>$notice);
        }
        return $res;
    }

    public function get_fail_reg_msg()
    {
         $lcw = array(
            "en" => "Email is registered as our existing customer, please login or request for the password to be sent to you by clicking on the “Forgotten Password” link.",
            "de" => "Kunde existiert bereits",
            "fr" => "Client existant",
            "es" => "Cliente Existente",
            "pt" => "Cliente existe",
            "it" => "Client Esiste",
            "nl" => "Opdrachtgever Bestaat",
            "jp" => "お客様のアカウントは既に存在する",
            "pl" => "Konto juz Istnieje",
            "da" => "Kunde eksistere",
            "ko" => "계정이 이미 존재합니다",
            "tr" => "Bu müşteri zaten var",
            "sv" => "Kunden finns",
            "no" => "Kunden finnes",
            "pt-br" => "Cliente existe",
            "ru" => "Клиент существует"
        );
        return $lcw[get_lang_id()];
    }

    public function get_lang_id()
    {
        return $this->lang_id;
    }

    public function login_redirect()
    {
        if ($_SESSION["client"]["logged_in"]) {
            $this->load_view('sign_in');
        } else {
            show_404();
        }
    }

    public function _check_login($value)
    {
        if (!$this->objResponse) {
            $this->load->library('xajax');
            $this->objResponse = new xajaxResponse();
        }
        if (!($rs = $this->sc['Client']->login($value["email"], $value["password"]))) {
            $this->objResponse->alert($this->_get_fail_msg());
        }
        $this->objResponse->setReturnValue($rs);

        return $this->objResponse;
    }

    public function forget_password()
    {
        $data["back"] = $this->input->get("back");
        if ($this->input->get('reset')) {
            $email = trim($this->input->get('email'));
            if (empty($email)) {
                $data['error'] = 1;
                $data['notice'] = _('Please enter your registered email address');
            } else {
                if (preg_match("/\w+@(\w|\d)+\.\w{2,3}/i", $email)) {
                    $num = $this->sc['Client']->resetPassword($email);
                    if ($num > 0) {
                        $data['error'] = 0;
                    } else {
                        $data['error'] = 2;
                        $data['notice'] = _('Email Address does not exist in our system. Please click your email');
                    }
                } else {
                    $data['error'] = 3;
                    $data['notice'] = _('Your Email is illegal');
                }
            }
        }
        $this->load->view('myaccount/forget_password.php', $data);
    }
}
