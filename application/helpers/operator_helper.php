<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function operator($str, &$operator, &$n_value)
{
    $operator[0] = "";
    $n_value[0] = $str;
    if (!is_numeric($str)) {
        $ar_str = @explode("&", $str);
        $count_str = count($ar_str);
        for ($i = 0; $i < $count_str; $i++) {
            if (preg_match("/^(<=|>=|=|<|>)(.*)/", trim($ar_str[$i]), $matches)) {
                $operator[$i] = " " . $matches[1];
                $n_value[$i] = $matches[2];
            } elseif (!is_numeric($ar_str[$i]) && strlen($ar_str[$i]) < 11) {
                $operator[$i] = "=";
                $n_value[$i] = $ar_str[$i];
            }
        }
    }
}

function fetch_operator(&$ar_array, $rskey, $rsvalue)
{
    operator($rsvalue, $operator, $n_value);
    for ($i = 0; $i < count($operator); $i++) {
        if (is_numeric($n_value[$i]) || strlen($n_value) > 10) {
            $ar_array[$rskey . $operator[$i]] = $n_value[$i];
        } else {
            switch (ltrim($operator[$i])) {
                case ">":
                case "<=":
                    $ar_array[$rskey . $operator[$i]] = $n_value[$i] . " 23:59:59";
                    break;
                case ">=":
                case "<":
                    $ar_array[$rskey . $operator[$i]] = $n_value[$i] . " 00:00:00";
                    break;
                case "=":
                    $ar_array[$rskey . " >="] = $n_value[$i] . " 00:00:00";
                    $ar_array[$rskey . " <="] = $n_value[$i] . " 23:59:59";
                    break;
            }
        }
    }
}

