<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Register extends PUB_Controller
{
    public function Register()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url', 'object'));
        $this->load->library('encrypt');
        $this->load->model('website/client_model');
        $this->load->model('mastercfg/country_model');
    }

    public function index()
    {
        if (($data["client_obj"] = $this->client_model->client_service->get_dao()->get()) === FALSE) {
            $_SESSION["NOTICE"] = "Error: " . __LINE__;
        } else {
            $_SESSION["client_vo"] = serialize($data["client_obj"]);
        }
        $data["bill_to_list"] = $this->country_model->get_country_name_in_lang(get_lang_id(), 1);
        $data["back"] = $this->input->get("back");
        $data["action"] = base_url() . "register/add";
        if ($data["back"]) {
            $this->load_view('register/register_form.php', $data);
        } else {
            $this->load_view('register/register.php', $data);
        }
    }

    public function add()
    {
        if ($this->input->post("posted")) {
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
                $data["client_obj"]->set_party_subscriber(0);
                $data["client_obj"]->set_status(1);
                $email = $data["client_obj"]->get_email();
                $proc = $this->client_model->client_service->get_dao()->get(array("email" => $email));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "Client Exists";
                } else {

                    if ($client_obj = $this->client_model->client_service->get_dao()->insert($data["client_obj"])) {
                        $this->client_model->client_service->object_login($client_obj, TRUE);
                        $this->client_model->register_success_event($client_obj);
                        unset($_SESSION["client_vo"]);
                        if ($_POST["back"]) {
                            echo "<script>parent.document.location.href='" . base_url() . urldecode($_POST["back"]) . "'</script>";
                        } else {
                            redirect(base_url());
                        }
                    } else {
                        $_SESSION["NOTICE"] = "Error: " . __LINE__;
                    }
                }
            }
        }

        if (empty($data["client_obj"])) {
            if (($data["client_obj"] = $this->client_model->client_service->get_dao()->get()) === FALSE) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__;
            } else {
                $_SESSION["client_vo"] = serialize($data["client_obj"]);
            }
        }

        $data["country_list"] = $this->region_service->get_sell_country_list();
        $data["notice"] = $_SESSION["NOTICE"];
        unset($_SESSION["NOTICE"]);
        $data["back"] = $this->input->post("back");
        if ($this->input->post($data["back"])) {
            $this->load_view('register/register_form.php', $data);
        } else {
            $this->load_view('register/register.php', $data);
        }
    }

}
