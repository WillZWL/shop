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


