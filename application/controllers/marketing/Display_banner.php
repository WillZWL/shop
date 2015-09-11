<?php

class Display_banner extends MY_Controller
{
    private $appId = "MKT0020";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'directory', 'notice'));
        $this->load->model('marketing/display_banner_model');
        $this->load->model('marketing/display_category_banner_model');
        $this->load->library('service/authorization_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/country_service');
    }

    public function index()
    {
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view('marketing/display_banner/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function get_list()
    {
        $data["catlist"] = $this->display_category_banner_model->get_list('1', '0');
        $data["level"] = 1;

        $data["objlist"] = $this->display_banner_model->get_display_list(array("banner_status" => 1));
        $this->load->view('marketing/display_banner/list', $data);
    }

    public function getnext()
    {
        if ($this->input->get('id') == "" || $this->input->get('level') == "" || $this->input->get('display_id') == "") {
            return;
        } else {
            $data["catlist"] = $this->display_category_banner_model->get_list($this->input->get('level'), $this->input->get('id'));
            $data["level"] = $this->input->get('level');
            $objlist = $this->display_banner_model->get_display_list(array("id" => $this->input->get('display_id'), "banner_status" => 1));
            $objlist = (array)$objlist;
            $data['banner_obj'] = $objlist[0];
            $this->load->view('marketing/display_banner/next', $data);
        }
    }

    public function to_publish($catid = "")
    {
        define('GRAPHIC_PH', $this->context_config_service->value_of("default_graphic_path"));
        $_SESSION["LISTPAGE"] = $_SERVER['QUERY_STRING'];

        $item = array();
        $item = explode('/', $_SERVER['QUERY_STRING']);
        $display_id = $item[6];
        $country_id = $item[7];
        $lang_id = $item[8];
        if ($country_id != "ALL") {
            if ($country_obj = $this->display_banner_model->get_country(array("id" => $country_id))) {
                $lang_id = $country_obj->get_language_id();
                $pv_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("usage" => "PV", "display_id" => $display_id, "country_id" => $country_id, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
                $pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("usage" => "PB", "display_id" => $display_id, "country_id" => $country_id, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            }
        } else {
            $country_id = "";
            $pv_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("usage" => "PV", "display_id" => $display_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            $pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("usage" => "PB", "display_id" => $display_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
        }
        if ($country_id) {
            $current_pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PB", "country_id" => $country_id, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            $num_of_current_pb_dbc_list = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PB", "country_id" => $country_id, "lang_id" => $lang_id));
        } else {
            $current_pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            $num_of_current_pb_dbc_list = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $lang_id));
        }
        $default_pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => "en"), array("orderby" => "position_id ASC"));
        if ($num_of_current_pb_dbc_list == 0) {
            foreach ($default_pb_dbc_list AS $d_pb_dbc_obj) {
                $c_pb_dbc_obj = $this->display_banner_model->get_display_banner_config();
                $c_pb_dbc_obj->set_display_id($d_pb_dbc_obj->get_display_id());
                $c_pb_dbc_obj->set_usage($d_pb_dbc_obj->get_usage());
                $c_pb_dbc_obj->set_country_id($country_id);
                $c_pb_dbc_obj->set_lang_id($lang_id);
                $c_pb_dbc_obj->set_position_id($d_pb_dbc_obj->get_position_id());
                $c_pb_dbc_obj->set_banner_type($d_pb_dbc_obj->get_banner_type());
                $c_pb_dbc_obj->set_height($d_pb_dbc_obj->get_height());
                $c_pb_dbc_obj->set_width($d_pb_dbc_obj->get_width());
                if (!$this->display_banner_model->insert_display_banner_config($c_pb_dbc_obj)) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }
            }
        }

        if (!$_SESSION["NOTICE"]) {
            foreach ($pv_dbc_list AS $pv_dbc_obj) {
                $pv_display_id = $pv_dbc_obj->get_display_id();
                $pv_country_id = $pv_dbc_obj->get_country_id();
                $pv_lang_id = $pv_dbc_obj->get_lang_id();
                $pv_position_id = $pv_dbc_obj->get_position_id();
                if ($pv_country_id) {
                    $pb_dbc_obj = $this->display_banner_model->get_display_banner_config(array("usage" => "PB", "display_id" => $pv_display_id, "country_id" => $pv_country_id, "lang_id" => $pv_lang_id, "position_id" => $pv_position_id));
                } else {
                    $pb_dbc_obj = $this->display_banner_model->get_display_banner_config(array("usage" => "PB", "display_id" => $pv_display_id, "country_id IS NULL" => NULL, "lang_id" => $pv_lang_id, "position_id" => $pv_position_id));
                }
                $pb_dbc_obj->set_banner_type($pv_dbc_obj->get_banner_type());
                $pb_dbc_obj->set_height($pv_dbc_obj->get_height());
                $pb_dbc_obj->set_width($pv_dbc_obj->get_width());
                $pb_dbc_obj->set_status(1);
                if ($this->display_banner_model->update_display_banner_config($pb_dbc_obj) === FALSE) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }
            }
            if (!$_SESSION["NOTICE"]) {
                foreach ($pb_dbc_list AS $pb_dbc_obj) {
                    $display_banner_config_id = $pb_dbc_obj->get_id();
                    $banner_type = $pb_dbc_obj->get_banner_type();
                    $position_id = $pb_dbc_obj->get_position_id();
                    if ($pv_country_id) {
                        if ($catid) {
                            $pv_list = $this->display_category_banner_model->get_display_banner_list(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $pv_list = $this->display_banner_model->get_display_banner_list(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        }
                    } else {
                        if ($catid) {
                            $pv_list = $this->display_category_banner_model->get_display_banner_list(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $pv_list = $this->display_banner_model->get_display_banner_list(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        }
                    }
                    if ($pv_list) {
                        foreach ($pv_list AS $pv_obj) {
                            $replace_flash = $replace_image = TRUE;
                            $slide_id = $pv_obj->get_slide_id();
                            if ($pv_country_id) {
                                if ($catid) {
                                    $pb_obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                } else {
                                    $pb_obj = $this->display_banner_model->get_display_banner(array("usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                }
                            } else {
                                if ($catid) {
                                    $pb_obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                } else {
                                    $pb_obj = $this->display_banner_model->get_display_banner(array("usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                }
                            }

                            $pv_image_id = $pv_obj->get_image_id();
                            $pv_flash_id = $pv_obj->get_flash_id();
                            if ($pb_obj) {
                                $pb_action = "update_display_banner";
                                $banner_type = $pb_dbc_obj->get_banner_type();
                                $pb_flash_id = $pb_obj->get_flash_id();
                                $pb_image_id = $pb_obj->get_image_id();
                                if ($pb_flash_id) {
                                    $pb_flash_graphic = $this->display_banner_model->get_graphic(array("id" => $pb_flash_id));
                                    if ($pv_flash_id) {
                                        $pv_flash_graphic = $this->display_banner_model->get_graphic(array("id" => $pv_flash_id));
                                        if ($pv_flash_graphic->get_file() != $pb_flash_graphic->get_file()) {
                                            if ($catid) {
                                                $db_num_rows = $this->display_category_banner_model->get_db_num_rows(array("flash_id" => $pb_flash_id));
                                            } else {
                                                $db_num_rows = $this->display_banner_model->get_db_num_rows(array("flash_id" => $pb_flash_id));
                                            }
                                            if ($db_num_rows == 1) {
                                                @unlink(GRAPHIC_PH . $pb_flash_graphic->get_location() . $pb_flash_graphic->get_file());
                                                $pb_flash_graphic->set_status(0);
                                                if (!$this->display_banner_model->update_graphic($pb_flash_graphic)) {
                                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                                }
                                                $pb_obj->set_flash_id(NULL);
                                            }
                                        } else {
                                            $replace_flash = FALSE;
                                        }
                                    } else {
                                        if ($catid) {
                                            $db_num_rows = $this->display_category_banner_model->get_db_num_rows(array("flash_id" => $pb_flash_id));
                                        } else {
                                            $db_num_rows = $this->display_banner_model->get_db_num_rows(array("flash_id" => $pb_flash_id));
                                        }
                                        if ($db_num_rows == 1) {
                                            @unlink(GRAPHIC_PH . $pb_flash_graphic->get_location() . $pb_flash_graphic->get_file());
                                            $pb_flash_graphic->set_status(0);
                                            if (!$this->display_banner_model->update_graphic($pb_flash_graphic)) {
                                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                            }
                                            $pb_obj->set_flash_id(NULL);
                                        }
                                    }
                                }
                                if ($pb_image_id) {
                                    $pb_image_graphic = $this->display_banner_model->get_graphic(array("id" => $pb_image_id));
                                    if ($pv_image_id) {
                                        $pv_image_graphic = $this->display_banner_model->get_graphic(array("id" => $pv_image_id));
                                        if ($pv_image_graphic->get_file() != $pb_image_graphic->get_file()) {
                                            if ($catid) {
                                                $db_num_rows = $this->display_category_banner_model->get_db_num_rows(array("image_id" => $pb_image_id));
                                            } else {
                                                $db_num_rows = $this->display_banner_model->get_db_num_rows(array("image_id" => $pb_image_id));
                                            }
                                            if ($db_num_rows == 1) {
                                                @unlink(GRAPHIC_PH . $pb_image_graphic->get_location() . $pb_image_graphic->get_file());
                                                $pb_image_graphic->set_status(0);
                                                if (!$this->display_banner_model->update_graphic($pb_image_graphic)) {
                                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                                }
                                                $pb_obj->set_image_id(NULL);
                                            }
                                        } else {
                                            $replace_image = FALSE;
                                        }
                                    } else {
                                        if ($catid) {
                                            $db_num_rows = $this->display_category_banner_model->get_db_num_rows(array("image_id" => $pb_image_id));
                                        } else {
                                            $db_num_rows = $this->display_banner_model->get_db_num_rows(array("image_id" => $pb_image_id));
                                        }
                                        if ($db_num_rows == 1) {
                                            $pb_image_graphic = $this->display_banner_model->get_graphic(array("id" => $pb_image_id));
                                            @unlink(GRAPHIC_PH . $pb_image_graphic->get_location() . $pb_image_graphic->get_file());
                                            $pb_image_graphic->set_status(0);
                                            if (!$this->display_banner_model->update_graphic($pb_image_graphic)) {
                                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                            }
                                            $pb_obj->set_image_id(NULL);
                                        }
                                    }
                                }
                            } else {
                                $pb_action = "insert_display_banner";
                                if ($catid) {
                                    $pb_obj = $this->display_category_banner_model->get_display_banner();
                                    $pb_obj->set_catid($catid);
                                } else {
                                    $pb_obj = $this->display_banner_model->get_display_banner();
                                }
                                $pb_obj->set_display_banner_config_id($pv_obj->get_display_banner_config_id());
                                $pb_obj->set_display_id($pv_obj->get_display_id());
                                $pb_obj->set_position_id($pv_obj->get_position_id());
                                $pb_obj->set_slide_id($pv_obj->get_slide_id());
                                $pb_obj->set_country_id($pv_obj->get_country_id());
                                $pb_obj->set_lang_id($pv_obj->get_lang_id());
                            }
                            $pb_obj->set_time_interval($pv_obj->get_time_interval());
                            $pb_obj->set_usage("PB");
                            $pb_obj->set_link_type($pv_obj->get_link_type());
                            $pb_obj->set_height($pv_obj->get_height());
                            $pb_obj->set_width($pv_obj->get_width());
                            $pb_obj->set_link($pv_obj->get_link());
                            $pb_obj->set_priority($pv_obj->get_priority());
                            $pb_obj->set_status($pv_obj->get_status());
                            if ($pv_image_id && $replace_image) {
                                $graphic_obj = $this->display_banner_model->get_graphic(array("id" => $pv_image_id));
                                $pv_image_link = GRAPHIC_PH . $graphic_obj->get_location() . $graphic_obj->get_file();
                                @copy($pv_image_link, GRAPHIC_PH . "adbanner/publish/" . $graphic_obj->get_file());

                                $graphic_id = $this->display_banner_model->graphic_seq_next_val();
                                $this->display_banner_model->update_graphic_seq($graphic_id);

                                $new_graphic_obj = $this->display_banner_model->get_graphic();
                                $new_graphic_obj->set_id($graphic_id);
                                $new_graphic_obj->set_type("image");
                                $new_graphic_obj->set_location("adbanner/publish/");
                                $new_graphic_obj->set_file($graphic_obj->get_file());
                                $new_graphic_obj->set_status(1);
                                if (!$this->display_banner_model->insert_graphic($new_graphic_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                                $pb_obj->set_image_id($graphic_id);
                            }
                            if ($pv_flash_id && $banner_type == "F" && $replace_flash) {
                                $graphic_obj = $this->display_banner_model->get_graphic(array("id" => $pv_flash_id));
                                $pv_flash_link = GRAPHIC_PH . $graphic_obj->get_location() . $graphic_obj->get_file();
                                @copy($pv_flash_link, GRAPHIC_PH . "adbanner/publish/" . $graphic_obj->get_file());

                                $graphic_id = $this->display_banner_model->graphic_seq_next_val();
                                $this->display_banner_model->update_graphic_seq($graphic_id);

                                $new_graphic_obj = $this->display_banner_model->get_graphic();
                                $new_graphic_obj->set_id($graphic_id);
                                $new_graphic_obj->set_type("flash");
                                $new_graphic_obj->set_location("adbanner/publish/");
                                $new_graphic_obj->set_file($graphic_obj->get_file());
                                $new_graphic_obj->set_status(1);
                                if (!$this->display_banner_model->insert_graphic($new_graphic_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                                $pb_obj->set_flash_id($graphic_id);
                            }

                            if ($catid) {
                                if (!$this->display_category_banner_model->$pb_action($pb_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            } else {
                                if (!$this->display_banner_model->$pb_action($pb_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        }
                    }
                }
            }
        }
        redirect($_SESSION["LISTPAGE"] . "?catid=$catid");
    }

    public function view($display_id, $country_id = "", $lang_id = "")
    {
        define('GRAPHIC_PH', $this->context_config_service->value_of("default_graphic_path"));

        $catid = $this->input->get('catid');

        if ($country_id != "ALL") {
            if ($country_obj = $this->display_banner_model->get_country(array("id" => $country_id))) {
                $selected_lang_id = $country_obj->get_language_id();
                $selected_country_id = $country_id;
            }
        } else {
            $selected_lang_id = $lang_id;
            $selected_country_id = "";
        }
        if ($this->input->post('posted')) {
            $publish = $this->input->post('publish');
            $position_id = $this->input->post('position_id');
            $link_type = $this->input->post('link_type');
            $link = $this->input->post('link');
            $time_interval = $this->input->post('time_interval');
            $status = $this->input->post('status');
            $slide_id = $this->input->post('slide_id') ? $this->input->post('slide_id') : 0;
            $banner_type = $this->input->post('banner_type');
            $priority = $this->input->post('priority');
            $height = $this->input->post('height');
            $width = $this->input->post('width');

            if ($selected_country_id) {
                $current_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id), array("orderby" => "position_id ASC"));
                $num_of_current_dbc_list = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
            } else {
                $current_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id), array("orderby" => "position_id ASC"));
                $num_of_current_dbc_list = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
            }
            $default_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => "en"), array("orderby" => "position_id ASC"));
            //isnert display banner config record(lang_id == "en")
            if ($num_of_current_dbc_list == 0) {
                foreach ($default_dbc_list AS $d_dbc_obj) {
                    $c_dbc_obj = $this->display_banner_model->get_display_banner_config();
                    $c_dbc_obj->set_display_id($d_dbc_obj->get_display_id());
                    $c_dbc_obj->set_usage($d_dbc_obj->get_usage());
                    $c_dbc_obj->set_country_id($selected_country_id);
                    $c_dbc_obj->set_lang_id($selected_lang_id);
                    $c_dbc_obj->set_position_id($d_dbc_obj->get_position_id());
                    $c_dbc_obj->set_banner_type($d_dbc_obj->get_banner_type());
                    $c_dbc_obj->set_height($d_dbc_obj->get_height());
                    $c_dbc_obj->set_width($d_dbc_obj->get_width());
                    if (!$this->display_banner_model->insert_display_banner_config($c_dbc_obj)) {
                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                    }
                }
            }

            if (!$_SESSION["NOTICE"]) {
                if ($selected_country_id) {
                    $dbc_obj = $this->display_banner_model->get_display_banner_config(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
                } else {
                    $dbc_obj = $this->display_banner_model->get_display_banner_config(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                }
                $dbc_obj->set_banner_type($banner_type);
                if ($this->display_banner_model->update_display_banner_config($dbc_obj) === FALSE) {
                    $_SESSION["NOTICE"] .= "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }

                $dbc_id = $dbc_obj->get_id();

                if (!$_SESSION["NOTICE"]) {
                    $pv_action = "update_display_banner";

                    if ($selected_country_id) {
                        if ($catid) {
                            $db_obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
                        } else {
                            $db_obj = $this->display_banner_model->get_display_banner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
                        }
                    } else {
                        if ($catid) {
                            $db_obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                        } else {
                            $db_obj = $this->display_banner_model->get_display_banner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                        }
                    }

                    if ($banner_type == "F" || $banner_type == "I") {
                        for ($no = 1; $no <= 3; $no++) {
                            //disable other slide

                            if ($selected_country_id) {
                                if ($catid) {
                                    $obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->display_banner_model->get_display_banner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                }
                            } else {
                                if ($catid) {
                                    $obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->display_banner_model->get_display_banner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                }
                            }
                            if ($obj) {
                                $obj->set_status(0);
                                if ($obj->get_image_id()) {
                                    $graphic_obj = $this->display_banner_model->get_graphic(array("id" => $obj->get_image_id()));
                                    @unlink(GRAPHIC_PH . $graphic_obj->get_location() . $graphic_obj->get_file());
                                    $graphic_obj->set_status(0);
                                    $this->display_banner_model->update_graphic($graphic_obj);
                                    $obj->set_image_id(NULL);
                                }
                                $obj->set_priority(NULL);
                                $obj->set_time_interval(NULL);

                                if ($catid) {
                                    $this->display_category_banner_model->update_display_banner($obj);
                                } else {
                                    $this->display_banner_model->update_display_banner($obj);
                                }
                            }
                        }
                    } elseif ($banner_type == "R") {
                        for ($no = 1; $no <= 3; $no++) {
                            if ($selected_country_id) {
                                if ($catid) {
                                    $obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->display_banner_model->get_display_banner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                }
                            } else {
                                if ($catid) {
                                    $obj = $this->display_category_banner_model->get_display_banner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->display_banner_model->get_display_banner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                }
                            }
                            if ($obj) {
                                $obj->set_time_interval($time_interval);
                                if ($catid) {
                                    $this->display_category_banner_model->update_display_banner($obj);
                                } else {
                                    $this->display_banner_model->update_display_banner($obj);
                                }
                            }
                        }
                    }
                    if (empty($db_obj)) {
                        if ($catid) {
                            $db_obj = $this->display_category_banner_model->get_display_banner();
                            $db_obj->set_catid($catid);
                        } else {
                            $db_obj = $this->display_banner_model->get_display_banner();
                        }
                        $db_obj->set_display_banner_config_id($dbc_id);
                        $db_obj->set_display_id($display_id);
                        $db_obj->set_position_id($position_id);
                        $db_obj->set_slide_id($slide_id);
                        $db_obj->set_country_id($selected_country_id);
                        $db_obj->set_lang_id($selected_lang_id);
                        $db_obj->set_time_interval($time_interval);
                        $db_obj->set_height($this->input->post('height'));
                        $db_obj->set_width($this->input->post('width'));
                        $db_obj->set_usage("PV");
                        $db_obj->set_link_type($link_type);
                        $db_obj->set_link($link);
                        $db_obj->set_priority($priority);
                        $db_obj->set_status($status);

                        $pv_action = "insert_display_banner";
                    } else {
                        $db_obj->set_link_type($link_type);
                        $db_obj->set_link($link);
                        $db_obj->set_status($status);
                        $db_obj->set_priority($priority);
                        $db_obj->set_time_interval($time_interval);
                    }
                    /*
                    if($_FILES["flash"]["name"])
                    {
                        if($_FILES["flash"]["size"] > $this->context_config_service->value_of('default_banner_flash_size'))
                        {
                            $_SESSION["NOTICE"] = "flash_too_large";
                        }
                        else
                        {
                            $config['allowed_types'] = 'swf';
                            $config['overwrite'] = TRUE;
                            $config['is_image'] = FALSE;
                            $graphic_location = "adbanner/preview/";
                            $config["upload_path"] = GRAPHIC_PH.$graphic_location;

                            if($pv_action == "update_display_banner")
                            {
                                $flash_id = $db_obj->get_flash_id();
                                if($catid)
                                {
                                    $db_num_rows = $this->display_category_banner_model->get_db_num_rows(array("flash_id"=>$flash_id));
                                }
                                else
                                {
                                    $db_num_rows = $this->display_banner_model->get_db_num_rows(array("flash_id"=>$flash_id));
                                }
                                if($db_num_rows == 1)
                                {
                                    @unlink(GRAPHIC_PH.$graphic_obj->get_location().$graphic_obj->get_file());
                                    $graphic_obj = $this->display_banner_model->get_graphic(array("id"=>$flash_id));
                                    $graphic_obj->set_status(0);
                                    if(!$this->display_banner_model->update_graphic($graphic_obj))
                                    {
                                        $_SESSION["NOTICE"] = "Error: ".__LINE__.": ".$this->db->_error_message();
                                    }
                                }
                            }
                            $graphic_id = $this->display_banner_model->graphic_seq_next_val();
                            $this->display_banner_model->update_graphic_seq($graphic_id);

                            $graphic_obj = $this->display_banner_model->get_graphic();
                            $graphic_obj->set_id($graphic_id);
                            $graphic_obj->set_location($graphic_location);
                            $graphic_obj->set_type("flash");
                            if($catid)
                            {
                                $config["file_name"] = $display_id."_".$position_id."_".$slide_id."_".$catid."_".$selected_country_id."_".$selected_lang_id."_".$graphic_id;
                            }
                            else
                            {
                                $config["file_name"] = $display_id."_".$position_id."_".$slide_id."_".$selected_country_id."_".$selected_lang_id."_".$graphic_id;
                            }

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload("flash"))
                            {
                                $res = $this->upload->data();
                                $graphic_obj->set_file($res["file_name"]);
                                $db_obj->set_flash_id($graphic_id);
                            }
                            else
                            {
                                $_SESSION["NOTICE"] = "Error: ".__LINE__.": ".$this->upload->display_errors();
                            }
                            if(!$_SESSION["NOTICE"])
                            {
                                if(!$this->display_banner_model->insert_graphic($graphic_obj))
                                {
                                    $_SESSION["NOTICE"] = "Error: ".__LINE__.": ".$this->db->_error_message();
                                }
                            }
                        }
                    }
                    */
                    if ($_FILES["image"]["name"]) {
                        $isize = getimagesize($_FILES["image"]["tmp_name"]);
                        $width = $isize[0];
                        $height = $isize[1];
                        $width_limit = (int)$dbc_obj->get_width();
                        $height_limit = (int)$dbc_obj->get_height();
                        $check_dimension = FALSE;
                        if ((!$err && $width == $width_limit && $height == $height_limit) || !($check_dimension)) {
                            $config['allowed_types'] = 'gif|jpg|jpeg|png';
                            $config['overwrite'] = TRUE;
                            $config['is_image'] = TRUE;
                            $graphic_location = "adbanner/preview/";
                            $config["upload_path"] = GRAPHIC_PH . $graphic_location;

                            if ($pv_action == "update_display_banner") {
                                $image_id = $db_obj->get_image_id();
                                if ($catid) {
                                    $db_num_rows = $this->display_category_banner_model->get_db_num_rows(array("image_id" => $image_id));
                                } else {
                                    $db_num_rows = $this->display_banner_model->get_db_num_rows(array("image_id" => $image_id));
                                }

                                if ($db_num_rows == 1) {
                                    $graphic_obj = $this->display_banner_model->get_graphic(array("id" => $image_id));
                                    @unlink(GRAPHIC_PH . $graphic_obj->get_location() . $graphic_obj->get_file());
                                    $graphic_obj->set_status(0);
                                    if (!$this->display_banner_model->update_graphic($graphic_obj)) {
                                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                    }
                                }
                            }
                            $graphic_id = $this->display_banner_model->graphic_seq_next_val();
                            $this->display_banner_model->update_graphic_seq($graphic_id);

                            $graphic_obj = $this->display_banner_model->get_graphic();
                            $graphic_obj->set_id($graphic_id);
                            $graphic_obj->set_location($graphic_location);
                            $graphic_obj->set_type("image");
                            if ($catid) {
                                $config["file_name"] = $display_id . "_" . $position_id . "_" . $slide_id . "_" . $catid . "_" . $selected_country_id . "_" . $selected_lang_id . "_" . $graphic_id;
                            } else {
                                $config["file_name"] = $display_id . "_" . $position_id . "_" . $slide_id . "_" . $selected_country_id . "_" . $selected_lang_id . "_" . $graphic_id;
                            }

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload("image")) {
                                $res = $this->upload->data();
                                $graphic_obj->set_file($res["file_name"]);
                                $db_obj->set_image_id($graphic_id);
                            } else {
                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->upload->display_errors();
                            }
                            if (!$_SESSION["NOTICE"]) {
                                if (!$this->display_banner_model->insert_graphic($graphic_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        } else {
                            $_SESSION["NOTICE"] = "wrong_dimension";
                        }
                    }
                    if (!$_SESSION["NOTICE"]) {
                        if ($catid) {
                            if (!$this->display_category_banner_model->$pv_action($db_obj)) {
                                {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        } else {
                            if (!$this->display_banner_model->$pv_action($db_obj)) {
                                {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        }
                    }
                }
            }
        }

        $data["different_country_list"] = $this->display_banner_model->get_different_country_list($display_id, $lang_id);
        $data["disp_obj"] = $this->display_banner_model->get_display(array("id" => $display_id));
        if ($catid) {
            $data["cat_obj"] = $this->display_category_banner_model->get_cat_detail($catid);
        }

        if ($country_id || $lang_id) {
            if ($selected_country_id) {
                $pv_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pv_num_of_banner"] = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1));
                $pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PB", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pb_num_of_banner"] = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PB", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1));
            } else {
                $pv_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pv_num_of_banner"] = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1));
                $pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pb_num_of_banner"] = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1));
            }
            //use english as default
            if ($data["pv_num_of_banner"] == 0) {
                $pv_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => "en", "status" => 1), array("orderby" => "position_id ASC"));
                $data["pv_num_of_banner"] = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => "en", "status" => 1));
            }

            if ($pv_dbc_list) {
                foreach ($pv_dbc_list AS $dbc_obj) {
                    $data["pv_db_obj"][$dbc_obj->get_position_id()]["config"] = $dbc_obj;
                    if ($selected_country_id) {
                        if ($catid) {
                            $data["db_pv_list"] = $this->display_category_banner_model->get_display_banner_list(array("catid" => $catid, "usage" => $dbc_obj->get_usage(), "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $data["db_pv_list"] = $this->display_banner_model->get_display_banner_list(array("usage" => $dbc_obj->get_usage(), "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        }
                    } else {
                        if ($catid) {
                            $data["db_pv_list"] = $this->display_category_banner_model->get_display_banner_list(array("catid" => $catid, "usage" => $dbc_obj->get_usage(), "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $data["db_pv_list"] = $this->display_banner_model->get_display_banner_list(array("usage" => $dbc_obj->get_usage(), "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        }
                    }
                    foreach ($data["db_pv_list"] AS $db_obj) {
                        if ($catid) {
                            $db_w_g_pv = $this->display_category_banner_model->get_db_w_graphic($catid, $dbc_obj->get_banner_type(), $display_id, $dbc_obj->get_position_id(), $db_obj->get_slide_id(), $selected_country_id, $selected_lang_id, "PV", FALSE);
                        } else {
                            $db_w_g_pv = $this->display_banner_model->get_db_w_graphic($dbc_obj->get_banner_type(), $display_id, $dbc_obj->get_position_id(), $db_obj->get_slide_id(), $selected_country_id, $selected_lang_id, "PV", FALSE);
                        }
                        $data["pv_db_obj"][$dbc_obj->get_position_id()]["details"][$db_obj->get_slide_id()] = $db_w_g_pv;
                    }
                }
            } else {
                $country_id = "";
            }
            if ($data["pb_num_of_banner"] == 0 && $country_id != ALL) {
                $pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pb_num_of_banner"] = $this->display_banner_model->get_dbc_num_rows(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1));
                $selected_country_id = "";
            }
            if ($pb_dbc_list) {
                foreach ($pb_dbc_list AS $dbc_obj) {

                    if ($dbc_obj) {
                        if ($catid) {
                            $publish_banner[$dbc_obj->get_position_id()] = $this->display_category_banner_model->get_publish_banner($catid, $dbc_obj->get_display_id(), $dbc_obj->get_position_id(), $dbc_obj->get_country_id(), $dbc_obj->get_lang_id(), $dbc_obj->get_usage());
                        } else {
                            $publish_banner[$dbc_obj->get_position_id()] = $this->display_banner_model->get_publish_banner($dbc_obj->get_display_id(), $dbc_obj->get_position_id(), $dbc_obj->get_country_id(), $dbc_obj->get_lang_id(), $dbc_obj->get_usage());
                        }

                        $data["pb_db_obj"][$dbc_obj->get_position_id()]["config"] = $dbc_obj;
                    } else {
                        $data["pb_db_obj"][$dbc_obj->get_position_id()]["config"] = "";
                    }

                    if ($selected_country_id) {
                        if ($catid) {
                            $data["pb_db_list"] = $this->display_category_banner_model->get_display_banner_list(array("catid" => $catid, "usage" => "PB", "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        } else {
                            $data["pb_db_list"] = $this->display_banner_model->get_display_banner_list(array("usage" => "PB", "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        }
                    } else {
                        if ($catid) {
                            $data["pb_db_list"] = $this->display_category_banner_model->get_display_banner_list(array("catid" => $catid, "usage" => "PB", "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        } else {
                            $data["pb_db_list"] = $this->display_banner_model->get_display_banner_list(array("usage" => "PB", "display_id" => $dbc_obj->get_display_id(), "position_id" => $dbc_obj->get_position_id(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        }
                    }
                    foreach ($data["pb_db_list"] AS $db_obj) {
                        if ($catid) {
                            if ($db_w_g_pb = $this->display_category_banner_model->get_db_w_graphic($catid, $dbc_obj->get_banner_type(), $display_id, $dbc_obj->get_position_id(), $db_obj->get_slide_id(), $selected_country_id, $selected_lang_id, "PB")) {
                                if ($db_obj->get_image_id() && $dbc_obj->get_banner_type() == "F") {
                                    $data["pb_db_obj"][$dbc_obj->get_position_id()]["backup_image"] = $this->display_category_banner_model->get_db_w_graphic($catid, $dbc_obj->get_banner_type(), $display_id, $dbc_obj->get_position_id(), $db_obj->get_slide_id(), $selected_country_id, $selected_lang_id, "PB", TRUE);
                                }
                                $data["pb_db_obj"][$dbc_obj->get_position_id()]["details"][$db_obj->get_slide_id()] = $db_w_g_pb;
                            } else {
                                $data["pb_db_obj"][$dbc_obj->get_position_id()]["details"][$db_obj->get_slide_id()] = "";
                            }
                        } else {
                            if ($db_w_g_pb = $this->display_banner_model->get_db_w_graphic($dbc_obj->get_banner_type(), $display_id, $dbc_obj->get_position_id(), $db_obj->get_slide_id(), $selected_country_id, $selected_lang_id, "PB")) {
                                if ($db_obj->get_image_id() && $dbc_obj->get_banner_type() == "F") {
                                    $data["pb_db_obj"][$dbc_obj->get_position_id()]["backup_image"] = $this->display_banner_model->get_db_w_graphic($dbc_obj->get_banner_type(), $display_id, $dbc_obj->get_position_id(), $db_obj->get_slide_id(), $selected_country_id, $selected_lang_id, "PB", TRUE);
                                }
                                $data["pb_db_obj"][$dbc_obj->get_position_id()]["details"][$db_obj->get_slide_id()] = $db_w_g_pb;
                            } else {
                                $data["pb_db_obj"][$dbc_obj->get_position_id()]["details"][$db_obj->get_slide_id()] = "";
                            }
                        }
                    }
                }
            }
        }
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $data["publish_banner"] = $publish_banner;
        $data["lang_list"] = $this->display_banner_model->get_language_list(array("status" => 1), array("orderby" => "name ASC"));
        $data["country_list"] = $this->display_banner_model->get_country_list(array("status" => 1, "url_enable" => 1), array("orderby" => "name ASC"));
        $data["display_id"] = $display_id;
        $data["lang_id"] = $lang_id;
        $data["country_id"] = $country_id;
        $data['catid'] = $this->input->get('catid');

        $data["lytebox_country_id"] = $country_id;
        if ($country_id == "ALL") {
            if ($country_obj = $this->country_service->get(array("language_id" => $lang_id, "status" => 1, "url_enable" => 1))) {
                $data["lytebox_country_id"] = $country_obj->get_id();
            }
        }
        $display_obj_list = $this->display_banner_model->get_display_list(array("banner_status" => 1));
        foreach ($display_obj_list as $obj) {
            $data["show_lightbox"][$obj->get_id()] = $obj->get_lightbox_status();
        }

        $data["notice"] = notice($lang);
        $this->load->view('marketing/display_banner/view', $data);
    }

    public function disable($country_id)
    {
        $_SESSION["LISTPAGE"] = $_SERVER['QUERY_STRING'];
        $item = array();
        $item = explode('/', $_SERVER['QUERY_STRING']);
        $display_id = $item[6];
        $pb_dbc_list = $this->display_banner_model->get_display_banner_config_list(array("country_id" => $country_id, "display_id" => $display_id, "usage" => "PB"));
        foreach ($pb_dbc_list AS $pb_dbc_obj) {
            $pb_dbc_obj->set_status(0);
            if (!$this->display_banner_model->update_display_banner_config($pb_dbc_obj)) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
            }
        }
        redirect($_SESSION["LISTPAGE"]);
    }

}

