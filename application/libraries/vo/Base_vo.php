<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Base_vo
{

    public function __construct()
    {
    }

    abstract function _get_primary_key();

}
