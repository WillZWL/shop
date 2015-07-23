<?php

class Banner extends MY_Controller
{
    private $app_id = "MKT0001";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'directory', 'notice'));
        $this->load->model('marketing/banner_model');
    }

    public function index()
    {
        include_once APPPATH . "language/" . $this->_get_app_id() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view('marketing/banner/index', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function get_list_old()
    {
        $data["level1_obj"] = $this->banner_model->get_list('1', '');

        if ($this->input->get("level1") != "") {
            $data["level2_obj"] = $this->banner_model->get_list(2, $this->input->get("level1"));
        }
        if ($this->input->get("level2") != "") {
            $data["level3_obj"] = $this->banner_model->get_list(3, $this->input->get("level2"));
        }
        $this->load->view('marketing/banner/list', $data);
    }

    public function get_list()
    {
        $data["objlist"] = $this->banner_model->get_list('1', '0');
        $data["level"] = 1;
        $this->load->view('marketing/banner/list', $data);
    }

    public function getnext()
    {
        if ($this->input->get('id') == "" || $this->input->get('level') == "") {
            return;
        } else {
            $data["objlist"] = $this->banner_model->get_list($this->input->get('level'), $this->input->get('id'));
            $data["level"] = $this->input->get('level');
            $this->load->view('marketing/banner/next', $data);
        }
    }

    public function view($catid)
    {
        if ($catid == "") {
            exit;
        }

        $refresh = "n";

        if ($this->input->post('posted')) {
            $refresh = "y";
            $uploaded = 0;
            $status = $this->input->post('status');
            $type = $this->input->post('type');
            $publish = $this->input->post('publish');
            $template = $this->input->post('template');
            if ($_FILES["image"]["name"] != "") {
                $iarr = explode(".", $_FILES["image"]["name"]);
                $iext = $iarr[count($iarr) - 1];
                if (!in_array($iext, array("gif", "jpg", "png"))) {
                    $ferr++;
                }
            }
            if ($_FILES["flash"]["name"] != "") {
                $farr = explode(".", $_FILES["flash"]["name"]);
                $fext = $farr[count($farr) - 1];
                if ($fext != "swf") {
                    $ferr++;
                }
            }

            if ($ferr) {
                $_SESSION["notice"] = "wrong_file_format";
            } else {
                $pv_action = "update";
                $obj = $this->banner_model->get_banner_obj($catid, $type, "PV");
                if (empty($obj)) {
                    $pv_action = "insert";
                    $obj = $this->banner_model->get_banner_obj();
                    $obj->set_cat_id($catid);
                    $obj->set_type($type);
                    $obj->set_usage("PV");
                }
                $err = 0;

                if ($_FILES["image"]["name"] != "") {
                    $isize = getimagesize($_FILES["image"]["tmp_name"]);
                    $width = $isize[0];
                    $height = $isize[1];
                    $limitx = $this->config->item("banner_width_" . $template . "_" . $type);
                    $limity = $this->config->item("banner_height_" . $template . "_" . $type);
                    if ($_FILES["images"]["size"] > $this->config->item('banner_size_limit')) {
                        $_SESSION["NOTICE"] = "image_too_large";
                        $err++;
                    }
                    if (!$err && $width == $limitx && $height == $limity) {
                        $filename = $catid . "_" . $type . "." . $iext;
                        @copy($_FILES["image"]["tmp_name"], $this->config->item('banner_local_path') . "preview/" . $filename);
                        @unlink($_FILES["image"]["tmp_name"]);
                        $obj->set_image_file($filename);
                        $uploaded = 1;
                    } else {
                        $_SESSION["NOTICE"] = "wrong_dimension";
                        $err++;
                    }
                }
                if ($_FILES["flash"]["name"] != "") {
                    if ($_FILES["flash"]["size"] > $this->config->item('flash_size_limit')) {
                        $_SESSION["NOTICE"] = "flash_too_large";
                        $err++;
                    } else {
                        $filename = $catid . "_" . $type . ".swf";
                        @copy($_FILES["flash"]["tmp_name"], $this->config->item('banner_local_path') . "preview/" . $filename);
                        @unlink($_FILES["flash"]["tmp_name"]);
                        $obj->set_flash_file($filename);
                        $uploaded = 1;
                    }
                }

                if (!$err) {
                    $obj->set_link($this->input->post('link'));
                    $obj->set_link_type($this->input->post('link_type'));
                    $obj->set_status($this->input->post('status'));

                    $ret = $this->banner_model->add_banner($obj, $pv_action);
                    if ($ret === FALSE) {
                        $_SESSION["notice"] = "update_failed";
                        $err++;
                    }
                    if ($this->input->post('removeflash')) {
                        $ret = $this->banner_model->clear_flash($catid, $type, "PV");
                        if ($ret === FALSE) {
                            $_SESSION["notice"] = "update_failed";
                            $err++;
                        }
                    }
                    if (!$err && $publish) {
                        $pb_action = "update";
                        $pvobj = $this->banner_model->get_banner_obj($catid, $type, "PV");
                        $pbobj = $this->banner_model->get_banner_obj($catid, $type, "PB");
                        if (empty($pbobj)) {
                            $pb_action = "insert";
                            $pbobj = $this->banner_model->get_banner_obj();
                            $pbobj->set_cat_id($catid);
                            $pbobj->set_type($type);
                            $pbobj->set_usage("PB");
                        }
                        if ($pvobj->get_image_file() == "" && $pvobj->get_flash_file() == "") {
                            $_SESSION["NOTICE"] = "nothing_to_publish";
                            $pberr++;
                        } else {
                            $image = $this->config->item('banner_local_path') . "preview/" . $pvobj->get_image_file();
                            $flash = $this->config->item('banner_local_path') . "preview/" . $pvobj->get_flash_file();
                            if (file_exists($image) || file_exists($flash)) {
                                @copy($image, $this->config->item('banner_publish_path') . $pvobj->get_image_file());
                                @copy($flash, $this->config->item('banner_publish_path') . $pvobj->get_flash_file());
                                $pbobj->set_image_file($pvobj->get_image_file());
                                $pbobj->set_flash_file($pvobj->get_flash_file());
                                $pbobj->set_link($pvobj->get_link());
                                $pbobj->set_link_type($pvobj->get_link_type());
                                $pbobj->set_status($status);

                                $ret = $this->banner_model->add_banner($pbobj, $pb_action);

                                if ($ret === FALSE) {
                                    $_SESSION["NOTICE"] = "update_failed";
                                }
                            } else {
                                $_SESSION["NOTICE"] = "nothing_to_publish";
                                $pberr++;
                            }
                        }
                    }
                }

                $ret = "Error: " . $this->banner_model->update_status($catid, $status);
                echo $this->db->_error_message();
                if ($ret === FALSE) {
                    $_SESSION["NOTICE"] = "stauts_update_failed";
                } else {
                    if ($err == 0 && $ferr == 0) {
                        unset($_SESSION["NOTICE"]);
                        $refresh = "y";
                    }
                }
            }
        }
        $data = array();
        if ($catid == 0) {
            $data["template_type"] = 1;
        } else {
            $data["template_type"] = 2;
        }

        include_once APPPATH . "language/" . $this->_get_app_id() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $image_list = $this->banner_model->get_banner_list(array("cat_id" => $catid, "usage" => "PV"), array("orderby" => "type asc"));
        $pimage_list = $this->banner_model->get_banner_list(array("cat_id" => $catid, "usage" => "PB"), array("orderby" => "type asc"));

        $data["catobj"] = $this->banner_model->get_cat($catid);
        $data["notice"] = notice($lang);
        $data["image_list"] = $image_list;
        $data["pimage_list"] = $pimage_list;
        $data["refresh"] = $refresh;
        $this->load->view('marketing/banner/view', $data);
    }
}

