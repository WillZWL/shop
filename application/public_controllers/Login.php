<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Login extends PUB_Controller
{
    public function __construct()
    {
        DEFINE("SKIPCUR", 1);
        parent::__construct();
        $this->load->helper(array('url', 'object', 'tbswrapper'));
        $this->load->model('website/client_model');
        $this->load->model('mastercfg/country_model');
        $this->load->library('encrypt');
        $this->load->library('template');
    }

    public function checkout_login()
    {
        $data["back"] = $this->input->get("back");
        if ($this->input->post("posted")) {
            if ($_POST["password"]) {
                if (!$this->client_model->client_service->login($_POST["email"], $_POST["password"])) {
                    $_SESSION["NOTICE"] = $this->_get_fail_msg();
                }
            }
        }
        redirect(base_url() . urldecode($data["back"]));
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
        if ($this->input->post("posted")) {
            if ($_POST["page"] == "register") {
                if (isset($_SESSION["client_vo"])) {
                    $this->client_model->client_service->get_dao()->include_vo();
                    $data["client_obj"] = unserialize($_SESSION["client_vo"]);

                    $_POST["password"] = $this->encrypt->encode(strtolower($_POST["password"]));
                    if (empty($_POST["subscriber"])) {
                        $_POST["subscriber"] = 0;
                    }
                    set_value($data["client_obj"], $_POST);
                    $data["client_obj"]->set_del_name($_POST["title"] . " " . $_POST["forename"] . " " . $_POST["surname"]);
                    $data["client_obj"]->set_del_company($_POST["company_name"]);
                    $data["client_obj"]->set_del_address_1($_POST["address_1"]);
                    $data["client_obj"]->set_del_address_2($_POST["address_2"]);
                    $data["client_obj"]->set_del_city($_POST["city"]);
                    $data["client_obj"]->set_del_state($_POST["state"]);
                    $data["client_obj"]->set_del_country_id($_POST["country_id"]);
                    $data["client_obj"]->set_del_tel_1($_POST["tel_1"]);
                    $data["client_obj"]->set_del_tel_2($_POST["tel_2"]);
                    $data["client_obj"]->set_del_tel_3($_POST["tel_3"]);
                    $data["client_obj"]->set_client_id_no($_POST["client_id_no"]);
                    $data["client_obj"]->set_party_subscriber(0);
                    $data["client_obj"]->set_status(1);
                    $email = $data["client_obj"]->get_email();
                    $proc = $this->client_model->client_service->get_dao()->get(array("email" => $email));
                    if (!empty($proc)) {
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
                        $_SESSION["NOTICE"] = $lcw[get_lang_id()];
                        $data["register_failed_msg"] = $lcw[get_lang_id()];
                    } else {

                        if ($client_obj = $this->client_model->client_service->get_dao()->insert($data["client_obj"])) {
                            $this->client_model->client_service->object_login($client_obj, TRUE);
                            $this->client_model->register_success_event($client_obj);
                            unset($_SESSION["client_vo"]);
                            $_SESSION["client_vo"] = serialize($data["client_obj"]);
                            if ($data["back"]) {
                                echo "<script>parent.document.location.href='" . base_url() . urldecode($data["back"]) . "'</script>";
                            } else {
                                redirect(base_url());
                            }
                        } else {
                            $_SESSION["NOTICE"] = "Error: " . __LINE__;
                        }
                    }
                }
            } else {
                if ($_POST["password"]) {
                    if ($this->client_model->client_service->login($_POST["email"], $_POST["password"])) {
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
            if (empty($data["client_obj"])) {
                if (($data["client_obj"] = $this->client_model->client_service->get_dao()->get()) === FALSE) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__;
                } else {
                    $_SESSION["client_vo"] = serialize($data["client_obj"]);
                }
            }
            $data["bill_to_list"] = $this->country_model->get_country_name_in_lang(get_lang_id(), 1);
            $data["lang_id"] = get_lang_id();
            $data["notice"] = $_SESSION["NOTICE"];
            unset($_SESSION["NOTICE"]);
            $data["step"] = 2;
            $data["ajax"] = $this->input->get("x_sign_in") || strpos($this->input->get("back"), "x_sign_in") !== FALSE;
            $data["trackno"] = $_GET['tracknum'];

            $this->template->add_js('/js/checkform.js');

            $this->load_tpl('content', 'tbs_login', $data, TRUE, TRUE);
        }
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
        if (!($rs = $this->client_model->client_service->login($value["email"], $value["password"]))) {
            $this->objResponse->alert($this->_get_fail_msg());
        }
        $this->objResponse->setReturnValue($rs);

        return $this->objResponse;
    }

}
