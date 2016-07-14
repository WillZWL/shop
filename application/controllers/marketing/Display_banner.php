<?php

class Display_banner extends MY_Controller
{
    private $appId = "MKT0020";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'directory', 'notice'));
    }

    public function index()
    {
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $this->load->view('marketing/display_banner/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function getList()
    {
        $data["catlist"] = $this->sc['DisplayCategoryBanner']->getListWithName('1', '0');
        $data["level"] = 1;
        $data["objlist"] = $this->sc['DisplayBanner']->getDisplayList(array("banner_status" => 1));
        $this->load->view('marketing/display_banner/list', $data);
    }

    public function getnext()
    {
        if ($this->input->get('id') == "" || $this->input->get('level') == "" || $this->input->get('display_id') == "") {
            return;
        } else {
            $data["catlist"] = $this->sc['DisplayCategoryBanner']->getListWithName($this->input->get('level'), $this->input->get('id'));
            $data["level"] = $this->input->get('level');
            $objlist = $this->sc['DisplayBanner']->getDisplayList(array("id" => $this->input->get('display_id'), "banner_status" => 1));
            $objlist = (array)$objlist;
            $data['banner_obj'] = $objlist[0];
            $this->load->view('marketing/display_banner/next', $data);
        }
    }

    public function to_publish($catid = "")
    {
        define('GRAPHIC_PH', $this->sc['ContextConfig']->valueOf("default_graphic_path"));
        $_SESSION["LISTPAGE"] = $_SERVER['QUERY_STRING'];
        $item = array();
        $item = explode('/', $_SERVER['QUERY_STRING']);
        $display_id = $item[6];
        $country_id = $item[7];
        $lang_id = $item[8];
        if ($country_id != "ALL") {
            if ($country_obj = $this->sc['Country']->get(array("id" => $country_id))) {
                $lang_id = $country_obj->getLanguageId();
                $pv_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("usage" => "PV", "display_id" => $display_id, "country_id" => $country_id, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
                $pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("usage" => "PB", "display_id" => $display_id, "country_id" => $country_id, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            }
        } else {
            $country_id = "";
            $pv_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("usage" => "PV", "display_id" => $display_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            $pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("usage" => "PB", "display_id" => $display_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
        }
        if ($country_id) {
            $current_pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PB", "country_id" => $country_id, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            $num_of_current_pb_dbc_list = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PB", "country_id" => $country_id, "lang_id" => $lang_id));
        } else {
            $current_pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "position_id ASC"));
            $num_of_current_pb_dbc_list = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $lang_id));
        }
        $default_pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => "en"), array("orderby" => "position_id ASC"));
        if ($num_of_current_pb_dbc_list == 0) {
            foreach ($default_pb_dbc_list AS $d_pb_dbc_obj) {
                $c_pb_dbc_obj = $this->sc['DisplayBanner']->getDao('DisplayBannerConfig')->get();
                $c_pb_dbc_obj->setDisplayId($d_pb_dbc_obj->getDisplayId());
                $c_pb_dbc_obj->setUsage($d_pb_dbc_obj->getUsage());
                $c_pb_dbc_obj->setCountryId($country_id);
                $c_pb_dbc_obj->setLangId($lang_id);
                $c_pb_dbc_obj->setPositionId($d_pb_dbc_obj->getPositionId());
                $c_pb_dbc_obj->setBannerType($d_pb_dbc_obj->getBannerType());
                $c_pb_dbc_obj->setHeight($d_pb_dbc_obj->getHeight());
                $c_pb_dbc_obj->setWidth($d_pb_dbc_obj->getWidth());
                if (!$this->sc['DisplayBanner']->insertDisplayBannerConfig($c_pb_dbc_obj)) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }
            }
        }

        if (!$_SESSION["NOTICE"]) {
            foreach ($pv_dbc_list AS $pv_dbc_obj) {
                $pv_display_id = $pv_dbc_obj->getDisplayId();
                $pv_country_id = $pv_dbc_obj->getCountryId();
                $pv_lang_id = $pv_dbc_obj->getLangId();
                $pv_position_id = $pv_dbc_obj->getPositionId();
                if ($pv_country_id) {
                    $pb_dbc_obj = $this->sc['DisplayBanner']->getDao('DisplayBannerConfig')->get(array("usage" => "PB", "display_id" => $pv_display_id, "country_id" => $pv_country_id, "lang_id" => $pv_lang_id, "position_id" => $pv_position_id));
                } else {
                    $pb_dbc_obj = $this->sc['DisplayBanner']->getDao('DisplayBannerConfig')->get(array("usage" => "PB", "display_id" => $pv_display_id, "country_id IS NULL" => NULL, "lang_id" => $pv_lang_id, "position_id" => $pv_position_id));
                }
                $pb_dbc_obj->setBannerType($pv_dbc_obj->getBannerType());
                $pb_dbc_obj->setHeight($pv_dbc_obj->getHeight());
                $pb_dbc_obj->setWidth($pv_dbc_obj->getWidth());
                $pb_dbc_obj->setStatus(1);
                if ($this->sc['DisplayBanner']->updateDisplayBannerConfig($pb_dbc_obj) === FALSE) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }
            }
            if (!$_SESSION["NOTICE"]) {
                foreach ($pb_dbc_list AS $pb_dbc_obj) {
                    $display_banner_config_id = $pb_dbc_obj->getId();
                    $banner_type = $pb_dbc_obj->getBannerType();
                    $position_id = $pb_dbc_obj->getPositionId();
                    if ($pv_country_id) {
                        if ($catid) {
                            $pv_list = $this->sc['DisplayCategoryBanner']->getDisplayBannerList(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $pv_list = $this->sc['DisplayBanner']->getDisplayBannerList(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        }
                    } else {
                        if ($catid) {
                            $pv_list = $this->sc['DisplayCategoryBanner']->getDisplayBannerList(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $pv_list = $this->sc['DisplayBanner']->getDisplayBannerList(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id), array("orderby" => "slide_id ASC"));
                        }
                    }
                    if ($pv_list) {
                        foreach ($pv_list AS $pv_obj) {
                            $replace_flash = $replace_image = TRUE;
                            $slide_id = $pv_obj->getSlideId();
                            if ($pv_country_id) {
                                if ($catid) {
                                    $pb_obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                } else {
                                    $pb_obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $pv_country_id, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                }
                            } else {
                                if ($catid) {
                                    $pb_obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                } else {
                                    $pb_obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PB", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $lang_id, "slide_id" => $slide_id));
                                }
                            }

                            $pv_image_id = $pv_obj->getImageId();
                            $pv_flash_id = $pv_obj->getFlashId();
                            if ($pb_obj) {
                                $pb_action = "update";
                                $banner_type = $pb_dbc_obj->getBannerType();
                                $pb_flash_id = $pb_obj->getFlashId();
                                $pb_image_id = $pb_obj->getImageId();
                                if ($pb_flash_id) {
                                    $pb_flash_graphic = $this->sc['DisplayBanner']->getGraphic(array("id" => $pb_flash_id));
                                    if ($pv_flash_id) {
                                        $pv_flash_graphic = $this->sc['DisplayBanner']->getGraphic(array("id" => $pv_flash_id));
                                        if ($pv_flash_graphic->getFile() != $pb_flash_graphic->getFile()) {
                                            if ($catid) {
                                                $db_num_rows = $this->sc['DisplayCategoryBanner']->getDbNumRows(array("flash_id" => $pb_flash_id));
                                            } else {
                                                $db_num_rows = $this->sc['DisplayBanner']->getDbNumRows(array("flash_id" => $pb_flash_id));
                                            }
                                            if ($db_num_rows == 1) {
                                                @unlink(GRAPHIC_PH . $pb_flash_graphic->getLocation() . $pb_flash_graphic->getFile());
                                                $pb_flash_graphic->setStatus(0);
                                                if (!$this->sc['DisplayBanner']->updateGraphic($pb_flash_graphic)) {
                                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                                }
                                                $pb_obj->setFlashId(NULL);
                                            }
                                        } else {
                                            $replace_flash = FALSE;
                                        }
                                    } else {
                                        if ($catid) {
                                            $db_num_rows = $this->sc['DisplayCategoryBanner']->getDbNumRows(array("flash_id" => $pb_flash_id));
                                        } else {
                                            $db_num_rows = $this->sc['DisplayBanner']->getDbNumRows(array("flash_id" => $pb_flash_id));
                                        }
                                        if ($db_num_rows == 1) {
                                            @unlink(GRAPHIC_PH . $pb_flash_graphic->getLocation() . $pb_flash_graphic->getFile());
                                            $pb_flash_graphic->setStatus(0);
                                            if (!$this->sc['DisplayBanner']->updateGraphic($pb_flash_graphic)) {
                                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                            }
                                            $pb_obj->setFlashId(NULL);
                                        }
                                    }
                                }
                                if ($pb_image_id) {
                                    $pb_image_graphic = $this->sc['DisplayBanner']->getGraphic(array("id" => $pb_image_id));
                                    if ($pv_image_id) {
                                        $pv_image_graphic = $this->sc['DisplayBanner']->getGraphic(array("id" => $pv_image_id));
                                        if ($pv_image_graphic->getFile() != $pb_image_graphic->getFile()) {
                                            if ($catid) {
                                                $db_num_rows = $this->sc['DisplayCategoryBanner']->getDbNumRows(array("image_id" => $pb_image_id));
                                            } else {
                                                $db_num_rows = $this->sc['DisplayBanner']->getDbNumRows(array("image_id" => $pb_image_id));
                                            }
                                            if ($db_num_rows == 1) {
                                                @unlink(GRAPHIC_PH . $pb_image_graphic->getLocation() . $pb_image_graphic->getFile());
                                                $pb_image_graphic->setStatus(0);
                                                if (!$this->sc['DisplayBanner']->updateGraphic($pb_image_graphic)) {
                                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                                }
                                                $pb_obj->set_image_id(NULL);
                                            }
                                        } else {
                                            $replace_image = FALSE;
                                        }
                                    } else {
                                        if ($catid) {
                                            $db_num_rows = $this->sc['DisplayCategoryBanner']->getDbNumRows(array("image_id" => $pb_image_id));
                                        } else {
                                            $db_num_rows = $this->sc['DisplayBanner']->getDbNumRows(array("image_id" => $pb_image_id));
                                        }
                                        if ($db_num_rows == 1) {
                                            $pb_image_graphic = $this->sc['DisplayBanner']->getGraphic(array("id" => $pb_image_id));
                                            @unlink(GRAPHIC_PH . $pb_image_graphic->getLocation() . $pb_image_graphic->getFile());
                                            $pb_image_graphic->setStatus(0);
                                            if (!$this->sc['DisplayBanner']->updateGraphic($pb_image_graphic)) {
                                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                            }
                                            $pb_obj->set_image_id(NULL);
                                        }
                                    }
                                }
                            } else {
                                $pb_action = "insert";
                                if ($catid) {
                                    $pb_obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner();
                                    $pb_obj->setCatid($catid);
                                } else {
                                    $pb_obj = $this->sc['DisplayBanner']->getDisplayBanner();
                                }
                                $pb_obj->setDisplayBannerConfigId($pv_obj->getDisplayBannerConfigId());
                                $pb_obj->setDisplayId($pv_obj->getDisplayId());
                                $pb_obj->setPositionId($pv_obj->getPositionId());
                                $pb_obj->setSlideId($pv_obj->getSlideId());
                                $pb_obj->setCountryId($pv_obj->getCountryId());
                                $pb_obj->setLangId($pv_obj->getLangId());
                            }
                            $pb_obj->setTimeInterval($pv_obj->getTimeInterval());
                            $pb_obj->setUsage("PB");
                            $pb_obj->setLinkType($pv_obj->getLinkType());
                            $pb_obj->setHeight($pv_obj->getHeight());
                            $pb_obj->setWidth($pv_obj->getWidth());
                            $pb_obj->setLink($pv_obj->getLink());
                            $pb_obj->setPriority($pv_obj->getPriority());
                            $pb_obj->setStatus($pv_obj->getStatus());
                            if ($pv_image_id && $replace_image) {
                                $graphic_obj = $this->sc['DisplayBanner']->getGraphic(array("id" => $pv_image_id));
                                $pv_image_link = GRAPHIC_PH . $graphic_obj->getLocation() . $graphic_obj->getFile();
                                @copy($pv_image_link, GRAPHIC_PH . "adbanner/publish/" . $graphic_obj->getFile());

                                $graphic_id = $this->sc['DisplayBanner']->graphic_seq_next_val();
                                $this->sc['DisplayBanner']->updateGraphic_seq($graphic_id);
                                $new_graphic_obj = $this->sc['DisplayBanner']->getGraphic();
                                $new_graphic_obj->setId($graphic_id);
                                $new_graphic_obj->setType("image");
                                $new_graphic_obj->setLocation("adbanner/publish/");
                                $new_graphic_obj->setFile($graphic_obj->getFile());
                                $new_graphic_obj->setStatus(1);
                                if (!$this->sc['DisplayBanner']->insertGraphic($new_graphic_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                                $pb_obj->set_image_id($graphic_id);
                            }
                            if ($pv_flash_id && $banner_type == "F" && $replace_flash) {
                                $graphic_obj = $this->sc['DisplayBanner']->getGraphic(array("id" => $pv_flash_id));
                                $pv_flash_link = GRAPHIC_PH . $graphic_obj->getLocation() . $graphic_obj->getFile();
                                @copy($pv_flash_link, GRAPHIC_PH . "adbanner/publish/" . $graphic_obj->getFile());

                                $graphic_id = $this->sc['DisplayBanner']->graphic_seq_next_val();
                                $this->sc['DisplayBanner']->updateGraphic_seq($graphic_id);

                                $new_graphic_obj = $this->sc['DisplayBanner']->getGraphic();
                                $new_graphic_obj->setId($graphic_id);
                                $new_graphic_obj->setType("flash");
                                $new_graphic_obj->setLocation("adbanner/publish/");
                                $new_graphic_obj->setFile($graphic_obj->getFile());
                                $new_graphic_obj->setStatus(1);
                                if (!$this->sc['DisplayBanner']->insertGraphic($new_graphic_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                                $pb_obj->setFlashId($graphic_id);
                            }

                            if ($catid) {
                                if (!$this->sc['DisplayCategoryBanner']->$pb_action($pb_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            } else {
                                if (!$this->sc['DisplayBanner']->$pb_action($pb_obj)) {
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

    public function view($display_id = '', $country_id = "", $lang_id = "")
    {
        define('GRAPHIC_PH', $this->sc['ContextConfig']->valueOf("default_graphic_path"));

        $catid = $this->input->get('catid');

        if ($country_id != "ALL") {
            if ($country_obj = $this->sc['Country']->getDao('Country')->get(array("id" => $country_id))) {
                $selected_lang_id = $country_obj->getLanguageId();
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
                $current_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id), array("orderby" => "position_id ASC"));
                $num_of_current_dbc_list = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
            } else {
                $current_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id), array("orderby" => "position_id ASC"));
                $num_of_current_dbc_list = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
            }
            $default_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => "en"), array("orderby" => "position_id ASC"));
            if ($num_of_current_dbc_list == 0) {
                foreach ($default_dbc_list AS $d_dbc_obj) {
                    $c_dbc_obj = $this->sc['DisplayBanner']->getDao('DisplayBannerConfig')->get();
                    $c_dbc_obj->setDisplayId($d_dbc_obj->getDisplayId());
                    $c_dbc_obj->setUsage($d_dbc_obj->getUsage());
                    $c_dbc_obj->setCountryId($selected_country_id);
                    $c_dbc_obj->setLangId($selected_lang_id);
                    $c_dbc_obj->setPositionId($d_dbc_obj->getPositionId());
                    $c_dbc_obj->setBannerType($d_dbc_obj->getBannerType());
                    $c_dbc_obj->setHeight($d_dbc_obj->getHeight());
                    $c_dbc_obj->setWidth($d_dbc_obj->getWidth());
                    if (!$this->sc['DisplayBanner']->insertDisplayBannerConfig($c_dbc_obj)) {
                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                    }
                }
            }

            if (!$_SESSION["NOTICE"]) {
                if ($selected_country_id) {
                    $dbc_obj = $this->sc['DisplayBanner']->getDao('DisplayBannerConfig')->get(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
                } else {
                    $dbc_obj = $this->sc['DisplayBanner']->getDao('DisplayBannerConfig')->get(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                }
                $dbc_obj->setBannerType($banner_type);
                if ($this->sc['DisplayBanner']->updateDisplayBannerConfig($dbc_obj) === FALSE) {
                    $_SESSION["NOTICE"] .= "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }

                $dbc_id = $dbc_obj->getId();

                if (!$_SESSION["NOTICE"]) {
                    $pv_action = "update";

                    if ($selected_country_id) {
                        if ($catid) {
                            $db_obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
                        } else {
                            $db_obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id" => $selected_country_id, "lang_id" => $selected_lang_id));
                        }
                    } else {
                        if ($catid) {
                            $db_obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                        } else {
                            $db_obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $slide_id, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                        }
                    }

                    if ($banner_type == "F" || $banner_type == "I") {
                        for ($no = 1; $no <= 3; $no++) {

                            if ($selected_country_id) {
                                if ($catid) {
                                    $obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                }
                            } else {
                                if ($catid) {
                                    $obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                }
                            }
                            if ($obj) {
                                $obj->setStatus(0);
                                if ($obj->getImageId()) {
                                    $graphic_obj = $this->sc['DisplayBanner']->getGraphic(array("id" => $obj->getImageId()));
                                    @unlink(GRAPHIC_PH . $graphic_obj->getLocation() . $graphic_obj->getFile());
                                    $graphic_obj->setStatus(0);
                                    $this->sc['DisplayBanner']->updateGraphic($graphic_obj);
                                    $obj->set_image_id(NULL);
                                }
                                $obj->setPriority(NULL);
                                $obj->setTimeInterval(NULL);

                                if ($catid) {
                                    $this->sc['DisplayCategoryBanner']->update($obj);
                                } else {
                                    $this->sc['DisplayBanner']->update($obj);
                                }
                            }
                        }
                    } elseif ($banner_type == "R") {
                        for ($no = 1; $no <= 3; $no++) {
                            if ($selected_country_id) {
                                if ($catid) {
                                    $obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id" => $country_id, "lang_id" => $selected_lang_id));
                                }
                            } else {
                                if ($catid) {
                                    $obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner(array("catid" => $catid, "usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                } else {
                                    $obj = $this->sc['DisplayBanner']->getDisplayBanner(array("usage" => "PV", "display_id" => $display_id, "position_id" => $position_id, "slide_id" => $no, "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id));
                                }
                            }
                            if ($obj) {
                                $obj->setTimeInterval($time_interval);
                                if ($catid) {
                                    $this->sc['DisplayCategoryBanner']->update($obj);
                                } else {
                                    $this->sc['DisplayBanner']->update($obj);
                                }
                            }
                        }
                    }
                    if (empty($db_obj)) {
                        if ($catid) {
                            $db_obj = $this->sc['DisplayCategoryBanner']->getDisplayBanner();
                            $db_obj->setCatid($catid);
                        } else {
                            $db_obj = $this->sc['DisplayBanner']->getDisplayBanner();
                        }
                        $db_obj->setDisplayBannerConfigId($dbc_id);
                        $db_obj->setDisplayId($display_id);
                        $db_obj->setPositionId($position_id);
                        $db_obj->setSlideId($slide_id);
                        $db_obj->setCountryId($selected_country_id);
                        $db_obj->setLangId($selected_lang_id);
                        $db_obj->setTimeInterval($time_interval);
                        $db_obj->setHeight($this->input->post('height'));
                        $db_obj->setWidth($this->input->post('width'));
                        $db_obj->setUsage("PV");
                        $db_obj->setLinkType($link_type);
                        $db_obj->setLink($link);
                        $db_obj->setPriority($priority);
                        $db_obj->setStatus($status);
                        $pv_action = "insert";
                    } else {
                        $db_obj->setLinkType($link_type);
                        $db_obj->setLink($link);
                        $db_obj->setStatus($status);
                        $db_obj->setPriority($priority);
                        $db_obj->setTimeInterval($time_interval);
                    }

                    if ($_FILES["image"]["name"]) {
                        $isize = getimagesize($_FILES["image"]["tmp_name"]);
                        $width = $isize[0];
                        $height = $isize[1];
                        $width_limit = (int)$dbc_obj->getWidth();
                        $height_limit = (int)$dbc_obj->getHeight();
                        $check_dimension = FALSE;
                        if ((!$err && $width == $width_limit && $height == $height_limit) || !($check_dimension)) {
                            $config['allowed_types'] = 'gif|jpg|jpeg|png';
                            $config['overwrite'] = TRUE;
                            $config['is_image'] = TRUE;
                            $graphic_location = "adbanner/preview/";
                            $config["upload_path"] = GRAPHIC_PH . $graphic_location;

                            if ($pv_action == "update") {
                                $image_id = $db_obj->getImageId();
                                if ($catid) {
                                    $db_num_rows = $this->sc['DisplayCategoryBanner']->getDbNumRows(array("image_id" => $image_id));
                                } else {
                                    $db_num_rows = $this->sc['DisplayBanner']->getDbNumRows(array("image_id" => $image_id));
                                }

                                if ($db_num_rows == 1) {
                                    $graphic_obj = $this->sc['DisplayBanner']->getGraphic(array("id" => $image_id));
                                    @unlink(GRAPHIC_PH . $graphic_obj->getLocation() . $graphic_obj->getFile());
                                    $graphic_obj->setStatus(0);
                                    if (!$this->sc['DisplayBanner']->updateGraphic($graphic_obj)) {
                                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                    }
                                }
                            }
                            $graphic_id = $this->sc['DisplayBanner']->graphic_seq_next_val();
                            $this->sc['DisplayBanner']->updateGraphic_seq($graphic_id);

                            $graphic_obj = $this->sc['DisplayBanner']->getGraphic();
                            $graphic_obj->setId($graphic_id);
                            $graphic_obj->setLocation($graphic_location);
                            $graphic_obj->setType("image");
                            if ($catid) {
                                $config["file_name"] = $display_id . "_" . $position_id . "_" . $slide_id . "_" . $catid . "_" . $selected_country_id . "_" . $selected_lang_id . "_" . $graphic_id;
                            } else {
                                $config["file_name"] = $display_id . "_" . $position_id . "_" . $slide_id . "_" . $selected_country_id . "_" . $selected_lang_id . "_" . $graphic_id;
                            }

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload("image")) {
                                $res = $this->upload->data();
                                $graphic_obj->setFile($res["file_name"]);
                                $db_obj->set_image_id($graphic_id);
                            } else {
                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->upload->display_errors();
                            }
                            if (!$_SESSION["NOTICE"]) {
                                if (!$this->sc['DisplayBanner']->insertGraphic($graphic_obj)) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        } else {
                            $_SESSION["NOTICE"] = "wrong_dimension";
                        }
                    }
                    if (!$_SESSION["NOTICE"]) {
                        if ($catid) {
                            if (!$this->sc['DisplayCategoryBanner']->getDao('DisplayCategoryBanner')->$pv_action($db_obj)) {
                                {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        } else {
                            if (!$this->sc['DisplayBanner']->getDao('DisplayBanner')->$pv_action($db_obj)) {
                                {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        }
                    }
                }
            }
        }
        $data["different_country_list"] = $this->sc['DisplayBanner']->getDifferentCountryList($display_id, $lang_id);
        $data["disp_obj"] = $this->sc['DisplayBanner']->getDao('Display')->get(array("id" => $display_id));
        if ($catid) {
            $data["cat_obj"] = $this->sc['Category']->get($catid);
        }
        if ($country_id || $lang_id) {
            if ($selected_country_id) {
                $pv_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pv_num_of_banner"] = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PV", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1));
                $pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PB", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pb_num_of_banner"] = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PB", "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1));
            } else {
                $pv_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pv_num_of_banner"] = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1));
                $pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pb_num_of_banner"] = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1));
            }
            //use english as default
            if ($data["pv_num_of_banner"] == 0) {
                $pv_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => "en", "status" => 1), array("orderby" => "position_id ASC"));
                $data["pv_num_of_banner"] = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PV", "country_id IS NULL" => NULL, "lang_id" => "en", "status" => 1));
            }
            if ($pv_dbc_list) {
                foreach ($pv_dbc_list AS $dbc_obj) {
                    $data["pv_db_obj"][$dbc_obj->getPositionId()]["config"] = $dbc_obj;
                    if ($selected_country_id) {
                        if ($catid) {
                            $data["db_pv_list"] = $this->sc['DisplayCategoryBanner']->getDisplayBannerList(array("catid" => $catid, "usage" => $dbc_obj->getUsage(), "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $data["db_pv_list"] = $this->sc['DisplayBanner']->getDisplayBannerList(array("usage" => $dbc_obj->getUsage(), "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        }
                    } else {
                        if ($catid) {
                            $data["db_pv_list"] = $this->sc['DisplayCategoryBanner']->getDisplayBannerList(array("catid" => $catid, "usage" => $dbc_obj->getUsage(), "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        } else {
                            $data["db_pv_list"] = $this->sc['DisplayBanner']->getDisplayBannerList(array("usage" => $dbc_obj->getUsage(), "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id), array("orderby" => "slide_id ASC"));
                        }
                    }
                    foreach ($data["db_pv_list"] AS $db_obj) {
                        if ($catid) {
                            $db_w_g_pv = $this->sc['DisplayCategoryBanner']->getDbWithGraphic($catid, $dbc_obj->getBannerType(), $display_id, $dbc_obj->getPositionId(), $db_obj->getSlideId(), $selected_country_id, $selected_lang_id, "PV", FALSE);
                        } else {
                            $db_w_g_pv = $this->sc['DisplayBanner']->getDbWithGraphic($dbc_obj->getBannerType(), $display_id, $dbc_obj->getPositionId(), $db_obj->getSlideId(), $selected_country_id, $selected_lang_id, "PV", FALSE);
                        }
                        $data["pv_db_obj"][$dbc_obj->getPositionId()]["details"][$db_obj->getSlideId()] = $db_w_g_pv;
                    }
                }
            } else {
                $country_id = "";
            }
            if ($data["pb_num_of_banner"] == 0 && $country_id != ALL) {
                $pb_dbc_list = $this->sc['DisplayBanner']->getDisplayBannerConfigList(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "position_id ASC"));
                $data["pb_num_of_banner"] = $this->sc['DisplayBanner']->getDbcNumRows(array("display_id" => $display_id, "usage" => "PB", "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1));
                $selected_country_id = "";
            }
            if ($pb_dbc_list) {
                // var_dump($pb_dbc_list);die();
                foreach ($pb_dbc_list AS $dbc_obj) {
                    if ($dbc_obj) {
                        if ($catid) {
                            $publish_banner[$dbc_obj->getPositionId()] = $this->sc['DisplayCategoryBanner']->getPublishBanner($catid, $dbc_obj->getDisplayId(), $dbc_obj->getPositionId(), $dbc_obj->getCountryId(), $dbc_obj->getLangId(), $dbc_obj->getUsage());
                        } else {
                            $publish_banner[$dbc_obj->getPositionId()] = $this->sc['DisplayBanner']->getPublishBanner($dbc_obj->getDisplayId(), $dbc_obj->getPositionId(), $dbc_obj->getCountryId(), $dbc_obj->getLangId(), $dbc_obj->getUsage());
                        }

                        $data["pb_db_obj"][$dbc_obj->getPositionId()]["config"] = $dbc_obj;
                    } else {
                        $data["pb_db_obj"][$dbc_obj->getPositionId()]["config"] = "";
                    }

                    if ($selected_country_id) {
                        if ($catid) {
                            $data["pb_db_list"] = $this->sc['DisplayCategoryBanner']->getDisplayBannerList(array("catid" => $catid, "usage" => "PB", "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        } else {
                            $data["pb_db_list"] = $this->sc['DisplayBanner']->getDisplayBannerList(array("usage" => "PB", "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id" => $selected_country_id, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        }
                    } else {
                        if ($catid) {
                            $data["pb_db_list"] = $this->sc['DisplayCategoryBanner']->getDisplayBannerList(array("catid" => $catid, "usage" => "PB", "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        } else {
                            $data["pb_db_list"] = $this->sc['DisplayBanner']->getDisplayBannerList(array("usage" => "PB", "display_id" => $dbc_obj->getDisplayId(), "position_id" => $dbc_obj->getPositionId(), "country_id IS NULL" => NULL, "lang_id" => $selected_lang_id, "status" => 1), array("orderby" => "priority DESC"));
                        }
                    }
                    foreach ($data["pb_db_list"] AS $db_obj) {
                        if ($catid) {
                            if ($db_w_g_pb = $this->sc['DisplayCategoryBanner']->getDbWithGraphic($catid, $dbc_obj->getBannerType(), $display_id, $dbc_obj->getPositionId(), $db_obj->getSlideId(), $selected_country_id, $selected_lang_id, "PB")) {
                                if ($db_obj->getImageId() && $dbc_obj->getBannerType() == "F") {
                                    $data["pb_db_obj"][$dbc_obj->getPositionId()]["backup_image"] = $this->sc['DisplayCategoryBanner']->getDbWithGraphic($catid, $dbc_obj->getBannerType(), $display_id, $dbc_obj->getPositionId(), $db_obj->getSlideId(), $selected_country_id, $selected_lang_id, "PB", TRUE);
                                }
                                $data["pb_db_obj"][$dbc_obj->getPositionId()]["details"][$db_obj->getSlideId()] = $db_w_g_pb;
                            } else {
                                $data["pb_db_obj"][$dbc_obj->getPositionId()]["details"][$db_obj->getSlideId()] = "";
                            }
                        } else {
                            if ($db_w_g_pb = $this->sc['DisplayBanner']->getDbWithGraphic($dbc_obj->getBannerType(), $display_id, $dbc_obj->getPositionId(), $db_obj->getSlideId(), $selected_country_id, $selected_lang_id, "PB")) {
                                if ($db_obj->getImageId() && $dbc_obj->getBannerType() == "F") {
                                    $data["pb_db_obj"][$dbc_obj->getPositionId()]["backup_image"] = $this->sc['DisplayBanner']->getDbWithGraphic($dbc_obj->getBannerType(), $display_id, $dbc_obj->getPositionId(), $db_obj->getSlideId(), $selected_country_id, $selected_lang_id, "PB", TRUE);
                                }
                                $data["pb_db_obj"][$dbc_obj->getPositionId()]["details"][$db_obj->getSlideId()] = $db_w_g_pb;
                            } else {
                                $data["pb_db_obj"][$dbc_obj->getPositionId()]["details"][$db_obj->getSlideId()] = "";
                            }
                        }
                    }
                }
            }
        }
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["publish_banner"] = $publish_banner;
        $data["lang_list"] = $this->sc['Language']->getDao('Language')->getList(array("status" => 1), array("orderby" => "lang_name ASC"));
        $data["country_list"] = $this->sc['Country']->getList(array("status" => 1, "url_enable" => 1), array("orderby" => "name ASC"));
        $data["display_id"] = $display_id;
        $data["lang_id"] = $lang_id;
        $data["country_id"] = $country_id;
        $data['catid'] = $this->input->get('catid');
        $data["lytebox_country_id"] = $country_id;
        if ($country_id == "ALL") {
            if ($country_obj = $this->sc['Country']->get(array("language_id" => $lang_id, "status" => 1, "url_enable" => 1))) {
                $data["lytebox_country_id"] = $country_obj->getId();
            }
        }
        $display_obj_list = $this->sc['DisplayBanner']->getDao('Display')->get(array("banner_status" => 1));
        foreach ($display_obj_list as $obj) {
            $data["show_lightbox"][$obj->getId()] = $obj->getLightboxStatus();
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
        $pb_dbc_list = $this->sc['DisplayBanner']->getDifferentCountryList(array("country_id" => $country_id, "display_id" => $display_id, "usage" => "PB"));
        foreach ($pb_dbc_list AS $pb_dbc_obj) {
            $pb_dbc_obj->setStatus(0);
            if (!$this->sc['DisplayBanner']->updateDisplayBannerConfig($pb_dbc_obj)) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
            }
        }
        redirect($_SESSION["LISTPAGE"]);
    }
}

