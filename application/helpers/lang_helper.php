<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function lang($str, $lang)
{
    $ar = explode(" ", $str);
    foreach ($ar as $rsstr) {
        $new_ar[] = isset($lang[$rsstr]) ? $lang[$rsstr] : $rsstr;
    }
    $newstr = implode(" ", $new_ar);
    return $newstr;
}

/**
* Gets currenct lang id
* @access   public
* @return   string
*/
function get_lang_id()
{
    static $lang_id = "";
    if (isset($_SESSION["lang_id"])) {
        $lang_id = $_SESSION["lang_id"];
    } else {
        $lang_id = $CI->config->config['lang_id'];
        if (!isset($lang_id)) {
            $lang_id = 'en';
        }
    }
    return $lang_id;
}


