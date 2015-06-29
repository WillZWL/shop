<?php
class User extends MY_Controller
{
    private $app_id="MST0001";
    private $lang_id="en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/user_model');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/Log_service');
        $this->load->library('service/Pagination_service');
        $this->load->library('service/Authorization_service');
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

        $_SESSION["LISTPAGE"] = base_url()."mastercfg/user/?".$_SERVER['QUERY_STRING'];

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

        if (empty($sort)){
            $sort = "id";
        }

        if (empty($order)){
            $order = "asc";
        }

        $option["orderby"] = $sort." ".$order;

        if ($this->input->get("showall")) {
            $data = $this->user_model->get_list_w_roles($where, $option);
        } else {
            $where["status"] = 1;
            $data = $this->user_model->get_list_w_roles($where, $option);
        }

        $data["lang"] = $lang;
        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order =="asc" ? "desc" : "asc" ;
        $data["searchdisplay"] = "";
        $this->load->view('mastercfg/user/user_index_v', $data);
    }

    public function add()
    {

        $sub_app_id = $this->_get_app_id()."01";

        if ($this->input->post("posted"))
        {
            if (isset($_SESSION["user_vo"]))
            {
                $this->user_model->include_user_vo();
                $data["user"] = unserialize($_SESSION["user_vo"]);

                $_POST["password"] = md5($_POST["password"]);
                set_value($data["user"], $_POST);

                $id = $data["user"]->get_id();
                $proc = $this->user_model->get_user(array("id"=>$id));
                if (!empty($proc))
                {
                    $_SESSION["NOTICE"] = "user_existed";
                }
                else
                {

                    if ($this->user_model->add_user($data["user"]))
                    {
                        $user_role_vo = $this->user_model->get_user_role();

                        if ($this->input->post("joined_list"))
                        {
                            foreach ($this->input->post("joined_list") as $role_id)
                            {
                                $user_role_obj = $user_role_vo;
                                call_user_func(array($user_role_obj, "set_user_id"), $id);
                                call_user_func(array($user_role_obj, "set_role_id"), $role_id);
                                $this->user_model->add_user_role($user_role_obj);
                            }
                        }
                        unset($_SESSION["user_vo"]);
                        redirect(base_url()."mastercfg/user/view/".$id);
                    }
                    else
                    {
                        $_SESSION["NOTICE"] = "submit_error";
                    }
                }
            }
        }

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        if (empty($data["user"]))
        {
            if (($data["user"] = $this->user_model->get_user()) === FALSE)
            {
                $_SESSION["NOTICE"] = "sql_error";
            }
            else
            {
                $_SESSION["user_vo"] = serialize($data["user"]);
            }
        }

        $data["role_list"] = $this->user_model->get_role_list();
        $data["joined_list"] = array();

        if ($this->input->post("joined_list"))
        {
            $inc_list = $this->input->post("joined_list");
            $data["joined_list"] = get_inclusion($data["role_list"], $inc_list, "id");
            $data["role_list"] = get_exclusion($data["role_list"], $inc_list, "id");
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('mastercfg/user/user_detail_v',$data);
    }

    public function view($id="")
    {
        if ($id)
        {
            $sub_app_id = $this->_get_app_id()."02";

            if ($this->input->post("posted"))
            {

                if (isset($_SESSION["user_vo"]))
                {
                    $this->user_model->include_user_vo();
                    $data["user"] = unserialize($_SESSION["user_vo"]);

                    $_POST["password"] = empty($_POST["password"])?$data["user"]->get_password():md5($_POST["password"]);

                    if ($data["user"]->get_id() != $_POST["id"])
                    {
                        $proc = $this->user_model->get_user(array("id"=>$_POST["id"]));
                        if (!empty($proc))
                        {
                            $_SESSION["NOTICE"] = "user_existed";
                        }
                    }
                    else
                    {
                        set_value($data["user"], $_POST);

                        if ($this->user_model->update_user($data["user"]))
                        {
                            $this->user_model->del_user_role(array("user_id"=>$id));
                            $user_role_vo = $this->user_model->get_user_role();

                            if ($this->input->post("joined_list"))
                            {
                                foreach ($this->input->post("joined_list") as $role_id)
                                {
                                    $user_role_obj = $user_role_vo;
                                    call_user_func(array($user_role_obj, "set_user_id"), $id);
                                    call_user_func(array($user_role_obj, "set_role_id"), $role_id);
                                    $this->user_model->add_user_role($user_role_obj);
                                }
                            }
                            unset($_SESSION["user_vo"]);
                            redirect(base_url()."mastercfg/user/view/".$id);
                        }
                        else
                        {
                            $_SESSION["NOTICE"] = "submit_error";
                        }
                    }
                }
            }

            include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
            $data["lang"] = $lang;

            if (empty($data["user"]))
            {
                if (($data["user"] = $this->user_model->get_user(array("id"=>$id))) === FALSE)
                {
                    $_SESSION["NOTICE"] = "sql_error";
                }
                else
                {
                    $_SESSION["user_vo"] = serialize($data["user"]);
                }
            }

            $data["role_list"] = $this->user_model->get_role_list();
            $data["joined_list"] = array();
            if ($this->input->post("joined_list"))
            {
                $inc_list = $this->input->post("joined_list");
                $data["joined_list"] = get_inclusion($data["role_list"], $inc_list, "id");
                $data["role_list"] = get_exclusion($data["role_list"], $inc_list, "id");
            }
            else
            {
                $inc_list = $this->user_model->get_user_role_list(array("user_id"=>$id));
                $data["joined_list"] = get_inclusion($data["role_list"], $inc_list, "id", "role_id");
                $data["role_list"] = get_exclusion($data["role_list"], $inc_list, "id", "role_id");
            }

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('mastercfg/user/user_detail_v',$data);
        }
    }

    public function delete($id="")
    {
        if (($user_vo = $this->user_model->get_user(array("id"=>$id))) === FALSE)
        {
            $_SESSION["NOTICE"] = "submit_error";
        }
        else {
            if (empty($user_vo))
            {
                $_SESSION["NOTICE"] = "user_not_found";
            }
            else
            {
                if (!$this->user_model->inactive_user($user_vo))
                {
                    $_SESSION["NOTICE"] = "submit_error";
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"]))
        {
            redirect($_SESSION["LISTPAGE"]);
        }
        else
        {
            redirect(current_url());
        }

    }

    public function _get_app_id(){
        return $this->app_id;
    }

    public function _get_lang_id(){
        return $this->lang_id;
    }
}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */