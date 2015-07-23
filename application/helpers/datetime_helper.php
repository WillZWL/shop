<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//return Timezone abbreviation, Examples: EST, MDT ...
function get_timezone_abbreviation($tz = 'Europe/London')
{
    $dateTime = new DateTime();
    $dateTime->setTimezone(new DateTimeZone($tz));
    return $dateTime->format('T');
}

// SBF #2467 drop-in function for date(). Returns date format d/m/Y, example 04/06/2013
function date_ex($format = "", $timestamp = "")
{
    $date = date('Y-m-d', $timestamp);
    $date = new DateTime($date);
    return $date->format('d/m/Y');
}


