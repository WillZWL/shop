<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function set_value(&$obj, $value = array())
{
    $class_methods = get_class_methods($obj);
    if (!empty($class_methods)) {
        if (is_array($value)) {
            foreach ($class_methods as $fct_name) {
                if (substr($fct_name, 0, 4) == "set_") {
                    $rskey = substr($fct_name, 4);
                    if (isset($value[$rskey])) {
                        call_user_func(array($obj, "set_" . $rskey), $value[$rskey]);
                    }
                }
            }
        } elseif (is_object($value)) {
            foreach ($class_methods as $fct_name) {
                if (substr($fct_name, 0, 4) == "set_") {
                    $rskey = substr($fct_name, 4);
                    if (!is_null($gvalue = @call_user_func(array($value, "get_" . $rskey)))) {
                        call_user_func(array($obj, "set_" . $rskey), $gvalue);
                    }
                }
            }
        }
    }
}

function compare_object($old_obj, $new_obj, $trim_white_space = FALSE, $select_excl = array())
{
    if (get_class($old_obj) == get_class($new_obj)) {
        $class_methods = get_class_methods($old_obj);
        $same = TRUE;
        $excl_array = array("create_on", "create_by", "create_at", "modify_on", "modify_by", "modify_at");
        if ($select_excl) {
            $excl_array = array_merge($excl_array, $select_excl);
        }
        foreach ($class_methods AS $field) {
            if (substr($field, 0, 4) == "get_") {
                if (!in_array(substr($field, 4), $excl_array)) {
                    if ($trim_white_space) {
                        if (trim(call_user_func(array($old_obj, $field))) <> trim(call_user_func(array($new_obj, $field)))) {
                            return FALSE;
                        }
                    } else {
                        if (call_user_func(array($old_obj, $field)) <> call_user_func(array($new_obj, $field))) {
                            return FALSE;
                        }
                    }
                }
            }
        }
        return TRUE;
    } else {
        return FALSE;
    }
}

function clone_interface(&$obj, $int_obj)
{
    $excl_array = array("create_on", "create_by", "create_at", "modify_on", "modify_by", "modify_at");
    $class_methods = get_class_methods($obj);
    if (!empty($class_methods)) {
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 4) == "set_") {
                $rskey = substr($fct_name, 4);
                if (!in_array($rskey, $excl_array)) {
                    if (!is_null($gvalue = @call_user_func(array($value, "get_" . $rskey)))) {
                        call_user_func(array($obj, "set_" . $rskey), $gvalue);
                    }
                }
            }
        }
    }
}

function get_inclusion($src_list, $inc_list = array(), $src_key, $inc_key = "")
{
    $newobj = array();

    if (is_object($inc_list)) {
        foreach ($inc_list as $obj) {
            $tmplist[] = call_user_func(array($obj, "get_" . $inc_key));
        }
        $inc_list = $tmplist;
    }

    if (empty($inc_list)) {
        $inc_list = array();
    }

    foreach ($src_list as $obj) {
        $ar_src_value = array();
        if (is_array($src_key)) {
            foreach ($src_key as $rs_key) {
                $ar_src_value[] = call_user_func(array($obj, "get_" . $rs_key));
            }
            $src_value = @implode("::", $ar_src_value);
        } else {
            $src_value = call_user_func(array($obj, "get_" . $src_key));
        }
        if (in_array($src_value, $inc_list)) {
            $newobj[] = $obj;
        }
    }
    return (object)$newobj;
}

function get_exclusion($src_list, $inc_list = array(), $src_key, $inc_key = "")
{
    $newobj = array();

    if (is_object($inc_list)) {
        foreach ($inc_list as $obj) {
            $tmplist[] = call_user_func(array($obj, "get_" . $inc_key));
        }
        $inc_list = $tmplist;
    }

    if (empty($inc_list)) {
        $inc_list = array();
    }

    foreach ($src_list as $obj) {
        if (is_array($src_key)) {
            foreach ($src_key as $rs_key) {
                $ar_src_value[] = call_user_func(array($obj, "get_" . $rs_key));
            }
            $src_value = @implode("::", $ar_src_value);
        } else {
            $src_value = call_user_func(array($obj, "get_" . $src_key));
        }
        if (in_array($src_value, $inc_list) === FALSE) {
            $newobj[] = $obj;
        }
    }
    return (object)$newobj;
}

function obj_to_query($obj)
{
    $class_methods = get_class_methods($obj);
    if (!empty($class_methods)) {
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 4) == "get_") {
                $rskey = substr($fct_name, 4);
                if (!(strpos($rskey, "create_") === 0 || strpos($rskey, "modify_") === 0)) {
                    $rs_ar[] = $rskey . "=" . htmlentities(addslashes(@call_user_func(array($obj, "get_" . $rskey))), ENT_QUOTES, 'UTF-8');
                }
            }
        }
        return @implode("&", $rs_ar);
    }

}


