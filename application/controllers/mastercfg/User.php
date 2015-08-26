<?php
use AtomV2\Models\Mastercfg\UserModel;
use AtomV2\Service\LogService;
use AtomV2\Service\PaginationService;
use AtomV2\Service\AuthorizationService;

class User extends MY_Controller
{
    private $appId = "MST0001";
    private $langId = "en";

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel;
        $this->logService = new LogService;
        $this->paginationService = new PaginationService;
        $this->authorizationService = new AuthorizationService;
    }

    public function index()
    {
        $subAppId = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/user/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $where["id"] = $this->input->get("id");
        $where["username"] = $this->input->get("username");
        $where["email"] = $this->input->get("email");
        $where["roles"] = $this->input->get("roles");
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort)) {
            $sort = "id";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->get("showall")) {
            $data = $this->userModel->getListWRoles($where, $option);
        } else {
            $where["status"] = 1;
            $data = $this->userModel->getListWRoles($where, $option);
        }

        $data["lang"] = $lang;
        $pconfig['total_rows'] = $data['total'];
        $this->paginationService->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('mastercfg/user/user_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->langId;
    }

    public function add()
    {
        $subAppId = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["user_vo"])) {
                //$this->userModel->include_userVo();
                $data["user"] = unserialize($_SESSION["user_vo"]);
                $_POST["password"] = md5($_POST["password"]);
                set_value($data["user"], $_POST);

                $id = $data["user"]->getId();
                $proc = $this->userModel->getUser(array("id" => $id));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "user_existed";
                } else {
                    if ($this->userModel->addUser($data["user"])) {
                        $user_role_vo = $this->userModel->getUserRole();

                        if ($this->input->post("joined_list")) {
                            foreach ($this->input->post("joined_list") as $role_id) {
                                $user_role_obj = $user_role_vo;
                                call_user_func(array($user_role_obj, "setUserId"), $id);
                                call_user_func(array($user_role_obj, "setRoleId"), $role_id);
                                $this->userModel->addUserRole($user_role_obj);
                            }
                        }
                        unset($_SESSION["user_vo"]);
                        redirect(base_url() . "mastercfg/user/view/" . $id);
                    } else {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        if (empty($data["user"])) {
            if (($data["user"] = $this->userModel->getUser()) === FALSE) {
                $_SESSION["NOTICE"] = "sql_error";
            } else {
                $_SESSION["user_vo"] = serialize($data["user"]);
            }
        }

        $data["role_list"] = $this->userModel->getRoleList();
        $data["joined_list"] = array();

        if ($this->input->post("joined_list")) {
            $inc_list = $this->input->post("joined_list");
            $data["joined_list"] = get_inclusion($data["role_list"], $inc_list, "Id");
            $data["role_list"] = get_exclusion($data["role_list"], $inc_list, "Id");
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('mastercfg/user/user_detail_v', $data);
    }

    public function view($id = "")
    {
        if ($id) {
            $subAppId = $this->getAppId() . "02";

            if ($this->input->post("posted")) {

                if (isset($_SESSION["user_vo"])) {
                    //$this->userModel->includeVserVo();
                    $data["user"] = unserialize($_SESSION["user_vo"]);

                    $_POST["password"] = empty($_POST["password"]) ? $data["user"]->getPassword() : md5($_POST["password"]);

                    if ($data["user"]->getId() != $_POST["id"]) {
                        $proc = $this->userModel->getUser(array("id" => $_POST["id"]));
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "user_existed";
                        }
                    } else {
                        set_value($data["user"], $_POST);

                        if ($this->userModel->update_user($data["user"])) {
                            print_r($id);
                            $this->userModel->delUserRole(array("user_id" => $id));
                            $user_role_vo = $this->userModel->getUserRole();

                            if ($this->input->post("joined_list")) {
                                foreach ($this->input->post("joined_list") as $role_id) {
                                    $user_role_obj = $user_role_vo;
                                    call_user_func(array($user_role_obj, "setUserId"), $id);
                                    call_user_func(array($user_role_obj, "setRoleId"), $role_id);
                                    $this->userModel->addUserRole($user_role_obj);
                                }
                            }
                            unset($_SESSION["user_vo"]);
                            redirect(base_url() . "mastercfg/user/view/" . $id);
                        } else {
                            $_SESSION["NOTICE"] = "submit_error";
                        }
                    }
                }
            }

            include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            if (empty($data["user"])) {
                if (($data["user"] = $this->userModel->getUser(array("id" => $id))) === FALSE) {
                    $_SESSION["NOTICE"] = "sql_error";
                } else {
                    $_SESSION["user_vo"] = serialize($data["user"]);
                }
            }

            $data["role_list"] = $this->userModel->getRoleList();
            $data["joined_list"] = array();
            if ($this->input->post("joined_list")) {
                $inc_list = $this->input->post("joined_list");
                $data["joined_list"] = get_inclusion($data["role_list"], $inc_list, "Id");
                $data["role_list"] = get_exclusion($data["role_list"], $inc_list, "Id");
            } else {
                $inc_list = $this->userModel->getUserRoleList(array("user_id" => $id));
                if ((array) $inc_list) {
                    $data["joined_list"] = get_inclusion($data["role_list"], $inc_list, "Id", "RoleId");
                    $data["role_list"] = get_exclusion($data["role_list"], $inc_list, "Id", "RoleId");
                }
            }

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('mastercfg/user/user_detail_v', $data);
        }
    }

    public function delete($id = "")
    {
        if (($userVo = $this->userModel->getUser(array("id" => $id))) === FALSE) {
            $_SESSION["NOTICE"] = "submit_error";
        } else {
            if (empty($userVo)) {
                $_SESSION["NOTICE"] = "user_not_found";
            } else {
                $userVo->setStatus(0);
                if (!$this->userModel->inactiveUser($userVo)) {
                    $_SESSION["NOTICE"] = "submit_error";
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }

    }
}


