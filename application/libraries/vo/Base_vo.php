<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Base_vo
{

    abstract function _get_primary_key();

    public function __construct()
    {
    }

}

/* End of file base_vo.php */
/* Location: ./app/libraries/vo/base_vo.php */