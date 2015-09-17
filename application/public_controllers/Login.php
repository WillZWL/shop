<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Login extends PUB_Controller
{
    private $lang_id = 'en';
    public function __construct()
    {
        DEFINE("SKIPCUR", 1);
        parent::__construct();
        $this->load->helper(array('url', 'object', 'lang'));
        $this->load->model('website/client_model');
        $this->load->model('mastercfg/country_model');
        $this->load->library('encrypt');
    }

    public function checkout_login()
    {
        $data["back"] = $this->input->get("back");
        if ($this->input->post("posted")) {
            if ($_POST["password"] && $_POST['email']) {
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
            if ($this->input->post("page") == "register") {
                $post_data = $this->input->post();
                $reg_res = $this->register($post_data);
                if ($reg_res['res'] === TRUE) {
                    $client_obj = $reg_res['data'];
                    $this->client_model->client_service->object_login($client_obj, TRUE);
                    $this->client_model->register_success_event($client_obj);
                    $_SESSION["client_vo"] = serialize($data["client_obj"]);
                    if ($data["back"]) {
                        echo "<script>parent.document.location.href='" . base_url() . urldecode($data["back"]) . "'</script>";
                    } else {
                        redirect(base_url());
                    }
                } else {
                    $SESSION['NOTICE'] = $data["register_failed_msg"] = $reg_res['data'];
                }
            } else {
                if ($this->input->post("password")) {
                    if ($this->client_model->client_service->login($this->input->post("email"), $this->input->post("password"))) {
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
            $data["bill_to_list"] = $this->country_model->get_country_name_in_lang(get_lang_id(), 1);
            $data["lang_id"] = get_lang_id();
            $data["notice"] = $_SESSION["NOTICE"];
            unset($_SESSION["NOTICE"]);
            $data["step"] = 2;
            $data["ajax"] = $this->input->get("x_sign_in") || strpos($this->input->get("back"), "x_sign_in") !== FALSE;
            $data["trackno"] = $_GET['tracknum'];
            $data['title'] = array('Mr', 'Mrs', 'Miss', 'Dr');
            $this->load->view('/default/myaccount/login.php', $data);
        }
    }

    public function register($data)
    {
        $client_obj = $this->client_model->client_service->get_dao()->get();
        $client_vo = clone $client_obj;
        $data["password"] = $this->encrypt->encode(strtolower($data["password"]));
        if (empty($data["subscriber"])) {
            $data["subscriber"] = 0;
        }
        $client_vo->set_email($data['email']);
        $client_vo->set_password($data['password']);
        $client_vo->set_title($data['title']);
        $client_vo->set_forename($data['forename']);
        $client_vo->set_surname($data['surname']);
        $client_vo->set_companyname($data['companyname']);
        $client_vo->set_address_1($data['address_1']);
        $client_vo->set_address_2($data['address_2']);
        $client_vo->set_del_name($data["title"] . " " . $data["forename"] . " " . $data["surname"]);
        $client_vo->set_del_company($data["company_name"]);
        $client_vo->set_del_address_1($data["address_1"]);
        $client_vo->set_del_address_2($data["address_2"]);
        $client_vo->set_del_city($data["city"]);
        $client_vo->set_del_state($data["state"]);
        $client_vo->set_del_country_id($data["country_id"]);
        $client_vo->set_del_tel_1($data["tel_1"]);
        $client_vo->set_del_tel_2($data["tel_2"]);
        $client_vo->set_del_tel_3($data["tel_3"]);
        $client_vo->set_tel_1($data["tel_1"]);
        $client_vo->set_tel_2($data["tel_2"]);
        $client_vo->set_tel_3($data["tel_3"]);
        $client_vo->set_state($data['state']);
        $client_vo->set_city($data['city']);
        $client_vo->set_country_id($data['country_id']);
        $client_vo->set_postcode($data['postcode']);
        $client_vo->set_client_id_no($data["client_id_no"]);
        $client_vo->set_party_subscriber(0);
        $client_vo->set_status(1);
        $email = $client_obj->get_email();
        $proc = $this->client_model->client_service->get_dao()->get(array("email" => $email));
        $res = [];
        if (!empty($proc)) {
            $notice = $this->get_fail_reg_msg();
            $res = array('res'=>FALSE, 'data'=>$notice);
        } else {
            if ($client_obj = $this->client_model->client_service->get_dao()->insert($client_vo)) {
               $res = array('res'=>TRUE, 'data'=>$client_obj);
            } else {
                $notice = "Error: " . __LINE__;
                $res = array('res'=>FALSE, 'data'=>$notice);
            }
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
        if (!($rs = $this->client_model->client_service->login($value["email"], $value["password"]))) {
            $this->objResponse->alert($this->_get_fail_msg());
        }
        $this->objResponse->setReturnValue($rs);

        return $this->objResponse;
    }

}
