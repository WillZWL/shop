<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('price_round_up')) {
    function price_round_up($price, $round_up)
    {
        if (!is_numeric($round_up)) {
            return $price;
        }
        $new_price = $price;
        $round_up = (string)$round_up;
        list($p_digit, $p_decimal) = @explode(".", $price);
        list($ru_digit, $ru_decimal) = @explode(".", $round_up);

        if ($ru_digit === "") {
            $ru_digit = "0";
        }

        $ru_digit_len = strlen($ru_digit);
        $p_decimal_len = strlen($p_decimal);

        $adj_ru_decimal = str_pad($ru_decimal, $p_decimal_len, "0", STR_PAD_RIGHT);

        if ($p_decimal * 1 > $adj_ru_decimal * 1) {
            $p_digit += 1;
        }

        if ($ru_digit[0] == "0") {
            $new_price = ceil($p_digit / pow(10, $ru_digit_len - 1)) * pow(10, $ru_digit_len - 1);
        } else {
            $p_digit_s = substr($p_digit, 0, $ru_digit_len * -1);
            $p_digit_l = substr($p_digit, $ru_digit_len * -1);
            if ($p_digit_l * 1 > $ru_digit) {
                $p_digit_s += 1;
            }
            $new_price = (int)($p_digit_s . $round_up);
        }
        $new_price = $new_price + (float)("0." . $ru_decimal);
        return number_format($new_price, 2, ".", "");
    }
}

if (!function_exists('average_divide')) {
    function average_divide($price, $divide)
    {
        $avg = floor($price * 100 / $divide) / 100;
        $adj = $price - $avg * $divide;
        $data["first"] = $avg + $adj;
        $data["rest"] = $avg;
        return $data;
    }
}

if (!function_exists('platform_curr_format')) {
    function platform_curr_format($amount, $show_currency = 1)
    {
        if (isset(PUB_Controller::$siteInfo)) {
            return curr_format(PUB_Controller::$siteInfo->getPlatformCurrencyId(), $amount, $show_currency, PUB_Controller::$siteInfo);
        }
        return number_format($amount, 2, ".", "");
    }
}

if (!function_exists('curr_format')) {
    function curr_format($currency_id, $amount, $show_currency = 1, $siteObj)
    {
        if ($siteObj) {
            $sign = $siteObj->getSign();
            $sign_pos = $siteObj->getSignPos();
            $dec_place = $siteObj->getDecPlace();
            $dec_point = $siteObj->getDecPoint();
            $thousands_sep = $siteObj->getThousandsSep();

            $amount_str = number_format($amount, $dec_place, $dec_point, $thousands_sep);

            if ($show_currency) {
                if ($sign_pos == "R") {
                    return $amount_str . " " . $sign;
                }
                return $sign . " " . $amount_str;
            }
            return $amount_str;
        }

        if ($show_currency) {
            return $currency_id . " " . number_format($amount, 2, ".", "");
        }
        return number_format($amount, 2, ".", "");
    }
}

if (!function_exists('platform_curr_round')) {
    function platform_curr_round($platform_id, $amount)
    {
        $dec_place = 2;
        if (isset($_SESSION["PLATFORM_CURRENCY"][$platform_id]["dec_place"])) {
            $dec_place = $_SESSION["PLATFORM_CURRENCY"][$platform_id]["dec_place"];
        }
        return number_format($amount, $dec_place, ".", "");
    }
}

if (!function_exists('price_to_rrp')) {
    function price_to_rrp($amount, $fixed_rrp = 'Y', $rrp_factor = 1.18)
    {
        if ($amount > 0) {
            if ($fixed_rrp == 'Y')
                $markup = $amount * 1.18;
            else
                $markup = $amount * $rrp_factor;

            $remainder = fmod($markup, 5);
            $add_to = 5 - $remainder;
            $rrp = number_format($markup - (-$add_to) - .01, 2, '.', '');
            return number_format($rrp, 2, ".", "");
        }
        return 0;
    }
}

if (!function_exists('curr_round')) {
    function curr_round($currency_id, $amount)
    {
        $dec_place = 2;

        if (isset($_SESSION["CURRENCY"][$currency_id]["dec_place"])) {
            $dec_place = $_SESSION["CURRENCY"][$currency_id]["dec_place"];
        }

        return number_format($amount, $dec_place, ".", "");
    }
}

if (!function_exists('need_random_markup')) {
    function need_random_markup()
    {
        if (!defined('NOT_WELCOME_VISITOR')) {
            return FALSE;
        } elseif (!NOT_WELCOME_VISITOR) {
            return FALSE;
        }

        return TRUE;
    }
}

if (!function_exists('random_markup')) {
    function random_markup($price)
    {
        if (need_random_markup()) {
            if (defined('PLATFORMCOUNTRYID')) {
                switch (strtolower(PLATFORMCOUNTRYID)) {
                    case 'fr' :
                        $dp = 0.9;
                        break;
                    case 'sg' :
                        $dp = 0.95;
                        break;

                    case 'be' :
                    case 'gb' :
                    case 'ie' :
                    case 'us' :
                        $dp = 0.99;
                        break;

                    case 'au' :
                    case 'es' :
                    case 'fi' :
                    case 'hk' :
                    case 'my' :
                    case 'ph' :
                    case 'nz' :
                        $dp = 0;
                        break;

                    default   :
                        $dp = 0;
                }
            } else {
                $dp = 0;
            }

            $temp = substr(strval(floor(time() / 10380)), -4); // 10380 -> 173mins, get rightmost 4 digits in order to avoid overflow of big value calcuation in the following step
            $temp = intval($temp . $temp) % 70;
            $random_factor = 1 + ((5 + ($temp / 10)) / 100);  // random factor from 5% - 12%
            return (round($price * $random_factor, 0) + $dp);
        } else {
            return $price;
        }
    }
}


