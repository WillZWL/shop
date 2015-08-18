<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Colour_extend_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Colour_extend_dao.php");
        $this->set_dao(new Colour_extend_dao());
        include_once(APPPATH . 'libraries/service/Translate_service.php');
        $this->set_translate_service(new Translate_service());
    }

    public function set_translate_service($serv)
    {
        $this->translate_service = $serv;
    }

    public function insert($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->get_dao()->update($obj);
    }

    public function get($where = array())
    {
        if (!count($where)) {
            return $this->get_dao()->get();
        } else {
            return $this->get_dao()->get($where);
        }
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_list_index($where, $option);
    }

    public function translate_color($colorObj, $from_lang = "en", $to_lang)
    {
        $new_lang_text = "";
        try {
            $this->get_translate_service()->translate(nl2br($colorObj->get_name()), $new_lang_text, $from_lang, $to_lang);
        } catch (Exception $ex) {
            $new_lang_text = "";
            mail("oswald-alert@eservicesgroup.com", "Color Translation =" . $colorObj->get_id(), $ex->getMessage(), 'From: website@valuebasket.com');
        }
        if ($new_lang_text != "") {
            $ruObj = $this->get_dao()->get(array("lang_id" => $to_lang, "color_id" => $colorObj->get_id()));
            if ($ruObj) {
                $ruObj->set_name($new_lang_text);
                $ret = $this->get_dao()->insert($ruObj);
            } else {
                $newColorExtend = $this->get_dao()->get();
                $newColorExtend->set_colour_id($colorObj->get_id());
                $newColorExtend->set_lang_id($to_lang);
                print $to_lang . ": " . $new_lang_text . "\r\n";
                $newColorExtend->set_name($new_lang_text);
                $ret = $this->get_dao()->insert($newColorExtend);
            }
            if ($ret === FALSE) {
                mail("oswald-alert@eservicesgroup.com", "Color Translation UPDATE db error" . $colorObj->get_id(), $this->get_dao()->db->last_query(), 'From: website@valuebasket.com');
            }
        }
    }

    public function get_translate_service()
    {
        return $this->translate_service;
    }
}
