<?php

include_once "Base_service.php";

class Ra_group_service extends Base_service
{
    private $rgc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Ra_group_dao.php");
        $this->set_dao(new Ra_group_dao());
        include_once(APPPATH . "libraries/dao/Ra_group_content_dao.php");
        $this->set_rgc_dao(new Ra_group_content_dao());
        include_once(APPPATH . 'libraries/service/Translate_service.php');
        $this->set_translate_service(new Translate_service());
    }

    public function set_translate_service($serv)
    {
        $this->translate_service = $serv;
    }

    public function translate_ra_group_content($group_id = "", $lang_id = "en")
    {
        if ($group_id) {
            if ($rg_obj = $this->get_dao()->get(array("group_id" => $group_id))) {
                $translate_arr = array();
                $rc_action = "update";
                if (!$new_rc_obj = $this->get_rgc_dao()->get(array("group_id" => $group_id, "lang_id" => $lang_id))) {
                    $rc_action = "insert";
                    $new_rc_obj = $this->get_rgc_dao()->get();
                    $new_rc_obj->set_group_id($group_id);
                    $new_rc_obj->set_lang_id($lang_id);
                }

                if ($rc_obj = $this->get_rgc_dao()->get(array("group_id" => $group_id, "lang_id" => "en"))) {
                    $translate_arr = array(
                        "group_display_name" => $rc_obj->get_group_display_name()
                    );

                    foreach ($translate_arr as $key => $source_text) {
                        if (!empty($source_text)) {
                            $new_lang_text = "";

                            try {
                                $this->get_translate_service()->translate(nl2br($source_text), $new_lang_text, "en", $lang_id);
                            } catch (Exception $ex) {
                                $new_lang_text = "";
                                mail("bd_product_team@eservicesgroup.com", "Translation error ra group id=" . $group_id . " with group name=" . $rg_obj->get_group_name() . "[" . $key . "]", $ex->getMessage(), 'From: website@valuebasket.com');
                            }
                            $new_lang_text = preg_replace('/\<br(\s*)?\/?\>(\n)*/i', "\n", $new_lang_text); // convert from br to nl
                            $new_rc_obj->{"set_{$key}"}($new_lang_text);
                        }
                    }
                    $this->get_rgc_dao()->$rc_action($new_rc_obj);
                }
            }
        }
    }

    public function get_rgc_dao()
    {
        return $this->rgc_dao;
    }

    public function set_rgc_dao(Base_dao $dao)
    {
        $this->rgc_dao = $dao;
    }

    public function get_translate_service()
    {
        return $this->translate_service;
    }
}


