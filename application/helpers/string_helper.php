<?php

if (!function_exists('camelcase2underscore')) {
    function camelcase2underscore($name)
    {
        return strtolower(preg_replace('/(?<!^)([A-Z]|\d+){1}/', '_$1', $name));
    }
}

if (!function_exists('underscore2camelcase')) {
    function underscore2camelcase($name)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
    }
}

if (!function_exists('replace_special_chars')) {
    function replace_special_chars($replaced_data)
    {
        $original_data = "";
        $original_data = str_replace('&amp;', '&', $replaced_data);
        $original_data = str_replace('&gt;', '>', $original_data);
        $original_data = str_replace('&lt;', '<', $original_data);

        return $original_data;
    }
}


// ------------------------------------------------------------------------

/**
 * Upper first character function
 *
 * @access    public
 * @param    string
 * @return    string
 */
if (!function_exists('str_first_char_upper')) {
    function str_first_char_upper($data)
    {
        if (empty($data)) {
            return $data;
        }

        return strtoupper(substr($data, 0, 1)) . substr($data, 1);
    }
}

/**
 * Replace database table alias function
 *
 * @access    public
 * @param    string
 * @return    string
 */
if (!function_exists('replace_db_alias')) {
    function replace_db_alias($data, $replace_list)
    {
        if (!is_array($data) || !is_array($replace_list)) {
            return $data;
        }

        $search_list;
        $new_replace_list = array();

        foreach ($replace_list as $search => $replace) {
            $search_list[] = '/' . $search . '\./';
            $new_replace_list[] = $replace . '.';
        }

        $result = array();

        foreach ($data as $key => $value) {
            $key = preg_replace($search_list, $new_replace_list, $key);
            $result[$key] = $value;
        }

        return $result;
    }
}

if (!function_exists('cutstr')) {
    function cutstr($string, $length, $dot = '')
    {

        if (strlen($string) <= $length) {
            return $string;
        }

        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

        $strcut = '';

        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t < 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }

            if ($noc >= $length) {
                break;
            }

        }
        if ($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);


//		$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

        return $strcut . $dot;
    }
}


if (!function_exists('strip_invalid_xml')) {
    function strip_invalid_xml($value)
    {
        $ret = "";
        $current;
        if ($value == "") {
            return $ret;
        }

        $encoding = ($enc = mb_detect_encoding($value)) ? $enc : "UTF-8";

        $value = iconv($encoding, "UTF-8//IGNORE", $value);

        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $current = ord($value{$i}) . "\n";
            if (($current == 0x9)
                || ($current == 0xA)
                || ($current == 0xD)
                || (($current >= 0x20)
                && ($current <= 0xD7FF))
                || (($current >= 0xE000)
                && ($current <= 0xFFFD))
                || (($current >= 0x10000)
                && ($current <= 0x10FFFF))) {
                $ret .= chr($current);
            } else {
                $ret .= " ";
            }
        }
        return $ret;
    }
}

if (!function_exists('xmlspecialchars')) {
    function xmlspecialchars($text, $strip_invalid = true)
    {
        if ($strip_invalid) {
            return str_replace('&#039;', '&apos;', htmlspecialchars(strip_invalid_xml($text), ENT_QUOTES));
        } else {
            return str_replace('&#039;', '&apos;', htmlspecialchars($text, ENT_QUOTES));
        }
    }
}

if (!function_exists('get_domain')) {
    function get_domain()
    {
        if (defined('DOMAIN')) {
            $domain = DOMAIN;
            $tmp = strpos($_SERVER['HTTP_HOST'], $domain);
        } else {
            $tmp = strpos($_SERVER['HTTP_HOST'], 'vb');
            $domain = substr($_SERVER['HTTP_HOST'], ($tmp ? $tmp : 0));
        }

        if (($tmp === false) || ($tmp == 0)) {
            $sub_domain = '';
        } else {
            $sub_domain = substr($_SERVER['HTTP_HOST'], 0, $tmp - 1);
        }

        return array("sub_domain" => $sub_domain, "domain" => $domain);
    }
}

if (!function_exists('check_domain')) {
    function check_domain()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $check_domain = ($_SERVER['SERVER_ADDR'] != $_SERVER['HTTP_HOST']);
        if ($check_domain) {
            $ar_domain = get_domain();
            $sub_domain = $ar_domain["sub_domain"];
            $domain = $ar_domain["domain"];
        }
        return $domain;
    }
}

if (!function_exists('replace_url_domain')) {
    function replace_url_domain($domain)
    {
        if (!function_exists('uri_string')) {
            include_once BASEPATH . "helpers/url_helper.php";
        }

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";
        $httpsurl = $protocol . $domain . uri_string();
        if ($_SERVER['QUERY_STRING'] != "") {
            $httpsurl .= "?" . $_SERVER['QUERY_STRING'];
        }
        return $httpsurl;
    }
}

if (!function_exists('http_build_raw_query')) {
    function http_build_raw_query($query_array, $separator = '&')
    {
        $query_str = array();
        foreach ($query_array as $rskey => $rsvalue) {
            $query_str[] = rawurlencode($rskey) . "=" . rawurlencode($rsvalue);
        }
        return @implode($separator, $query_str);
    }
}
if (!function_exists('check_finance_role')) {
    function check_finance_role($returnString = false)
    {
        $account_role = array("acc_staff", "acc_lead", "acc_man", "com_man", "com_staff", "com_lead");

        if (isset($_SESSION["user"]["role_id"])) {
            foreach ($_SESSION["user"]["role_id"] as $role) {
                if (in_array($role, $account_role)) {
                    if ($returnString) {
                        return "so.finance_dispatch_date as dispatch_date";
                    } else {
                        return true;
                    }
                }
            }
        }
        if ($returnString) {
            return "so.dispatch_date";
        } else {
            return false;
        }
    }
}
